<?php

namespace App\Http\Controllers\Admin\DocumentationPages\Categories\Topics;

use App\Http\Controllers\Controller;
use App\Models\DocumentationCategory;
use App\Models\DocumentationTopic;
use App\Repositories\SearchRepo;
use App\Services\Filerepo\Controllers\FilesController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TopicsController extends Controller
{
    public function index()
    {
        $docs = DocumentationTopic::query()
            ->when(request()->category_id, function ($q) {
                $q->where('category_id', request()->category_id);
            })
            ->when(request()->slug, function ($q) {
                $cat = DocumentationCategory::whereslug(request()->slug)->first();
                $q->where('category_id', $cat->id);
            });

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
                            <li><a class="dropdown-item autotable-view" href="/admin/docs/categories/' . $item->id . '/topics/detail/' . $item->id . '">View</a></li>
                            '
                    .
                    (checkPermission('docs.categories.topics', 'post') ?
                        '<li><a class="dropdown-item autotable-edit" data-id="' . $item->id . '" href="/admin/docs/categories/' . $item->id . '/topics/detail/' . $item->id . '/edit">Edit</a></li>'
                        :
                        '')
                    .
                    '
                            <li><a class="dropdown-item autotable-status-update" data-id="' . $item->id . '" href="/admin/docs/categories/' . $item->id . '/topics/detail/' . $item->id . '/status-update">' . ($item->status == 1 ? 'Deactivate' : 'Activate') . '</a></li>
                        </ul>
                    </div>
                    ';
            })
            ->paginate();

        return response(['results' => $res]);
    }

    public function create()
    {
        // Show the create docs/categories/'.$item->id.'/topics/doc page form
    }

    public function store(Request $request)
    {

        // Validate the incoming request data
        $validatedData = $request->validate([
            'category_id' => 'required|exists:documentation_categories,id', // Ensure id exists
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
            while (DocumentationTopic::where('slug', $slug)->exists()) {
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

        $documentation = DocumentationTopic::updateOrCreate(['id' => $request->id], $validatedData);

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
}
