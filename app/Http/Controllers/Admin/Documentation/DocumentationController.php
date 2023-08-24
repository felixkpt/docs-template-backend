<?php

namespace App\Http\Controllers\Admin\Documentation;

use App\Http\Controllers\Controller;
use App\Models\Documentation;
use App\Repositories\SearchRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DocumentationController extends Controller
{
    public function index()
    {
        $documentation = Documentation::query();

        $res = SearchRepo::of($documentation, ['title', 'content_short'], ['id', 'title', 'status', 'user_id'], ['title', 'content_short', 'content', 'image', 'status'])
            ->addColumn('action', function ($item) {
                return '
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="icon icon-list2 font-20"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item autotable-navigate" href="/admin/documentation/documentation/' . $item->slug . '">View</a></li>
                            <li><a class="dropdown-item autotable-edit" data-id="' . $item->id . '" href="/admin/documentation/documentation/' . $item->id . '">Edit</a></li>
                            <li><a class="dropdown-item autotable-status-update" data-id="' . $item->id . '" href="/admin/documentation/documentation/' . $item->id . '/status-update">' . ($item->status == 1 ? 'Deactivate' : 'Activate') . '</a></li>
                        </ul>
                    </div>
                    ';
            })
            ->paginate();

        return response(['results' => $res]);
    }

    public function create()
    {
        // Show the create documentation page form
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255|unique:documentations,title,' . $request->id . ',id', // Ensure title is unique
            'content_short' => 'required|string',
            'content' => 'required|string',
            'image' => 'nullable|string',
            'status' => 'required|string',
        ]);

        // Generate the slug from the title
        $slug = Str::slug($validatedData['title']); // Using Laravel's Str::slug() function

        // Check if the generated slug is unique, if not, add a suffix
        $count = 1;
        while (Documentation::where('slug', $slug)->exists()) {
            $slug = Str::slug($validatedData['title']) . '-' . $count;
            $count++;
        }

        // Include the generated slug in the validated data
        $validatedData['slug'] = $slug;
        if (!$request->id) {
            $validatedData['user_id'] = auth()->user()->id;
        }

        // Create a new Documentation instance with the validated data

        $res = Documentation::updateOrCreate(['id' => $request->id], $validatedData);

        $action = 'created';
        if ($request->id)
            $action = 'updated';
        return response(['type' => 'success', 'message' => 'Documentation page ' . $action . ' successfully', 'results' => $res]);
    }

}
