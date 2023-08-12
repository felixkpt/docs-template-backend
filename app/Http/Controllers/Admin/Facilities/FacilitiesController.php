<?php

namespace App\Http\Controllers\Admin\Facilities;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Repositories\SearchRepo;
use Illuminate\Http\Request;

class FacilitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $facilities = Facility::query();

        if (request()->all == '1') return response(['results' => $facilities->take(10)->get()]);

        return response(['results' => SearchRepo::of($facilities)->paginate()]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
