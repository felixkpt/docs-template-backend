<?php

namespace App\Http\Controllers\Admin\DocumentationPages\Categories;

use App\Http\Controllers\Admin\DocumentationPages\Categories\Topics\TopicsController;
use App\Http\Controllers\Controller;
use App\Models\DocumentationCategory;
use App\Models\PostStatus;
use App\Repositories\SearchRepo;
use App\Services\Filerepo\Controllers\FilesController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoriesController extends Controller
{
    public function index()
    {
        $docs = DocumentationCategory::query()
            ->when(isset(request()->id) && request()->id > 0, fn ($q) => $q->where('id', request()->id))
            ->when(isset(request()->parent_category_id), fn ($q) => $q->where('parent_category_id', request()->parent_category_id));

        $res = SearchRepo::of($docs, ['id', 'title', 'image'])
            ->sortable(['id', 'image'])
            ->addColumn('name', fn ($item) => $item->title)
            ->addColumn('action', function ($item) {
                return '
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="icon icon-list2 font-20"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item autotable-navigate" href="/admin/docs/' . $item->slug . '">View</a></li>
                            '
                    .
                    (checkPermission('docs.categories', 'post') ?
                        '<li><a class="dropdown-item autotable-edit" data-id="' . $item->id . '" href="/admin/docs/categories/' . $item->id . '">Edit</a></li>'
                        :
                        '')
                    .
                    '
                            <li><a class="dropdown-item autotable-status-update" data-id="' . $item->id . '" href="/admin/docs/' . $item->slug . '/status-update">' . ($item->status == 1 ? 'Deactivate' : 'Activate') . '</a></li>
                        </ul>
                    </div>
                    ';
            })
            ->paginate();

        return response(['results' => $res]);
    }

    public function create()
    {
        // Show the create docs/doc page form
    }

    public function store(Request $request)
    {

        // Validate the incoming request data
        $validatedData = $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('documentation_categories', 'title')->where(function ($query) use ($request) {
                    return $query->where('parent_category_id', $request->parent_category_id);
                })->ignore($request->id),
            ],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('documentation_categories', 'slug')->ignore($request->id),
            ],
            'description' => 'nullable|string|max:255',
            'image' => 'required|image',
            'parent_category_id' => 'nullable|exists:documentation_categories,id',
            'priority_number' => 'nullable|integer|between:0,99999999',
        ]);

        if ($request->slug) {
            $slug = Str::slug($validatedData['slug']);
        } else {
            // Generate the slug from the title and parent_category slug
            $slug = Str::slug($validatedData['title']);


            if ($request->id) {
                $exists = DocumentationCategory::find($request->id);
                if ($exists) {
                    $request->merge(['parent_category_id' => $exists->parent_category_id]);
                }
            }

            // Check if the generated slug is unique, if not, add a prefix
            $parent_category_id = $request->parent_category_id;
            while ($parent_category_id > 0) {
                $category = DocumentationCategory::find($parent_category_id);
                if ($category) {
                    $slug = $category->slug . '-' . $slug;
                    break;
                } else {
                    break;
                }
            }

            // Check if the generated slug is unique, if not, add a suffix
            $count = 1;
            while (DocumentationCategory::where('slug', $slug)->exists()) {
                $slug = Str::slug($slug) . '-' . Str::random($count);
                $count++;
            }
        }

        // Include the generated slug in the validated data
        $validatedData['slug'] = Str::lower($slug);
        if (!$request->id) {
            $validatedData['user_id'] = auth()->user()->id;
        }

        // Create a new Documentation instance with the validated data
        $validatedData['title'] = Str::title($validatedData['title']);

        $documentation = DocumentationCategory::updateOrCreate(['id' => $request->id], $validatedData);

        if (request()->hasFile('image')) {
            $uploader = new FilesController();
            $image_data = $uploader->saveFiles($documentation, [request()->file('image')]);

            $path = $image_data[0]['path'] ?? null;
            $documentation->image = $path;
            $documentation->save();
        }

        $action = 'created';
        if ($request->id)
            $action = 'updated';
        return response(['type' => 'success', 'message' => 'Documentation category ' . $action . ' successfully', 'results' => $documentation]);
    }

    function update(Request $request, $id)
    {
        $request->merge(['id' => $id]);
        return $this->store($request);
    }

    public function show($slug)
    {
        $docs = DocumentationCategory::where('slug', $slug);

        $res = SearchRepo::of($docs, [], [])
            ->addColumn('name', fn ($item) => $item->name)
            ->statuses(PostStatus::select('id', 'name')->get())->first();

        return response(['results' => $res]);
    }

    public function listCatTopics($slug)
    {
        request()->merge(['slug' => $slug]);
        return app(TopicsController::class)->index();
    }
}
