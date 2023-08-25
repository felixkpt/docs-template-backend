<?php

namespace App\Http\Controllers\Admin\Documentation\Documentation;

use App\Http\Controllers\Admin\Documentation\DocumentationController as DocumentationControllerAll;
use App\Http\Controllers\Controller;
use App\Models\Documentation;
use App\Repositories\SearchRepo;
use Illuminate\Http\Request;

class DocumentationController extends Controller
{
    public function show($id)
    {
        $docs = Documentation::where('id', $id);

        $res = SearchRepo::of($docs, [], [])->first();

        return response(['results' => $res]);
    }

    function update(Request $request, $id)
    {
        $request->merge(['id' => $id]);
        return app(DocumentationControllerAll::class)->store($request);
    }

    public function destroy($id)
    {
        // Delete a docs/doc page
    }
}
