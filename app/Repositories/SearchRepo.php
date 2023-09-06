<?php

namespace App\Repositories;

use App\Models\Status;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * The SearchRepoV2 class provides functionality for searching and sorting data using Laravel's Eloquent ORM or Query Builder.
 *
 * Created by PhpStorm.
 * User: iankibet
 * Date: 7/12/17
 * Time: 4:09 AM
 *
 * This class was contributed by:
 *   - Ian Kibet (https://gitlab.com/ictianspecialist)
 *   - Felix (https://github.com/felixkpt)
 *
 * Initial update: July 1, 2023.
 * License: MIT
 */
class SearchRepo
{
    protected $builder;

    protected $addedColumns = [];
    protected $sortable = [];
    protected $model_name = '';
    protected $fillable = [];
    protected $statuses = [];
    protected $request_data;

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
        $self->request_data = request()->all();

        $model = null;
        if (method_exists($builder, 'getModel')) {
            $model = $builder->getModel();

            $currentConnection = DB::getDefaultConnection();
            $self->model_name = str_replace('_', ' ', Str::after(class_basename(get_class($model)), $currentConnection . '_'));

            if (!$fillable) {
                $fill = $model->getFillable();
                $fill = array_diff($fill, ['user_id']);
                $self->fillable = $fill;
            }

            $self->statuses = Status::select('id', 'name')->get()->toArray();

            $searchable = $searchable ?: $model->searchable;
        }

        $model_table = $model->getTable();

        $request_data = request()->all();
        $self->request_data = $request_data;


        // Handle searching logic
        $term = $request_data['q'] ?? null;

        $search_field = $request_data['search_field'] ?? null;

        // Define an array to map fields to search strategies
        $searchStrategyArray = [
            'is_fcr' => 'equals',
        ];

        // Determine the search strategy based on the search field
        $strategy = $searchStrategyArray[$search_field] ?? 'like'; // Default strategy is 'like'

        // Special case: Handle fields ending with '_id' for exact matching
        if ($strategy === 'like') {

            // Check if the search term is enclosed in quotation marks
            if (preg_match('/^"(.*)"$/i', $term, $matches) || preg_match('/^\'(.*)\'$/i', $term, $matches)) {
                $term = $matches[1]; // Strip the quotation marks
                $strategy = 'equals';
            }
        }

        if ($search_field) {
            $searchable = [$search_field];
        }

        if (!empty($term) && !empty($searchable)) {

            if ($builder instanceof EloquentBuilder) {

                $builder = $builder->where(function ($q) use ($searchable, $term, $model_table, $strategy) {

                    foreach ($searchable as $column) {
                        Log::info('Searching:', ['term' => $term, 'model_table' => $model_table, 'col' => $column]);

                        if (Str::contains($column, '.')) {

                            [$relation, $column] = explode('.', $column, 2);

                            $relation = Str::camel($relation);

                            // Apply search condition within the relation
                            $q->whereHas($relation, function (EloquentBuilder $query) use ($column, $term, $strategy) {
                                $query->where($column, $strategy === 'like' ? 'like' : '=', $strategy === 'like' ? "%$term%" : "$term");
                            });
                        } else {
                            // Apply search condition on the main table
                            $q->orWhere($model_table . '.' . $column, $strategy === 'like' ? 'like' : '=', $strategy === 'like' ? "%$term%" : "$term");

                            Log::critical("Search results:", ['res' => $q->first()]);
                        }
                    }
                });
            } elseif ($builder instanceof QueryBuilder) {
                foreach ($searchable as $column) {
                    if (Str::contains($column, '.')) {
                        [$relation, $column] = explode('.', $column, 2);

                        $relation = Str::camel($relation);

                        $builder->orWhere(function (QueryBuilder $query) use ($column, $term, $strategy) {
                            $query->where($column, $strategy === 'like' ? 'like' : '=', $strategy === 'like' ? "%$term%" : "$term");
                        });
                    } else {
                        // Apply search condition on the main table
                        $builder->orwhere($model_table . '.' . $column, $strategy === 'like' ? 'like' : '=', $strategy === 'like' ? "%$term%" : "$term");
                    }
                }
            }
        }

        // Sorting logic implementation...
        if (request()->order_by) {
            $orderBy = Str::lower(request()->order_by);

            if (Str::contains($orderBy, '.')) {
                [$relation, $column] = explode('.', $orderBy, 2);

                $possibleRelation = Str::camel($relation);

                if ($model && method_exists($model, $possibleRelation)) {

                    $orderBy = $relation . '_id';
                    if (Schema::hasColumn($model_table, $orderBy) || in_array($orderBy, $sortable)) {
                        $order_method = request()->order_method ?? 'asc';
                        $builder->orderBy($orderBy, $order_method);
                    }
                }
            } elseif ($model && Schema::hasColumn($model_table, $orderBy) || in_array($orderBy, $sortable)) {
                $order_method = request()->order_method ?? 'asc';
                $builder->orderBy($orderBy, $order_method);
            }
        } else {
            $builder->orderBy($model_table . '.id', 'desc');
        }

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

        // Append all request data to the pagination links
        $results->appends($this->request_data);

        // for api consumption remove the following line
        // $pagination = $results->links()->__toString();

        // maintain the following line for api consumption
        $results = $custom->merge($results);

        // for api consumption remove the following line
        // $results['pagination'] = $pagination;

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
        // Retrieve the first result without pagination
        $result = $this->builder->first($columns);

        if ($result) {
            // Loop through added custom columns and add them to the stdClass object
            foreach ($this->addedColumns as $column => $callback) {
                $result->$column = $callback($result);
            }
        }

        $results = ['data' => $result];
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
        $arr = [
            'sortable' => $this->sortable, 'fillable' => $this->getFillable($this->fillable),
            'model_name' => $this->model_name, 'model_name_plural' => Str::plural($this->model_name),
            'statuses' => $this->statuses
        ];
        return $arr;
    }

    function getFillable(array $fillable)
    {
        $guess_array = [
            'name' => ['input' => 'input', 'type' => 'name'],
            'email' => ['input' => 'input', 'type' => 'email'],
            'priority_no' => ['input' => 'input', 'type' => 'number'],

            'content*' => ['input' => 'textarea', 'type' => null],
            'description*' => ['input' => 'textarea', 'type' => null],

            'text' => ['input' => 'input', 'type' => 'url'],

            '*_id'  => ['input' => 'select', 'type' => null],
            '*_ids'  => ['input' => 'multiselect', 'type' => null],
            '*_list'  => ['input' => 'select', 'type' => null],
            '*_multilist'  => ['input' => 'multiselect', 'type' => null],
            'guard_name' => ['input' => 'select', 'type' => null],

            'img' => ['input' => 'input', 'type' => 'file'],
            'image' => ['input' => 'input', 'type' => 'file'],
            'avatar' => ['input' => 'input', 'type' => 'file'],

            '*_time' => ['input' => 'input', 'type' => 'datetime-local'],
            '*_name' => ['input' => 'input', 'type' => 'name'],
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
