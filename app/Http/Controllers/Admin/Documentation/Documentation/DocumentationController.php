<?php

namespace App\Http\Controllers\Admin\Documentation\Documentation;

use App\Http\Controllers\Controller;
use App\Models\Documentation;
use App\Repositories\SearchRepo;
use Illuminate\Http\Request;

class DocumentationController extends Controller
{
    public function show($slug)
    {
        $documentation = Documentation::where('slug', $slug);

        $res = SearchRepo::of($documentation, [], [])->first();

        return response(['results' => $res]);
    }

    function update(Request $request, $id)
    {
        $request->merge(['id' => $id]);
        return $this->store($request);
    }

    public function destroy($id)
    {
        // Delete a documentation page
    }
}
