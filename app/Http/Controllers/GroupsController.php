<?php

namespace App\Http\Controllers;
use App\Models\GroupModel;
use Illuminate\Http\Request;
use App\Models\DomainManagementModel;

class GroupsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $groups = GroupModel::select('*')->get();
        return view("groups.index", compact("groups"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("groups.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = [];
        $group = new GroupModel;
        $data = $request->all();
        
        $group->name = $data["name"];
        $group->type = $data["type"];
        $group->status = $data["status"];

        if ($group->save()) {
            $request->session()->flash("message", "Group has been added successfully");
            return redirect('/add-group');
        } else {
            $request->session()->flash("error", "Unable to add Group. Please try again later");
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

    public function assign(Request $request){
        die('3232434');
    }
}
