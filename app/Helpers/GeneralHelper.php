<?php
namespace App\Helpers;
use Auth;

class GeneralHelper{

    public static function check_gtoken_status($request, $access_token, $client_id) {
        try {
            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://www.googleapis.com/oauth2/v1/tokeninfo?access_token='.$access_token,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            ));

            $response = curl_exec($curl);
            curl_close($curl);

            $response = json_decode($response);
            $error = isset($response->error)?$response->error:"";

            if(!empty($error) && $error == "invalid_token"){
                return false;
            }else{
                return true;
            }
        } catch (\Throwable $ex) {
            $request->session()->flash("message", $ex->getMessage());
            return redirect('/view-client/'.$client_id);
        }
    }

    public static function generate_gtoken($request, $refresh_token, $lms_client_id) {
        try {
            $client_secret_json = @file_get_contents(base_path().'/client_secrets.json');
            $client_secret = json_decode($client_secret_json);
            $client_secret = $client_secret->web;
            $client_id = $client_secret->client_id;
            $client_secret_token = $client_secret->client_secret;

            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://oauth2.googleapis.com/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'client_id' => $client_id,
                'client_secret' => $client_secret_token,
                'refresh_token' => $refresh_token,
                'grant_type' => 'refresh_token'),
            ));

            $response = curl_exec($curl);
            
            if (curl_errno($curl)) {
                $error_msg = curl_error($curl);
            }
            curl_close($curl);
            if (isset($error_msg)) {
                $request->session()->flash("message", $error_msg);
                return redirect('/view-client/'.$lms_client_id);
            }

            $response = json_decode($response);

            $error = isset($response->error)?$response->error:"";
            if(!empty($error) && $error == "invalid_grant"){
                return false;
            }
            $access_token = $response->access_token;
            
            return $access_token;
        } catch (\Throwable $ex) {
            $request->session()->flash("message", $ex->getMessage());
            return redirect('/view-client/'.$lms_client_id);
        }
    }

}