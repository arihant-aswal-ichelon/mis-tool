<?php

namespace App\Http\Controllers;
use App\Models\YT_Tags;
use Illuminate\Http\Request;
use App\Models\DomainManagementModel;

class TagsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tags = YT_Tags::select('y_t__tags.tag_name', 'y_t__tags.client_id', 'y_t__tags.status', 'domainmanagement.name')
        ->join('domainmanagement', 'domainmanagement.id', '=', 'y_t__tags.client_id')
        ->get();
        return view("tags.index", compact("tags"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = DomainManagementModel::where('status', 'active')->where('youtube', 'active')->orderBy('id', 'desc')->get();
        return view("tags.create", compact("clients"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = [];
        $client = new YT_Tags;
        $data = $request->all();
        
        $client->tag_name = $data["tag_name"];
        $client->client_id = $data["client_id"];
        $client->status = $data["status"];

        if ($client->save()) {
            $request->session()->flash("message", "Tag has been added successfully");
            return redirect('/add-tag');
        } else {
            $request->session()->flash("error", "Unable to add Tag. Please try again later");
        }
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
