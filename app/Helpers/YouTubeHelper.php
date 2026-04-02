<?php
namespace App\Helpers;
use Auth;

class YouTubeHelper{

    public static function get_yt_channel_data($request, $client_id, $yt_channel_id, $access_token){
        $youtube_api_key = "AIzaSyAGMY5jnk_riNAmXRmCn65lweEs9v1vtMI";
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://youtube.googleapis.com/youtube/v3/channels?part=snippet%2CcontentDetails%2Cstatistics&id='.$yt_channel_id.'&key='.$youtube_api_key,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$access_token
            ),
        ));

        $response = curl_exec($curl);
        
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl);
        if (isset($error_msg)) {
            $request->session()->flash("message", $error_msg);
            return redirect('/view-client/'.$client_id);
        }
        $response = json_decode($response);
        
        return $response;
    }

    public static function get_yt_data_overall($request, $client_id, $youtube_metrics, $start_date, $end_date, $yt_channel_id, $access_token, $maxresults=10, $sort='views'){

        $youtube_api_key = "AIzaSyAGMY5jnk_riNAmXRmCn65lweEs9v1vtMI";
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://youtubeanalytics.googleapis.com/v2/reports?endDate='.$end_date.'&ids=channel=='.$yt_channel_id.'&metrics='.implode(',', $youtube_metrics).'&maxResults='.$maxresults.'&sort=-'.$sort.'&startDate='.$start_date.'&key='.$youtube_api_key,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$access_token
            ),
        ));

        $response = curl_exec($curl);
        
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl);
        if (isset($error_msg)) {
            $request->session()->flash("message", $error_msg);
            return redirect('/view-client/'.$client_id);
        }
        $response = json_decode($response);
        
        return $response;
    }

    public static function get_yt_top_watched($request, $client_id, $youtube_metrics, $start_date, $end_date, $yt_channel_id, $access_token, $maxresults=10, $sort='views'){

        $youtube_api_key = "AIzaSyAGMY5jnk_riNAmXRmCn65lweEs9v1vtMI";
        

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://youtubeanalytics.googleapis.com/v2/reports?dimensions=video&endDate='.$end_date.'&ids=channel=='.$yt_channel_id.'&maxResults='.$maxresults.'&metrics='.implode(',', $youtube_metrics).'&sort=-'.$sort.'&startDate='.$start_date.'&key='.$youtube_api_key,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$access_token
            ),
        ));

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl);
        if (isset($error_msg)) {
            $request->session()->flash("message", $error_msg);
            return redirect('/view-client/'.$client_id);
        }
        $response = json_decode($response);

        $response_arr = $yt_videos = array(); $yt_videos_str = "";
        if(isset($response->rows) && !empty($response->rows)){
            foreach ($response->rows as $key => $value) {
                $yt_video_id= $value[0];

                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://youtube.googleapis.com/youtube/v3/videos?part=snippet%2CcontentDetails%2Cstatistics&startDate='.$start_date.'&endDate='.$end_date.'&id='.$yt_video_id.'&key='.$youtube_api_key,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_HTTPHEADER => array(
                        'Authorization: Bearer '.$access_token
                    ),
                ));

                $response = curl_exec($curl);
                curl_close($curl);
                $response = json_decode($response);
                $yt_videos[] = array('video_statistics' => $response, 'video_data' => $value);
            }
            return $yt_videos;
        }

        return $response;
    }

    public static function get_yt_content($request, $client_id, $youtube_metrics, $start_date, $end_date, $yt_channel_id, $access_token, $maxresults=10, $sort='views'){
        $youtube_api_key = "AIzaSyAGMY5jnk_riNAmXRmCn65lweEs9v1vtMI";

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://youtubeanalytics.googleapis.com/v2/reports?dimensions=video&endDate='.$end_date.'&ids=channel=='.$yt_channel_id.'&maxResults='.$maxresults.'&metrics='.implode(',', $youtube_metrics).'&sort=-'.$sort.'&startDate='.$start_date.'&key='.$youtube_api_key,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$access_token
            ),
        ));

        $response = curl_exec($curl);
        
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl);
        if (isset($error_msg)) {
            $request->session()->flash("message", $error_msg);
            return redirect('/view-client/'.$client_id);
        }
        $response = json_decode($response);
        // var_dump($response);die;

        $response_arr = $yt_videos = array(); $yt_videos_str = "";

        if(isset($response->rows) && !empty($response->rows)){
            foreach ($response->rows as $key => $value) {
                $yt_video_id= $value[0]; unset($value[0]);

                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://youtube.googleapis.com/youtube/v3/videos?part=snippet%2CcontentDetails%2Cstatistics&startDate='.$start_date.'&endDate='.$end_date.'&id='.$yt_video_id.'&key='.$youtube_api_key,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_HTTPHEADER => array(
                        'Authorization: Bearer '.$access_token
                    ),
                ));

                $response = curl_exec($curl);
                curl_close($curl);
                $response = json_decode($response);
                
                $yt_videos[] = array('video_statistics' => $response, 'video_metrics' => $youtube_metrics, 'video_data' => $value, 'video_total' => self::get_yt_data_overall($request, $client_id, $youtube_metrics, $start_date, $end_date, $yt_channel_id, $access_token, $maxresults, $sort));
            }
            return $yt_videos;
        }

        return $response;
    }

    public static function get_yt_video_by_id($request, $client_id, $youtube_metrics, $start_date, $end_date, $yt_channel_id, $video_id, $access_token, $maxresults=10, $sort='views'){

        $youtube_api_key = "AIzaSyAGMY5jnk_riNAmXRmCn65lweEs9v1vtMI";

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://youtubeanalytics.googleapis.com/v2/reports?dimensions=video&endDate='.$end_date.'&filters=video=='.$video_id.'&ids=channel=='.$yt_channel_id.'&maxResults='.$maxresults.'&metrics='.implode(',', $youtube_metrics).'&sort=-'.$sort.'&startDate='.$start_date.'&key='.$youtube_api_key,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$access_token
            ),
        ));

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl);
        if (isset($error_msg)) {
            $request->session()->flash("message", $error_msg);
            return redirect('/view-client/'.$client_id);
        }
        $video_statistics = json_decode($response);
        
        $response_arr = $yt_videos = array(); $yt_videos_str = "";
        if(isset($video_statistics->rows) && !empty($video_statistics->rows)){

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://youtube.googleapis.com/youtube/v3/videos?part=snippet%2CcontentDetails%2Cstatistics&startDate='.$start_date.'&endDate='.$end_date.'&id='.$video_id.'&key='.$youtube_api_key,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer '.$access_token
                ),
            ));

            $response = curl_exec($curl);
            curl_close($curl);
            $response = json_decode($response);
            
            $yt_videos[] = array('video_data' => $response, 'video_statistics' => $video_statistics);
            
            return $yt_videos;
        }

        return $response;
    }

    public static function get_yt_traffic($request, $client_id, $youtube_metrics, $start_date, $end_date, $yt_channel_id, $access_token, $maxresults=10, $sort='views'){
        $youtube_api_key = "AIzaSyAGMY5jnk_riNAmXRmCn65lweEs9v1vtMI";
        
        $response_arr = $yt_videos = array();
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://youtubeanalytics.googleapis.com/v2/reports?dimensions=insightTrafficSourceType&endDate='.$end_date.'&ids=channel=='.$yt_channel_id.'&maxResults='.$maxresults.'&metrics='.implode(',', $youtube_metrics).'&sort=-'.$sort.'&startDate='.$start_date.'&key='.$youtube_api_key,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$access_token
            ),
        ));

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl);
        if (isset($error_msg)) {
            $request->session()->flash("message", $error_msg);
            return redirect('/view-client/'.$client_id);
        }
        $response = json_decode($response);

        $yt_videos = array('statistics' => $response, 'total' => self::get_yt_data_overall($request, $client_id, $youtube_metrics, $start_date, $end_date, $yt_channel_id, $access_token, $maxresults, $sort));

        return $yt_videos;
    }

    public static function get_yt_geography($request, $client_id, $youtube_metrics, $start_date, $end_date, $yt_channel_id, $access_token, $maxresults=10, $sort='views'){
        $youtube_api_key = "AIzaSyAGMY5jnk_riNAmXRmCn65lweEs9v1vtMI";
        
        $response_arr = $yt_videos = array();
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://youtubeanalytics.googleapis.com/v2/reports?dimensions=country&endDate='.$end_date.'&ids=channel=='.$yt_channel_id.'&metrics='.implode(',', $youtube_metrics).'&sort=-'.$sort.'&startDate='.$start_date.'&key='.$youtube_api_key,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$access_token
            ),
        ));

        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl);
        if (isset($error_msg)) {
            $request->session()->flash("message", $error_msg);
            return redirect('/view-client/'.$client_id);
        }
        $response = json_decode($response);
        
        $yt_videos = array('statistics' => $response, 'total' => self::get_yt_data_overall($request, $client_id, $youtube_metrics, $start_date, $end_date, $yt_channel_id, $access_token, $maxresults, $sort));
        
        return $yt_videos;
    }

    public static function get_yt_viewergender($request, $client_id, $youtube_metrics, $start_date, $end_date, $yt_channel_id, $access_token, $maxresults=10, $sort='viewerPercentage'){
        $sort='viewerPercentage';
        $youtube_api_key = "AIzaSyAGMY5jnk_riNAmXRmCn65lweEs9v1vtMI";
        
        $response_arr = $yt_videos = array();
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://youtubeanalytics.googleapis.com/v2/reports?dimensions=gender&endDate='.$end_date.'&ids=channel=='.$yt_channel_id.'&metrics='.implode(',', $youtube_metrics).'&sort=-'.$sort.'&startDate='.$start_date.'&key='.$youtube_api_key,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$access_token
            ),
        ));

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl);
        if (isset($error_msg)) {
            $request->session()->flash("message", $error_msg);
            return redirect('/view-client/'.$client_id);
        }
        $response = json_decode($response);
        
        $yt_videos = array('statistics' => $response);
        return $yt_videos;
    }

    public static function get_yt_viewerageGroup($request, $client_id, $youtube_metrics, $start_date, $end_date, $yt_channel_id, $access_token, $maxresults=10, $sort='viewerPercentage'){
        $sort='viewerPercentage';
        $youtube_api_key = "AIzaSyAGMY5jnk_riNAmXRmCn65lweEs9v1vtMI";
        
        $response_arr = $yt_videos = array();
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://youtubeanalytics.googleapis.com/v2/reports?dimensions=ageGroup&endDate='.$end_date.'&ids=channel=='.$yt_channel_id.'&metrics='.implode(',', $youtube_metrics).'&sort=-'.$sort.'&startDate='.$start_date.'&key='.$youtube_api_key,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$access_token
            ),
        ));

        $response = curl_exec($curl);
        
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl);
        if (isset($error_msg)) {
            $request->session()->flash("message", $error_msg);
            return redirect('/view-client/'.$client_id);
        }
        $response = json_decode($response);
        
        $yt_videos = array('statistics' => $response);

        return $yt_videos;
    }

    public static function get_yt_playlist($request, $client_id, $youtube_metrics, $start_date, $end_date, $yt_channel_id, $access_token, $maxresults=10, $sort='views'){
        $youtube_api_key = "AIzaSyAGMY5jnk_riNAmXRmCn65lweEs9v1vtMI";
        
        $response_arr = $yt_videos = array();
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://youtubeanalytics.googleapis.com/v2/reports?dimensions=playlist&endDate='.$end_date.'&filters=isCurated==1&ids=channel=='.$yt_channel_id.'&maxResults='.$maxresults.'&metrics='.implode(',', $youtube_metrics).'&sort=-'.$sort.'&startDate='.$start_date.'&key='.$youtube_api_key,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$access_token
            ),
        ));

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl);

        if (isset($error_msg)) {
            $request->session()->flash("message", $error_msg);
            return redirect('/view-client/'.$client_id);
        }
        $response = json_decode($response);

        $yt_videos = array('statistics' => $response, 'total' => self::get_yt_data_overall($request, $client_id, $youtube_metrics, $start_date, $end_date, $yt_channel_id, $access_token, $maxresults, $sort));
        return $yt_videos;
    }

    public static function get_yt_date($request, $client_id, $youtube_metrics, $start_date, $end_date, $yt_channel_id, $access_token, $maxresults=10, $sort='day'){
        $youtube_api_key = "AIzaSyAGMY5jnk_riNAmXRmCn65lweEs9v1vtMI";
        
        $response_arr = $yt_videos = array();
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://youtubeanalytics.googleapis.com/v2/reports?dimensions=day&endDate='.$end_date.'&ids=channel=='.$yt_channel_id.'&metrics='.implode(',', $youtube_metrics).'&sort=-'.$sort.'&startDate='.$start_date.'&key='.$youtube_api_key,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$access_token
            ),
        ));

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl);
        if (isset($error_msg)) {
            $request->session()->flash("message", $error_msg);
            return redirect('/view-client/'.$client_id);
        }
        $response = json_decode($response);
        
        $yt_videos = array('statistics' => $response, 'total' => self::get_yt_data_overall($request, $client_id, $youtube_metrics, $start_date, $end_date, $yt_channel_id, $access_token, $maxresults, $sort));
        return $yt_videos;
    }

    public static function get_yt_topkeywords($request, $client_id, $youtube_metrics, $start_date, $end_date, $yt_channel_id, $access_token, $maxresults=10, $sort='views'){
        $youtube_api_key = "AIzaSyAGMY5jnk_riNAmXRmCn65lweEs9v1vtMI";
        
        $response_arr = $yt_videos = array();
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://youtubeanalytics.googleapis.com/v2/reports?dimensions=insightTrafficSourceDetail&endDate='.$end_date.'&filters=insightTrafficSourceType==YT_SEARCH&ids=channel=='.$yt_channel_id.'&maxResults='.$maxresults.'&metrics='.implode(',', $youtube_metrics).'&sort=-'.$sort.'&startDate='.$start_date.'&key='.$youtube_api_key,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$access_token
            ),
        ));

        $response = curl_exec($curl);
        
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl);
        if (isset($error_msg)) {
            $request->session()->flash("message", $error_msg);
            return redirect('/view-client/'.$client_id);
        }
        $response = json_decode($response);
        
        $yt_videos = array('statistics' => $response, 'total' => self::get_yt_data_overall($request, $client_id, $youtube_metrics, $start_date, $end_date, $yt_channel_id, $access_token, $maxresults, $sort));
        return $yt_videos;
    }

    public static function get_yt_hashtags($request, $client_id, $youtube_metrics, $start_date, $end_date, $yt_channel_id, $access_token, $maxresults=10, $sort='views'){
        $youtube_api_key = "AIzaSyAGMY5jnk_riNAmXRmCn65lweEs9v1vtMI";
        
        $response_arr = $yt_videos = array();
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://youtubeanalytics.googleapis.com/v2/reports?dimensions=insightTrafficSourceDetail&endDate='.$end_date.'&filters=insightTrafficSourceType==HASHTAGS&ids=channel=='.$yt_channel_id.'&maxResults='.$maxresults.'&metrics='.implode(',', $youtube_metrics).'&sort=-'.$sort.'&startDate='.$start_date.'&key='.$youtube_api_key,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$access_token
            ),
        ));

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl);
        if (isset($error_msg)) {
            $request->session()->flash("message", $error_msg);
            return redirect('/view-client/'.$client_id);
        }
        $response = json_decode($response);
        
        $yt_videos = array('statistics' => $response, 'total' => self::get_yt_data_overall($request, $client_id, $youtube_metrics, $start_date, $end_date, $yt_channel_id, $access_token, $maxresults, $sort));
        return $yt_videos;
    }

    public static function get_yt_sharing($request, $client_id, $youtube_metrics, $start_date, $end_date, $yt_channel_id, $access_token, $maxresults=10, $sort='shares'){
        $sort='shares';
        $youtube_api_key = "AIzaSyAGMY5jnk_riNAmXRmCn65lweEs9v1vtMI";
        
        $response_arr = $yt_videos = array();
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://youtubeanalytics.googleapis.com/v2/reports?dimensions=sharingService&endDate='.$end_date.'&ids=channel=='.$yt_channel_id.'&maxResults='.$maxresults.'&metrics='.implode(',', $youtube_metrics).'&sort=-'.$sort.'&startDate='.$start_date.'&key='.$youtube_api_key,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$access_token
            ),
        ));

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl);
        if (isset($error_msg)) {
            $request->session()->flash("message", $error_msg);
            return redirect('/view-client/'.$client_id);
        }
        $response = json_decode($response);

        $yt_videos = array('statistics' => $response, 'total' => self::get_yt_data_overall($request, $client_id, $youtube_metrics, $start_date, $end_date, $yt_channel_id, $access_token, $maxresults, $sort));
        return $yt_videos;
    }

    public static function get_yt_playback_locations($request, $client_id, $youtube_metrics, $start_date, $end_date, $yt_channel_id, $access_token, $maxresults=10, $sort='views'){

        $youtube_api_key = "AIzaSyAGMY5jnk_riNAmXRmCn65lweEs9v1vtMI";

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://youtubeanalytics.googleapis.com/v2/reports?dimensions=insightPlaybackLocationType&endDate='.$end_date.'&ids=channel=='.$yt_channel_id.'&metrics='.implode(',', $youtube_metrics).'&sort=-'.$sort.'&startDate='.$start_date.'&key='.$youtube_api_key,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$access_token
            ),
        ));

        $response = curl_exec($curl);
        
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl);
        if (isset($error_msg)) {
            $request->session()->flash("message", $error_msg);
            return redirect('/view-client/'.$client_id);
        }
        $response = json_decode($response);
       
        return $response;
    }

    /** Video Wise Report */
    public static function get_yt_channel_videos($request, $client_id, $youtube_metrics, $start_date, $end_date, $yt_channel_id, $access_token, $maxresults=10, $sort='views'){
        $youtube_api_key = "AIzaSyAGMY5jnk_riNAmXRmCn65lweEs9v1vtMI";

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://youtubeanalytics.googleapis.com/v2/reports?dimensions=video&endDate='.$end_date.'&ids=channel=='.$yt_channel_id.'&maxResults='.$maxresults.'&metrics='.implode(',', $youtube_metrics).'&sort=-'.$sort.'&startDate='.$start_date.'&key='.$youtube_api_key,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$access_token
            ),
        ));

        $response = curl_exec($curl);
        
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl);
        if (isset($error_msg)) {
            $request->session()->flash("message", $error_msg);
            return redirect('/view-client/'.$client_id);
        }
        $response = json_decode($response);

        $response_arr = $yt_videos = array(); $yt_videos_str = "";
        if(isset($response->rows) && !empty($response->rows)){
            foreach ($response->rows as $key => $value) {
                $yt_video_id= $value[0]; unset($value[0]);

                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://youtube.googleapis.com/youtube/v3/videos?part=snippet%2CcontentDetails%2Cstatistics&startDate='.$start_date.'&endDate='.$end_date.'&id='.$yt_video_id.'&key='.$youtube_api_key,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_HTTPHEADER => array(
                        'Authorization: Bearer '.$access_token
                    ),
                ));

                $response = curl_exec($curl);
                curl_close($curl);
                $response = json_decode($response);
                
                $yt_videos[] = array('video_statistics' => $response, 'video_metrics' => $youtube_metrics, 'video_data' => $value, 'video_total' => self::get_yt_data_overall($request, $client_id, $youtube_metrics, $start_date, $end_date, $yt_channel_id, $access_token, $maxresults, $sort));
            }
            return $yt_videos;
        }

        return $response;
    }

    public static function get_yt_video_search_terms($video_id, $youtube_metrics, $start_date, $end_date, $yt_channel_id, $access_token, $maxresults=10, $sort='views'){
        $youtube_api_key = "AIzaSyAGMY5jnk_riNAmXRmCn65lweEs9v1vtMI";

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://youtubeanalytics.googleapis.com/v2/reports?dimensions=insightTrafficSourceDetail&endDate='.$end_date.'&filters=video=='.$video_id.';insightTrafficSourceType==YT_SEARCH&ids=channel=='.$yt_channel_id.'&maxResults='.$maxresults.'&metrics='.implode(',', $youtube_metrics).'&sort=-'.$sort.'&startDate='.$start_date.'&key='.$youtube_api_key,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$access_token
            ),
        ));

        $response = curl_exec($curl);
        
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl);

        $response = json_decode($response);
        
        return $response;
    }

    public static function get_yt_video_wise_traffic($video_id, $youtube_metrics, $start_date, $end_date, $yt_channel_id, $access_token, $maxresults=10, $sort='views'){
        $sort='views';
        $youtube_api_key = "AIzaSyAGMY5jnk_riNAmXRmCn65lweEs9v1vtMI";

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://youtubeanalytics.googleapis.com/v2/reports?dimensions=insightTrafficSourceType&endDate='.$end_date.'&filters=video=='.$video_id.';&ids=channel=='.$yt_channel_id.'&maxResults='.$maxresults.'&metrics='.implode(',', $youtube_metrics).'&sort=-'.$sort.'&startDate='.$start_date.'&key='.$youtube_api_key,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$access_token
            ),
        ));

        $response = curl_exec($curl);
        
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl);

        $response = json_decode($response);
        
        return $response;
    }

    public static function get_yt_video_wise_demographic($video_id, $youtube_metrics, $start_date, $end_date, $yt_channel_id, $access_token, $maxresults=10, $sort='views'){
        $sort='gender,ageGroup';
        $youtube_api_key = "AIzaSyAGMY5jnk_riNAmXRmCn65lweEs9v1vtMI";

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://youtubeanalytics.googleapis.com/v2/reports?dimensions=ageGroup,gender&endDate='.$end_date.'&filters=video=='.$video_id.';&ids=channel=='.$yt_channel_id.'&maxResults='.$maxresults.'&metrics='.implode(',', $youtube_metrics).'&sort='.$sort.'&startDate='.$start_date.'&key='.$youtube_api_key,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$access_token
            ),
        ));

        $response = curl_exec($curl);
        
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl);

        $response = json_decode($response);
        
        return $response;
    }

    public static function get_yt_video_wise_social_sharing($video_id, $youtube_metrics, $start_date, $end_date, $yt_channel_id, $access_token, $maxresults=10, $sort='views'){
        $sort='shares';
        $youtube_api_key = "AIzaSyAGMY5jnk_riNAmXRmCn65lweEs9v1vtMI";

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://youtubeanalytics.googleapis.com/v2/reports?dimensions=sharingService&endDate='.$end_date.'&filters=video=='.$video_id.';&ids=channel=='.$yt_channel_id.'&metrics='.implode(',', $youtube_metrics).'&sort='.$sort.'&startDate='.$start_date.'&key='.$youtube_api_key,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$access_token
            ),
        ));

        $response = curl_exec($curl);
        
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl);

        $response = json_decode($response);
        
        return $response;
    }

    public static function get_yt_video_wise_organic_audience_retention($video_id, $youtube_metrics, $start_date, $end_date, $yt_channel_id, $access_token, $maxresults=10, $sort='views'){
        $youtube_api_key = "AIzaSyAGMY5jnk_riNAmXRmCn65lweEs9v1vtMI";

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://youtubeanalytics.googleapis.com/v2/reports?dimensions=elapsedVideoTimeRatio&endDate='.$end_date.'&filters=video=='.$video_id.';audienceType==ORGANIC&ids=channel=='.$yt_channel_id.'&metrics='.implode(',', $youtube_metrics).'&startDate='.$start_date.'&key='.$youtube_api_key,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$access_token
            ),
        ));

        $response = curl_exec($curl);
        
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl);

        $response = json_decode($response);
        
        return $response;
    }

    public static function get_ajax_yt_video_id($video_id, $access_token){
        $youtube_api_key = "AIzaSyAGMY5jnk_riNAmXRmCn65lweEs9v1vtMI";

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://youtube.googleapis.com/youtube/v3/videos?part=snippet%2CcontentDetails%2Cstatistics&id='.$video_id.'&key='.$youtube_api_key,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$access_token
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response);
        
        return $response;
    }

    public static function get_yt_search_tag_owner($tag, $yt_channel_id, $access_token, $maxresults=10, $sort='viewCount'){
        $youtube_api_key = "AIzaSyAGMY5jnk_riNAmXRmCn65lweEs9v1vtMI";

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://youtube.googleapis.com/youtube/v3/search?maxResults='.$maxresults.'&key='.$yt_channel_id.'&part=snippet&channelId='.$yt_channel_id.'&order='.$sort.'&q='.urlencode($tag),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$access_token
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        
        $response = json_decode($response);
        return $response;
    }

    public static function get_yt_search_tag_overall($tag, $yt_channel_id, $access_token, $maxresults=10, $sort='viewCount'){
        $youtube_api_key = "AIzaSyAGMY5jnk_riNAmXRmCn65lweEs9v1vtMI";

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://youtube.googleapis.com/youtube/v3/search?part=snippet&maxResults='.$maxresults.'&order='.$sort.'&q='.urlencode($tag).'&key='.$youtube_api_key,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$access_token
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $response = json_decode($response);
        return $response;
    }

    public static function get_ajax_ChannelVideos($yt_channel_id, $access_token, $sort='viewCount', $maxresults=50){
        $youtube_api_key = "AIzaSyAGMY5jnk_riNAmXRmCn65lweEs9v1vtMI";

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://youtube.googleapis.com/youtube/v3/search?part=snippet&channelId='.$yt_channel_id.'&maxResults='.$maxresults.'&order='.$sort.'&type=video&key='.$youtube_api_key,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$access_token
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $response = json_decode($response);
        return $response;
    }

    //Ajax
    public static function ajax_yt_channel_data($yt_channel_id, $access_token){
        $youtube_api_key = "AIzaSyAGMY5jnk_riNAmXRmCn65lweEs9v1vtMI"; $sort='viewCount'; $maxresults=50;
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://youtube.googleapis.com/youtube/v3/search?part=snippet&channelId='.$yt_channel_id.'&maxResults='.$maxresults.'&order='.$sort.'&type=video&key='.$youtube_api_key,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$access_token
            ),
        ));

        $response = curl_exec($curl);
        
        curl_close($curl);
        $response = json_decode($response);
        
        $response_arr = $yt_video_tags = array(); $yt_videos_str = "";
        if(isset($response->items) && !empty($response->items)){
            foreach ($response->items as $key => $value) {
                $tags_arr = array();
                $yt_video_id= $value->id->videoId; 
                
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://youtube.googleapis.com/youtube/v3/videos?part=snippet%2CcontentDetails%2Cstatistics&id='.$yt_video_id.'&key='.$youtube_api_key,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_HTTPHEADER => array(
                        'Authorization: Bearer '.$access_token
                    ),
                ));

                $response = curl_exec($curl);
                curl_close($curl);
                $response = json_decode($response);

                if(isset($response->items[0]->snippet->tags) && !empty($response->items[0]->snippet->tags)){
                    $tags_arr = $response->items[0]->snippet->tags;

                    foreach($tags_arr as $tag){
                        $yt_video_tags[] = $tag;
                    }
                }
            }
            $unique_yt_video_tags = array_unique(array_map("strtolower", $yt_video_tags));
            $response_arr['unique'] = $unique_yt_video_tags;
            $duplicate_yt_video_tags = array_diff($yt_video_tags, $unique_yt_video_tags);
            $response_arr['duplicate'] = $duplicate_yt_video_tags;

            return $response_arr;
        }

        return $response;
    }

    public static function ajax_yt_playlist_data($yt_channel_id, $access_token){
        $youtube_api_key = "AIzaSyAGMY5jnk_riNAmXRmCn65lweEs9v1vtMI"; $sort='viewCount'; $maxresults=50;
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://youtube.googleapis.com/youtube/v3/playlists?part=snippet%2CcontentDetails&channelId='.$yt_channel_id.'&maxResults=1000&key='.$youtube_api_key,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$access_token
            ),
        ));

        $response = curl_exec($curl);
        
        curl_close($curl);
        $response = json_decode($response);
        
        $response_arr = $yt_playlist_videos = $yt_non_playlist_videos = array(); $yt_videos_str = "";
        if(isset($response->items) && !empty($response->items)){
            foreach ($response->items as $key => $value) {
                
                $tags_arr = array();
                $yt_playlist_id= $value->id; 
                
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://youtube.googleapis.com/youtube/v3/playlistItems?part=snippet&part=contentDetails&part=status&playlistId='.$yt_playlist_id.'&key='.$youtube_api_key,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_HTTPHEADER => array(
                        'Authorization: Bearer '.$access_token
                    ),
                ));

                $response = curl_exec($curl);
                curl_close($curl);
                $response = json_decode($response);

                if(isset($response->items) && !empty($response->items)){
                    foreach($response->items as $video){
                        $yt_playlist_videos[] = $video->contentDetails->videoId;
                    }
                }
            }
            
            if(!empty($yt_playlist_videos)){
                $video_curl = curl_init();
                curl_setopt_array($video_curl, array(
                    CURLOPT_URL => 'https://www.googleapis.com/youtube/v3/search?part=snippet,id&channelId='.$yt_channel_id.'&order=date&maxResults=1000&key='.$youtube_api_key,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_HTTPHEADER => array(
                        'Authorization: Bearer '.$access_token
                    ),
                ));

                $video_response = curl_exec($video_curl);
                curl_close($video_curl);
                $video_response = json_decode($video_response);

                if(isset($video_response->items) && !empty($video_response->items)){
                    foreach ($video_response->items as $key => $value) {
                        if(isset($value->id->videoId)){
                            $video_id = $value->id->videoId;
                            // var_dump($value);die;
                            
                            $check = in_array($video_id, $yt_playlist_videos);
                            if(!$check){
                                $yt_non_playlist_videos[] = array('video_id' => $video_id, 'video_title' => $value->snippet->title, 'video_image'=> $value->snippet->thumbnails->default->url);
                            }
                        }
                    }
                    return $yt_non_playlist_videos;
                }
            }
            return $response_arr;
        }

        return $response;
    }

    public static function ajax_yt_views_data($yt_channel_id, $access_token, $start_date, $end_date){
        $youtube_api_key = "AIzaSyAGMY5jnk_riNAmXRmCn65lweEs9v1vtMI"; $sort='viewCount'; $maxresults=50;
        
        //echo 'https://youtubeanalytics.googleapis.com/v2/reports?endDate='.$end_date.'&ids=channel=='.$yt_channel_id.'&metrics=views&startDate='.$start_date.'&key='.$youtube_api_key;die;

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://youtubeanalytics.googleapis.com/v2/reports?endDate='.$end_date.'&ids=channel=='.$yt_channel_id.'&metrics=views&startDate='.$start_date.'&key='.$youtube_api_key,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$access_token
            ),
        ));

        $response = curl_exec($curl);
        
        curl_close($curl);
        $response = json_decode($response);
        
        return $response;
    }

    public static function ajax_yt_last_video($yt_channel_id, $access_token, $start_date, $end_date){
        $youtube_api_key = "AIzaSyAGMY5jnk_riNAmXRmCn65lweEs9v1vtMI"; $sort='viewCount'; $maxresults=1;
        
        //echo 'https://youtubeanalytics.googleapis.com/v2/reports?endDate='.$end_date.'&ids=channel=='.$yt_channel_id.'&metrics=views&startDate='.$start_date.'&key='.$youtube_api_key;die;

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://youtube.googleapis.com/youtube/v3/search?part=snippet&channelId='.$yt_channel_id.'&maxResults='.$maxresults.'&order=date&publishedAfter='.$start_date.'&publishedBefore='.$end_date.'&key='.$youtube_api_key,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$access_token
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response);
        
        return $response;
    }

    public static function ajax_yt_data_overall($yt_channel_id, $access_token, $start_date, $end_date){

        $maxresults=1000; $sort='views';
        $youtube_api_key = "AIzaSyAGMY5jnk_riNAmXRmCn65lweEs9v1vtMI";
        $youtube_metrics = array( 'views', 'estimatedMinutesWatched',  'averageViewDuration', 'annotationClickThroughRate');

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://youtubeanalytics.googleapis.com/v2/reports?endDate='.$end_date.'&ids=channel=='.$yt_channel_id.'&metrics='.implode(',', $youtube_metrics).'&maxResults='.$maxresults.'&sort=-'.$sort.'&startDate='.$start_date.'&key='.$youtube_api_key,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$access_token
            ),
        ));

        $response = curl_exec($curl);

        $response = json_decode($response);
        
        return $response;
    }

    /** Channel Video Comparison */

    public static function get_yt_channel_comparison($request, $client_id, $youtube_metrics, $start_date, $end_date, $compare_by_start_date, $compare_by_end_date, $yt_channel_id, $access_token, $maxresults, $sort){

        $comparison_data = array();

        $compare_from_get_yt_content = self::get_yt_content($request, $client_id, $youtube_metrics, $start_date, $end_date, 
        $yt_channel_id, $access_token, $maxresults=10, $sort);

        $comparison_data['compare_to'] = array('start_date' => $start_date, 'end_date' => $end_date, 'DataArr' => $compare_from_get_yt_content);

        $compare_to_get_yt_content = self::get_yt_content($request, $client_id, $youtube_metrics, $compare_by_start_date, $compare_by_end_date, $yt_channel_id, $access_token, $maxresults=10, $sort);
        $comparison_data['compare_from'] = array('start_date' => $compare_by_start_date, 'end_date' => $compare_by_end_date, 'DataArr' => $compare_to_get_yt_content);
        
        return $comparison_data;

    }

    
}