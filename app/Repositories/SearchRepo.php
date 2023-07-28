<?php


namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * The SearchRepo class provides functionality for searching and sorting data using Laravel's Eloquent ORM or Query Builder.
 *
 * Author: Felix (https://github.com/felixkpt)
 * Creation Date: July 1, 2023
 * License: MIT
 */
class SearchRepo
{
    protected $builder;

    protected $addedColumns = [];
    protected $sortable = [];
    protected $model_name = '';
    protected $fillable = [];


    /**
     * Create a new instance of SearchRepo.
     *
     * @param mixed $builder The builder instance (EloquentBuilder or QueryBuilder).
     * @param array $searchable The columns to search against.
     * @param array $sortable The sortable columns.
     * @return SearchRepo The SearchRepo instance.
     */
    public static function of($builder, $searchable = [], $sortable = [], $fillable = [])
    {
        $self = new self;
        $self->sortable = $sortable;
        $self->builder = $builder;
        $self->fillable = $fillable;

        $term = request()->q;

        $model = null;
        if (method_exists($builder, 'getModel')) {
            $model = $builder->getModel();

            $currentConnection = DB::getDefaultConnection();
            $self->model_name = Str::replace('_', ' ', Str::after(class_basename(get_class($model)), $currentConnection . '_'));

            if (!$fillable) {
                $fill = $model->getFillable();
                $fill = array_diff($fill, ['user_id']);
                $self->fillable = $fill;
            }

            $searchable = $searchable ?: $model->searchable;
        }

        if (!empty($term) && !empty($searchable)) {
            if ($builder instanceof EloquentBuilder) {
                foreach ($searchable as $column) {
                    if (Str::contains($column, '.')) {
                        [$relation, $column] = Str::parseCallback($column, 2);

                        $builder->orWhereHas($relation, function (EloquentBuilder $query) use ($column, $term) {
                            $query->where($column, 'like', "%$term%");
                        });
                    } else {
                        $builder->orWhere($column, 'like', "%$term%");
                    }
                }
            } elseif ($builder instanceof QueryBuilder) {
                foreach ($searchable as $column) {
                    if (Str::contains($column, '.')) {
                        [$relation, $column] = Str::parseCallback($column, 2);

                        $builder->orWhere(function (QueryBuilder $query) use ($relation, $column, $term) {
                            $query->orWhere($relation . '.' . $column, 'like', "%$term%");
                        });
                    } else {
                        $builder->orWhere($column, 'like', "%$term%");
                    }
                }
            }
        }

        if (request()->has('orderBy')) {
            $orderBy = Str::lower(request()->orderBy);

            if ($model)
                $col = Str::after($orderBy, $model->getTable() . '.');
            if ($model && Schema::hasColumn($model->getTable(), $col) || in_array($orderBy, $sortable)) {
                $orderDirection = request()->orderDirection ?? 'asc';
                $builder->orderBy($orderBy, $orderDirection);
            }
        }

        $self->builder = $builder;

        return $self;
    }

    function orderBy($column)
    {
        $this->builder = $this->builder->orderBy($column);

        return $this;
    }

    /**
     * Add a custom column to the search results.
     *
     * @param string $column The column name.
     * @param \Closure $callback The callback function to generate the column value.
     * @return $this The SearchRepo instance.
     */
    public function addColumn($column, $callback)
    {
        $this->addedColumns[$column] = $callback;

        return $this;
    }

    /**
     * Paginate the search results.
     *
     * @param int $perPage The number of items per page.
     * @param array $columns The columns to retrieve.
     * @return \Illuminate\Pagination\LengthAwarePaginator The paginated results.
     */
    function paginate($perPage = 20, $columns = ['*'])
    {
        $builder = $this->builder;

        $perPage = request()->per_page ?? 10;
        $page = request()->page ?? 1;

        // Handle last page results
        $results = $builder->paginate($perPage, $columns, 'page', $page);
        $currentPage = $results->currentPage();
        $lastPage = $results->lastPage();
        $items = $results->items();

        if ($currentPage > $lastPage && count($items) === 0) {
            $results = $builder->paginate($perPage, $columns, 'page', $lastPage);
        }

        $r = $this->additionalColumns($results);

        $results->setCollection(collect($r));

        $custom = collect($this->getCustoms());

        $results = $custom->merge($results);

        return $results;
    }

    /**
     * Get the search results without pagination.
     *
     * @param array $columns The columns to retrieve.
     * @return array The search results.
     */
    function get($columns = ['*'])
    {
        $results = ['data' => $this->builder->get($columns)];
        $custom = collect($this->getCustoms());

        $results = $custom->merge($results);

        return $results;
    }

    /**
     * Get the first search results without pagination.
     *
     * @param array $columns The columns to retrieve.
     * @return array The search results.
     */
    function first($columns = ['*'])
    {
        $results = ['data' => $this->builder->first($columns)];
        $custom = collect($this->getCustoms());

        $results = $custom->merge($results);

        return $results;
    }

    /**
     * Add additional custom columns to the search results.
     *
     * @param \Illuminate\Pagination\LengthAwarePaginator $results The paginated results.
     * @return array The search results with additional columns.
     */
    function additionalColumns($results)
    {
        $data = $results->items();

        foreach ($data as $item) {
            foreach ($this->addedColumns as $column => $callback) {
                $item->$column = $callback($item);
            }
        }

        return $data;
    }

    function getCustoms()
    {
        $arr = ['sortable' => $this->sortable, 'fillable' => $this->getFillable($this->fillable), 'model_name' => $this->model_name, 'model_name_plural' => Str::plural($this->model_name)];
        return $arr;
    }

    function getFillable(array $fillable)
    {
        $guess_array = [
            'name' => ['input' => 'input', 'type' => 'name'],
            'email' => ['input' => 'input', 'type' => 'email'],
            'priority_no' => ['input' => 'input', 'type' => 'number'],

            'content*' => ['input' => 'textarea', 'type' => null],

            'text' => ['input' => 'input', 'type' => 'url'],

            '*_id'  => ['input' => 'select', 'type' => null],

            'img' => ['input' => 'input', 'type' => 'file'],
            'image' => ['input' => 'input', 'type' => 'file'],
            'avatar' => ['input' => 'input', 'type' => 'file'],

            '*_time' => ['input' => 'input', 'type' => 'datetime-local'],
            'last_*' => ['input' => 'input', 'type' => 'datetime-local'],
            '*_at' => ['input' => 'input', 'type' => 'datetime-local'],

            '*date' => ['input' => 'input', 'type' => 'date'],

            'is_*' => ['input' => 'input', 'type' => 'checkbox'],
        ];

        $guessed = [];

        foreach ($fillable as $field) {
            $matchedType = null;

            foreach ($guess_array as $pattern => $type) {
                if (fnmatch($pattern, $field)) {
                    $matchedType = $type;
                    break;
                }
            }

            if ($matchedType) {
                $guessed[$field]['input'] = $matchedType['input'];
                $guessed[$field]['type'] = $matchedType['type'];
            } else {
                $guessed[$field] = ['input' => 'input', 'type' => 'text'];
            }
        }

        return $guessed;
    }
}
