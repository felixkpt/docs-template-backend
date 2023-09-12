<?php

namespace App\Http\Controllers\Admin\Documentation\Detail;

use App\Http\Controllers\Admin\Documentation\DocumentationController;
use App\Http\Controllers\Controller;
use App\Models\Documentation;
use App\Models\PostStatus;
use App\Repositories\SearchRepo;
use Illuminate\Http\Request;

class DocumentationDetailController extends Controller
{
    public function show($id)
    {
        $docs = Documentation::where('id', $id);

        $res = SearchRepo::of($docs, [], [])
            ->addColumn('content', fn ($item) => refreshTemporaryTokensInString($item->content))
            ->statuses(PostStatus::select('id', 'name')->get())->first();

        return response(['results' => $res]);
    }

    function update(Request $request, $id)
    {
        $request->merge(['id' => $id]);
        return app(DocumentationController::class)->store($request);
    }

    public function destroy($id)
    {
        // Delete a docs/doc page
    }
}
