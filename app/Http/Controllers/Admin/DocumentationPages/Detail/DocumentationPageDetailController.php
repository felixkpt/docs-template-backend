<?php

namespace App\Http\Controllers\Admin\DocumentationPages\Detail;

use App\Http\Controllers\Admin\DocumentationPages\DocumentationPagesController;
use App\Http\Controllers\Controller;
use App\Models\DocumentationPage;
use App\Models\PostStatus;
use App\Repositories\SearchRepo;
use Illuminate\Http\Request;

class DocumentationPageDetailController extends Controller
{
    public function show($id)
    {
        $docs = DocumentationPage::where('id', $id);

        $res = SearchRepo::of($docs, [], [])
            ->addColumn('content', fn ($item) => refreshTemporaryTokensInString($item->content))
            ->statuses(PostStatus::select('id', 'name')->get())->first();

        return response(['results' => $res]);
    }

    function update(Request $request, $id)
    {
        $request->merge(['id' => $id]);
        return app(DocumentationPagesController::class)->store($request);
    }

    public function destroy($id)
    {
        // Delete a docs/doc page
    }
}
