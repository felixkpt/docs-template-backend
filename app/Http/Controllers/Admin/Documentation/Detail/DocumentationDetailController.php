<?php

namespace App\Http\Controllers\Admin\Documentation\Detail;

use App\Http\Controllers\Admin\Documentation\Documentation\DocumentationController;
use App\Http\Controllers\Controller;
use App\Models\Documentation;
use App\Repositories\SearchRepo;
use Illuminate\Http\Request;

class DocumentationDetailController extends Controller
{
    public function show($id)
    {
        $docs = Documentation::where('id', $id);

        $res = SearchRepo::of($docs, [], [])
            ->addColumn('content', fn ($item) => refreshTemporaryTokensInString($item->content))->first();

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
