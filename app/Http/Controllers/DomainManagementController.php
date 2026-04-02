<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DomainManagementModel;

class DomainManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = DomainManagementModel::orderBy('id', 'desc')->get();
        return view("clients.index", compact("clients"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("clients.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = [];
        $client = new DomainManagementModel;
        $data = $request->all();

        $client->name = $data["name"];
        $client->phone = $data["phone"];
        $client->email = $data["email"];
        $client->yt_channel_id = !empty($data["yt_channel_id"])?$data["yt_channel_id"]:"";
        $client->industry = $data["industry"];
        $client->city = $data["city"];
        $client->zip = $data["zip"];
        $client->status = $data["status"];
        $client->youtube = isset($data["youtube"])?$data["youtube"]:"inactive";
        $client->facebook = isset($data["facebook"])?$data["facebook"]:"inactive";

        if ($client->save()) {
            $request->session()->flash("message", "Client has been added successfully");
            return redirect('/add-client');
        } else {
            $request->session()->flash("error", "Unable to add client. Please try again later");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        unset($_SESSION['lms_client_check']);
        $client_data = DomainManagementModel::where("id", $id)->get();
        return view("clients.show", compact("client_data"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = DomainManagementModel::find($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        
        $data = [];
        $data = $request->all();
        $user = User::find($request->input("id"));
        $user->name = $data["name"];
        $user->lastname = $data["lastname"];
        $user->email = $data["email"];
        $user->department_id = $data["department_id"];
        if (!empty($data["password"])) {
            $user->password = Hash::make($data["password"]);
        }
        $user->ivr_user_id = $data["ivr_user_id"];
        if ($user->save()) {
            $request->session()->flash("message", "User has been updated successfully");
        } else {
            $request->session()->flash("error", "Unable to update user. Please try again later");
        }
         $user->ivr_virtual_number = $data["ivr_virtual_number"];
        return redirect()->route("users");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    { 
        if (!empty($id)) {
            $res = User::where('id',$id)->delete();
            if($res)   {
                $request->session()->flash("message", "User has been deleted successfully");
            } else {
                $request->session()->flash("error", "Unable to delete this user. Please try again later");
            }
        } else {
            $request->session()->flash("error", "You are not authorised to access this location");
        }
        return redirect()->route("users");
    }
}
