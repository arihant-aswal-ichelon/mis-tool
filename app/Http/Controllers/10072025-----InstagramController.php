<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DomainManagementModel;
use App\Models\FbSubscription;
use Illuminate\Support\Facades\Validator;
use App\Helpers\GeneralHelper;
use App\Helpers\InstagramHelper;

class InstagramController extends Controller
{
    public function __construct(Request $request)
    {
        $this->fbVersion ='v22.0';
        $this->client_id = $request->id;
        $this->client = DomainManagementModel::where('id', $this->client_id)->first();
    }

    /**
     * Display a listing of the resource.
     */
    public function handle_facebook_auth()
    {
        $client_id = $this->client_id;
        $subscription = FbSubscription::where('client_id', $client_id)->orderBy('id', 'desc')->first();
        return view("analysis.instagram.fbsubscription.index", compact("subscription", "client_id"));
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
    public function store_facebook_auth(Request $request)
    {
        $response = array();
        $validator = Validator::make($request->all(), [
            'page_access_token' => 'required',
            'page_id' => 'required',
            'page_name' => 'required',
            'client_id' => 'required',
        ]);

        if ($validator->fails()) {
            $response = array('status' => false, 'message' => 'Required Params');
            echo json_encode($response);
        }

        $deleteSubscription = FbSubscription::where('client_id', $request->get('client_id'))->delete();

        $fbSubscription = FbSubscription::insert(
            [
                'access_token' => $request->get('page_access_token'),
                'page_id' => $request->get('page_id'),
                'page_name' => $request->get('page_name'),
                'client_id' => $request->get('client_id'),
            ]
        );
        
        if($fbSubscription){
            $response = array('status' => true, 'message' => 'FB Page Subscribed Successfully!');
        }else{
            $response = array('status' => false, 'message' => 'FB Page Subscription Failed!');
        }

        echo json_encode($response);
        die;
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

    public function get_instagram_data_overall(Request $request){
        $data = array();
        $post = $request->all();
        $client_id = $this->client_id;

        try{
            $clientFbSubscription = FbSubscription::where('client_id', $client_id)->first();
            if(empty($clientFbSubscription)){
                return redirect("view-client/".$client_id);
            }else{
                $FbPageId = $clientFbSubscription->page_id;
                $FbPageToken = $clientFbSubscription->access_token;
                $InstagramBusinessAccountArr = InstagramHelper::getInstagramBusinessAccount($this->fbVersion, $FbPageId, $FbPageToken);
                if($InstagramBusinessAccountArr->status){
                    $InstagramBusinessAccountList = $InstagramBusinessAccountArr->message;
                    if(isset($InstagramBusinessAccountList->instagram_business_account)){
                        $instagramAccountArr = $IgBusinessAccountArr = array();
                        
                        foreach($InstagramBusinessAccountList->instagram_business_account as $instagramAccountId){
                            $instagramAccountArr[] = InstagramHelper::getInstagramAccountDetails($this->fbVersion, $FbPageId, $FbPageToken, $instagramAccountId);
                        }

                        foreach($instagramAccountArr as $instagramAccount){
                            if($instagramAccount->status){
                                $IgBusinessAccountArr[] = $instagramAccount->message;
                            }
                        }

                        return view("analysis.instagram.ig-account-list", compact("IgBusinessAccountArr", "client_id"));
                    }else{
                        $request->session()->flash("message", 'Instagram Account Not Found!');
                        return redirect("view-client/".$client_id);
                    }
                }else{
                    $request->session()->flash("message", $InstagramBusinessAccountArr->message);
                    return redirect("view-client/".$client_id);
                }
            }
        }catch(\PDOException $ex) {
            $request->session()->flash("message", $ex->getMessage());
            return redirect("view-client/".$client_id);
        } catch (\Throwable $ex) {
            $request->session()->flash("message", $ex->getMessage());
            return redirect("view-client/".$client_id);
        }
    }

    public function get_instagram_insight(Request $request){
        $section = $error = $from_section = "";
        $data = $InstagramBusinessAccountArr = array();
        $post = $request->all();
        $client_id = $request->id;
        $instagramAccountId = $request->igaccountid;
        $since = date("Y-m-01",strtotime("-3 Months"));
        $until = date('Y-m-d');
        $limit = '1000';

        $clientFbSubscription = FbSubscription::where('client_id', $client_id)->first();
        $FbPageId = $clientFbSubscription->page_id;
        $FbPageToken = $clientFbSubscription->access_token;
        
        if(isset($_GET['fields']) && !empty($_GET['fields'])){
            $fields = $_GET['fields'];
            $fieldArr = json_decode(base64_decode($fields));
            
            $section = $fieldArr->section;
            $from_section = (isset($filter->from_section) && !empty($filter->from_section))?$filter->from_section:"";
        }
        
        switch ($section) {
            case 'content':
                $data['from_section'] = "content";
                $InstagramBusinessAccountArr = InstagramHelper::getInstagramAccountContents($this->fbVersion, $FbPageId, $FbPageToken, $instagramAccountId, $limit, $since, $until);
                if($InstagramBusinessAccountArr->status){
                    $data['igData'] = $InstagramBusinessAccountArr->message;
                }
                return view("analysis.instagram.content", compact("data", "section", "instagramAccountId", "client_id"));
                break;
            case 'hashtagSearch':
                $data['from_section'] = "hashtagSearch";
                
                if(isset($_GET['hashtag']) && !empty($_GET['hashtag'])){
                    $hashtag = $_GET['hashtag'];

                    $InstagramBusinessAccountArr = InstagramHelper::getInstagramHashtagContents($this->fbVersion, $FbPageId, $FbPageToken, $instagramAccountId, $limit, $since, $until, $hashtag);
                    if($InstagramBusinessAccountArr->status){
                        $data['igData'] = (object) $InstagramBusinessAccountArr->message;
                    }
                }
                return view("analysis.instagram.ig-hashtag", compact("data", "section", "instagramAccountId", "client_id"));
                break;
            default:
                $InstagramBusinessAccountArr = InstagramHelper::getInstagramAccountDetails($this->fbVersion, $FbPageId, $FbPageToken, $instagramAccountId);
                if($InstagramBusinessAccountArr->status){
                    $data['igData'] = $InstagramBusinessAccountArr->message;
                }
                return view("analysis.instagram.insights", compact("data", "section", "instagramAccountId", "client_id"));
                break;
        }
    }

    public function get_instagramMedia_insight(Request $request){
        $data = array();
        $post = $request->all();
        $client_id = $request->id;
        $igaccountid = $request->igaccountid;
        $mediaid = $request->mediaid;

        try{
            $clientFbSubscription = FbSubscription::where('client_id', $client_id)->first();
            if(empty($clientFbSubscription)){
                return redirect("view-client/".$client_id);
            }else{
                $FbPageId = $clientFbSubscription->page_id;
                $FbPageToken = $clientFbSubscription->access_token;
                $InstagramBusinessAccountArr = InstagramHelper::getInstagramMediaInsight($this->fbVersion, $FbPageId, $FbPageToken, $igaccountid, $mediaid);
                if($InstagramBusinessAccountArr->status){
                    $InstagramBusinessAccountList = $InstagramBusinessAccountArr->message;
                    return view("analysis.instagram.media-data", compact("InstagramBusinessAccountList", "igaccountid", "client_id"));
                }else{
                    $request->session()->flash("message", $InstagramBusinessAccountArr->message);
                    return redirect("view-client/".$client_id);
                }
            }
        }catch(\PDOException $ex) {
            $request->session()->flash("message", $ex->getMessage());
            return redirect("view-client/".$client_id);
        } catch (\Throwable $ex) {
            $request->session()->flash("message", $ex->getMessage());
            return redirect("view-client/".$client_id);
        }
    }
}
