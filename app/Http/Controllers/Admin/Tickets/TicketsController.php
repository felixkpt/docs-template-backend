<?php

namespace App\Http\Controllers\Admin\Tickets;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Repositories\SearchRepo;
use Illuminate\Http\Request;

class TicketsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $documentation = Ticket::query();

        $res = SearchRepo::of($documentation, ['subject', 'user_id'], ['id', 'subject', 'status', 'user_id'], ['subject', 'status', 'assigned_to_list'])
            ->addColumn('action', function ($item) {
                return '
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="icon icon-list2 font-20"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item navigate" href="/admin/documentation/' . $item->slug . '">View</a></li>
                            <li><a class="dropdown-item prepare-edit" data-id="' . $item->id . '" href="/admin/documentation/' . $item->id . '">Edit</a></li>
                            <li><a class="dropdown-item prepare-status-update" data-id="' . $item->id . '" href="/admin/documentation/' . $item->id . '/status-update">' . ($item->status == 1 ? 'Deactivate' : 'Activate') . '</a></li>
                        </ul>
                    </div>
                    ';
            })
            ->paginate();

        return response(['results' => $res]);
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
