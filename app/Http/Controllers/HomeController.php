<?php

namespace App\Http\Controllers;

use Session;
use Illuminate\Http\Request;
use App\Models\DomainManagementModel;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $clients = DomainManagementModel::where('status', 'active')->orderBy('id', 'desc')->get();
        return view('home', compact('clients'));
    }

    public function gauth(Request $request, string $id=""){

        try{
            $client = new \Google_Client();
            $client->setApplicationName('ICGAnalytics');
            $client->setScopes([
                'https://www.googleapis.com/auth/youtube.readonly',
            ]);
            $client->setAuthConfig('client_secrets.json');
            $client->setAccessType('offline');
            
            if (isset($_GET['state']) && !empty($_GET['state'])) {
                $state = json_decode(base64_decode($_GET['state']));
                $id = $state->clientid;
            }
            
            if(empty($id)){
                $request->session()->flash("error", "Invalid Request. Client not found!");
                return redirect("clients/".$id);
            }else{
                $client_data = DomainManagementModel::find($id);
                $authCode = $client_data->gauthcode;
                
                if (isset($_GET['code']) && !empty($_GET['code'])) {
                    $authCode = $_GET['code'];
                    $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                    
                    
                    $client_data->grefreshtoken = $accessToken['refresh_token'];
                    $client_data->gaccesstoken = $accessToken['access_token'];
                    $client_data->gauthjson = $accessToken;
                    $client_data->gauthcode = $authCode;
                    $client_data->save();
                }else{
                    $client_secret_json = @file_get_contents(base_path().'/client_secrets.json');
                    $client_secret = json_decode($client_secret_json);
                    $client_secret = $client_secret->web;
                    
                    $client_id = $client_secret->client_id;
                    $redirect_uris = $client_secret->redirect_uris[0];
                    
                    $custom_param_str = base64_encode(json_encode(array("clientid" => $id)));

                    $authUrl = 'https://accounts.google.com/o/oauth2/auth?response_type=code&access_type=offline&client_id='.$client_id.'&redirect_uri='.$redirect_uris.'&scope=https://www.googleapis.com/auth/youtube.readonly&approval_prompt=force&state='.$custom_param_str;
                    
                    echo "<script>window.open('".filter_var($authUrl, FILTER_SANITIZE_URL)."', '_self').focus();</script>";
                    die;
                }

                $request->session()->flash("message", "Auth token created successfully!");
                return redirect("view-client/".$id);
            }
        }catch(\PDOException $ex) {
            $request->session()->flash("message", $ex->getMessage());
            return redirect('/clients');
        } catch (\Throwable $ex) {
            $request->session()->flash("message", $ex->getMessage());
            return redirect('/clients');
        }
    }
}
