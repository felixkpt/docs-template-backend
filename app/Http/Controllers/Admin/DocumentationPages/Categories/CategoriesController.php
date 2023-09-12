<?php

namespace App\Http\Controllers\Admin\DocumentationPages\Categories;

use App\Http\Controllers\Admin\DocumentationPages\Categories\Topics\TopicsController;
use App\Http\Controllers\Controller;
use App\Models\DocumentationCategory;
use App\Models\DocumentationTopic;
use App\Models\PostStatus;
use App\Repositories\SearchRepo;
use App\Services\Filerepo\Controllers\FilesController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoriesController extends Controller
{
    public function index()
    {
        $docs = DocumentationCategory::query();

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
                            <li><a class="dropdown-item autotable-navigate" href="/admin/docs/categories/' . $item->slug . '">View</a></li>
                            '
                    .
                    (checkPermission('docs.categories', 'post') ?
                        '<li><a class="dropdown-item autotable-edit" data-id="' . $item->id . '" href="/admin/docs/categories/' . $item->slug . '/edit">Edit</a></li>'
                        :
                        '')
                    .
                    '
                            <li><a class="dropdown-item autotable-status-update" data-id="' . $item->id . '" href="/admin/docs/categories/' . $item->slug . '/status-update">' . ($item->status == 1 ? 'Deactivate' : 'Activate') . '</a></li>
                        </ul>
                    </div>
                    ';
            })
            ->paginate();

        return response(['results' => $res]);
    }

    public function create()
    {
        // Show the create docs/categories/doc page form
    }

    public function store(Request $request)
    {

        // Validate the incoming request data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255|unique:documentation_topics,title,' . $request->id . ',id', // Ensure title is unique
            'slug' => 'nullable|string|max:255|unique:documentation_topics,slug,' . $request->id . ',id', // Ensure slug is unique
            'image' => 'required|image',
        ]);

        if ($request->slug) {
            $slug = Str::slug($validatedData['slug']);
        } else {
            // Generate the slug from the title
            $slug = Str::slug($validatedData['title']);
        }

        if (!$request->id) {

            // Check if the generated slug is unique, if not, add a suffix
            $count = 1;
            while (DocumentationCategory::where('slug', $slug)->exists()) {
                $slug = Str::slug($slug) . '-' . $count;
                $count++;
            }
        }

        // Include the generated slug in the validated data
        $validatedData['slug'] = $slug;
        if (!$request->id) {
            $validatedData['user_id'] = auth()->user()->id;
        }

        // Create a new Documentation instance with the validated data

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
        return response(['type' => 'success', 'message' => 'Documentation topic ' . $action . ' successfully', 'results' => $documentation]);
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
