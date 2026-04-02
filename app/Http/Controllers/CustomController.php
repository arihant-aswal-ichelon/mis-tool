<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\YT_Tags;

class CustomController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        //
    }

    public static function addtagnameFunc(Request $request){
        
        $client_id = $_POST["client_id"];
        $tag_name = $_POST["tagname"];

        $find_tag = YT_Tags::where('client_id', $client_id)->where('tag_name', $tag_name)->first();
        if(!is_object($find_tag)){
            $tags = new YT_Tags;
            $tags->tag_name = $tag_name;
            $tags->client_id = $client_id;
    
            if ($tags->save()) {
                echo json_encode(array("status" => true, "message" => "Tag has been added successfully!"));
                die;
            } else {
                echo json_encode(array("status" => false, "message" => "Unable to add. Please try again!"));
                die;
            }
        } else {
            echo json_encode(array("status" => false, "message" => "Tag already added!"));
            die;
        }
    }
}
