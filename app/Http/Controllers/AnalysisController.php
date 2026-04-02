<?php

namespace App\Http\Controllers;
use PDO;
use Illuminate\Http\Request;
use App\Models\DomainManagementModel;
use App\Helpers\GeneralHelper;
use App\Helpers\YouTubeHelper;
use App\Models\YT_Tags;

class AnalysisController extends Controller
{
    protected $client_id;
    protected $client_url;

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        //
    }

    public function __construct(Request $request)
    {
        $this->client_id = $request->id;
        $this->client_url = $request->url;
        $this->client = DomainManagementModel::where('id', $this->client_id)->first();
    }

    //YouTube Channel Overall
    public function get_youtube_data_overall(Request $request)
    {
        $maxresults = 10;
        $sort = "views";
        $data = array();
        $post = $request->all();
        $client_id = $this->client_id;
        $data['client_id'] = $client_id;
        try{
            $client_data = DomainManagementModel::find($client_id);
            $yt_channel_id = $client_data->yt_channel_id;
            $access_token = $client_data->gaccesstoken;
            $grefreshtoken = $client_data->grefreshtoken;
            $token_status = GeneralHelper::check_gtoken_status($request, $access_token, $client_id);
           
            if(!$token_status){
                //die('23323');
                $access_token = GeneralHelper::generate_gtoken($request, $grefreshtoken, $client_id);
               
                if(!$access_token){
                    return redirect('/gauth/'.$client_id);
                }
                
                DomainManagementModel::where('id', $client_id)->update(['gaccesstoken' => $access_token]);
            }
            
            if(isset($post) && !empty($post) && isset($post['filter_datepicker']) && !empty($post['filter_datepicker'])){
                $filter_datepicker = $post['filter_datepicker'];
                $explode_datepicker = explode('to', $filter_datepicker);
                $start_date = trim($explode_datepicker[0]);
                $end_date = trim($explode_datepicker[1]);
            }else{
                $end_date = date('Y-m-d', strtotime('-3 day', strtotime(date('Y-m-d'))));
                $start_date = date('2015-01-01', strtotime('-7 day', strtotime($end_date)));
            }

            if(isset($post) && !empty($post) && isset($post['record']) && !empty($post['record'])){
                $maxresults = $post['record'];
            }
            
            $youtube_metrics = array( 'views', 'estimatedMinutesWatched',  'averageViewDuration', 'comments', 'likes', 'dislikes', 'shares','subscribersGained', 'subscribersLost' );
            $api_response = YouTubeHelper::get_yt_data_overall($request, $client_id, $youtube_metrics, $start_date, $end_date, $yt_channel_id, $access_token);
            if(isset($api_response->error->code) && !empty($api_response->error->code)){
                $request->session()->flash("message", $api_response->error->message);
                return redirect("view-client/".$client_id);
            }
            $youtube_metrics = array( 'views', 'averageViewDuration');
            $top_watched = YouTubeHelper::get_yt_top_watched($request, $client_id, $youtube_metrics, $start_date, $end_date, $yt_channel_id, $access_token, $maxresults);

            $channel_data = YouTubeHelper::get_yt_channel_data($request, $client_id, $yt_channel_id, $access_token);
            
            $data['channel_data'] = $channel_data;
            $data['youtube_data'] = $api_response;
            $data['yt_top_watched'] = $top_watched;
            $data['start_date'] = $start_date;
            $data['end_date'] = $end_date;
            $data['maxresults'] = $maxresults;
            $data['sort'] = $sort;
            
            return view("analysis.youtube.yt-step-1", compact("data"));
        }catch(\PDOException $ex) {
            $request->session()->flash("message", $ex->getMessage());
            return redirect("view-client/".$client_id);
        } catch (\Throwable $ex) {
            $request->session()->flash("message", $ex->getMessage());
            return redirect("view-client/".$client_id);
        }
    }

    //YouTube Assign Group Videos
    public function get_youtube_assign_group(Request $request)
    {
        $maxresults = 10;
        $sort = "viewCount";
        $data = array();
        $post = $request->all();
        $client_id = $this->client_id;
        $data['client_id'] = $client_id;
        try{
            $client_data = DomainManagementModel::find($client_id);
            $yt_channel_id = $client_data->yt_channel_id;
            $access_token = $client_data->gaccesstoken;
            $grefreshtoken = $client_data->grefreshtoken;
            $token_status = GeneralHelper::check_gtoken_status($request, $access_token, $client_id);
            
            if(!$token_status){
                //die('23323');
                $access_token = GeneralHelper::generate_gtoken($request, $grefreshtoken, $client_id);
                if(!$access_token){
                    return redirect('/gauth/'.$client_id);
                }
                DomainManagementModel::where('id', $client_id)->update(['gaccesstoken' => $access_token]);
            }
            $data['access_token'] = $access_token;

            if(isset($post) && !empty($post) && isset($post['record']) && !empty($post['record'])){
                $maxresults = $post['record'];
            }
            if(isset($post) && !empty($post) && isset($post['sort']) && !empty($post['sort'])){
                $sort = $post['sort'];
            }
            
            $youtube_metrics = $youtube_filters = array('viewCount', 'videoCount', 'title', 'relevance', 'rating', 'date' );
            $data['youtube_filters'] = $youtube_filters;
            $data['youtube_metrics'] = $youtube_metrics;
            $data['sort'] = $sort;

            return view("analysis.youtube.yt-group-assign", compact("data"));
        }catch(\PDOException $ex) {
            $request->session()->flash("message", $ex->getMessage());
            return redirect("view-client/".$client_id);
        } catch (\Throwable $ex) {
            $request->session()->flash("message", $ex->getMessage());
            return redirect("view-client/".$client_id);
        }
    }

    //YT Channel Details
    public function get_youtube_detail_data(Request $request){
        try{
            $data = $tag = array();
            $post = $request->all();
            
            $client_id = $request->id;
            $filter = $request->filter;
            $filter = base64_decode($filter);
            $filter = json_decode($filter);

            // var_dump($filter);die;
            $start_date = $filter->start_date;
            $end_date = $filter->end_date;
            $section = $filter->section;
            $maxresults = $filter->maxresults;
            $sort = $filter->sort;
            $from_section = isset($filter->from_section)?$filter->from_section:"";
            // var_dump($sort);
            // var_dump($section);die;
            if($from_section == "tag_wise"){$sort='views';}

            $client_data = DomainManagementModel::find($client_id);
            $yt_channel_id = $client_data->yt_channel_id;
            $access_token = $client_data->gaccesstoken;
            $grefreshtoken = $client_data->grefreshtoken;
            $token_status = GeneralHelper::check_gtoken_status($request, $access_token, $client_id);
            $start_date = $filter->start_date;
            $end_date = $filter->end_date;
            
            if(!$token_status){
                $access_token = GeneralHelper::generate_gtoken($request, $grefreshtoken, $client_id);
                if(!$access_token){
                    return redirect('/gauth/'.$client_id);
                }
                DomainManagementModel::where('id', $client_id)->update(['gaccesstoken' => $access_token]);
            }

            if(isset($post) && !empty($post) && isset($post['filter_datepicker']) && !empty($post['filter_datepicker'])){
                $filter_datepicker = $post['filter_datepicker'];
                $explode_datepicker = explode('to', $filter_datepicker);
                $start_date = trim($explode_datepicker[0]);
                $end_date = trim($explode_datepicker[1]);
            }

            
            $compare_by_start_date = $compare_by_end_date = "";
            if(isset($post) && !empty($post) && isset($post['filter_by_datepicker']) && !empty($post['filter_by_datepicker'])){
                $filter_datepicker = $post['filter_by_datepicker'];
                $explode_datepicker = explode('to', $filter_datepicker);
                $compare_by_start_date = trim($explode_datepicker[0]);
                $compare_by_end_date = trim($explode_datepicker[1]);
            }
            
            if(isset($post) && !empty($post) && isset($post['record']) && !empty($post['record'])){
                $maxresults = $post['record'];
            }
            if(isset($post) && !empty($post) && isset($post['sort']) && !empty($post['sort'])){
                $sort = $post['sort'];
            }
            if(isset($post) && !empty($post) && isset($post['tag']) && !empty($post['tag'])){
                $tag = $post['tag'];
            }
           
            
            $data['yt_channel_id'] = $yt_channel_id;
            $data['access_token'] = $access_token;
            $data['section'] = $section;
            $data['client_id'] = $client_id;
            $data['start_date'] = $start_date;
            $data['end_date'] = $end_date;
            $data['maxresults'] = isset($maxresults)?$maxresults:10;
            $data['sort'] = $sort;
            // var_dump($from_section);die;
            
            switch ($section) {
                case 'date':
                    $data['from_section'] = "date";
                    $youtube_metrics = array('views', 'estimatedMinutesWatched',  'averageViewDuration', 'comments', 'likes', 'dislikes', 'shares','subscribersGained', 'subscribersLost' );
                    $api_response = YouTubeHelper::get_yt_date($request, $client_id, $youtube_metrics, $start_date, $end_date, $yt_channel_id, $access_token, $maxresults, $sort);
                    if(isset($api_response->error->code) && !empty($api_response->error->code)){
                        $request->session()->flash("message", $api_response->error->message);
                        return redirect("view-client/".$client_id);
                    }
                    $data['youtube_data'] = $api_response;
                    $youtube_filters = array('views', 'estimatedMinutesWatched' );
                    $data['youtube_filters'] = $youtube_filters;
                    $data['youtube_metrics'] = $youtube_metrics;
                    return view("analysis.youtube.yt-video-date", compact("data"));
                    break;
                case 'playlist':
                    $data['from_section'] = "playlist";
                    // var_dump($sort);die;
                    $youtube_metrics = array( 'views', 'viewsPerPlaylistStart', 'estimatedMinutesWatched', 'playlistStarts', 'averageTimeInPlaylist', 'averageViewDuration', 'averageViewPercentage');
                    $api_response = YouTubeHelper::get_yt_playlist($request, $client_id, $youtube_metrics, $start_date, $end_date, $yt_channel_id, $access_token, $maxresults, $sort);
                    
                    if(isset($api_response->error->code) && !empty($api_response->error->code)){
                        $request->session()->flash("message", $api_response->error->message);
                        return redirect("view-client/".$client_id);
                    }
                    
                    $youtube_filters = array('views', 'estimatedMinutesWatched' );
                    $data['youtube_filters'] = $youtube_filters;
                    $data['youtube_data'] = $api_response;
                    $data['youtube_metrics'] = $youtube_metrics;
                    return view("analysis.youtube.yt-video-playlist", compact("data"));
                    break;
                case 'viewagegender':
                    $data['from_section'] = "viewagegender";
                    $youtube_metrics = array('viewerPercentage');
                    $api_response = YouTubeHelper::get_yt_viewerageGroup($request, $client_id, $youtube_metrics, $start_date, $end_date, $yt_channel_id, $access_token, $maxresults, $sort);
                    if(isset($api_response->error->code) && !empty($api_response->error->code)){
                        $request->session()->flash("message", $api_response->error->message);
                        return redirect("view-client/".$client_id);
                    }
                    $youtube_filters = array('views', 'estimatedMinutesWatched' );
                    $data['youtube_filters'] = $youtube_filters;
                    $data['youtube_data'] = $api_response;
                    $data['youtube_metrics'] = $youtube_metrics;
                    return view("analysis.youtube.yt-video-viewagegender", compact("data"));
                    break;
                case 'viewergender':
                    $data['from_section'] = "viewergender";
                    $youtube_metrics = array('viewerPercentage');
                    $api_response = YouTubeHelper::get_yt_viewergender($request, $client_id, $youtube_metrics, $start_date, $end_date, $yt_channel_id, $access_token, $maxresults, $sort);
                    
                    if(isset($api_response->error->code) && !empty($api_response->error->code)){
                        $request->session()->flash("message", $api_response->error->message);
                        return redirect("view-client/".$client_id);
                    }
                    $youtube_filters = array('views', 'estimatedMinutesWatched' );
                    $data['youtube_filters'] = $youtube_filters;
                    $data['youtube_data'] = $api_response;
                    $data['youtube_metrics'] = $youtube_metrics;
                    return view("analysis.youtube.yt-video-viewergender", compact("data"));
                    break;
                case 'geography':
                    $data['from_section'] = "geography";
                    $youtube_metrics = array( 'views', 'estimatedMinutesWatched',  'averageViewDuration', 'comments', 'likes', 'dislikes', 'shares','subscribersGained', 'subscribersLost' );
                    $api_response = YouTubeHelper::get_yt_geography($request, $client_id, $youtube_metrics, $start_date, $end_date, $yt_channel_id, $access_token, $maxresults, $sort);
                    if(isset($api_response->error->code) && !empty($api_response->error->code)){
                        $request->session()->flash("message", $api_response->error->message);
                        return redirect("view-client/".$client_id);
                    }
                    $youtube_filters = array('views', 'estimatedMinutesWatched' );
                    $data['youtube_filters'] = $youtube_filters;
                    $data['youtube_data'] = $api_response;
                    $data['youtube_metrics'] = $youtube_metrics;
                    return view("analysis.youtube.yt-video-geography", compact("data"));
                    break;
                case 'traffic':
                    $data['from_section'] = "traffic";
                    $youtube_metrics = array('views', 'estimatedMinutesWatched',  'averageViewDuration');
                    $api_response = YouTubeHelper::get_yt_traffic($request, $client_id, $youtube_metrics, $start_date, $end_date, $yt_channel_id, $access_token, $maxresults, $sort);
                    if(isset($api_response->error->code) && !empty($api_response->error->code)){
                        $request->session()->flash("message", $api_response->error->message);
                        return redirect("view-client/".$client_id);
                    }
                    $youtube_filters = array('views', 'estimatedMinutesWatched' );
                    $data['youtube_filters'] = $youtube_filters;
                    $data['youtube_data'] = $api_response;
                    $data['youtube_metrics'] = $youtube_metrics;
                    return view("analysis.youtube.yt-video-traffic", compact("data"));
                    break;
                case 'topkeywords':
                    $data['from_section'] = "topkeywords";
                    $youtube_metrics = array('views', 'estimatedMinutesWatched',  'averageViewDuration');
                    $api_response = YouTubeHelper::get_yt_topkeywords($request, $client_id, $youtube_metrics, $start_date, $end_date, $yt_channel_id, $access_token, $maxresults, $sort);
                    if(isset($api_response->error->code) && !empty($api_response->error->code)){
                        $request->session()->flash("message", $api_response->error->message);
                        return redirect("view-client/".$client_id);
                    }
                    $youtube_filters = array('views', 'estimatedMinutesWatched' );
                    $data['youtube_filters'] = $youtube_filters;
                    $data['youtube_data'] = $api_response;
                    $data['youtube_metrics'] = $youtube_metrics;
                    return view("analysis.youtube.yt-top-keywords", compact("data"));
                    break;
                case 'hashtags':
                    $data['from_section'] = "hashtags";
                    $youtube_metrics = array('views', 'estimatedMinutesWatched',  'averageViewDuration');
                    $api_response = YouTubeHelper::get_yt_hashtags($request, $client_id, $youtube_metrics, $start_date, $end_date, $yt_channel_id, $access_token, $maxresults, $sort);
                    if(isset($api_response->error->code) && !empty($api_response->error->code)){
                        $request->session()->flash("message", $api_response->error->message);
                        return redirect("view-client/".$client_id);
                    }
                    $youtube_filters = array('views', 'estimatedMinutesWatched' );
                    $data['youtube_filters'] = $youtube_filters;
                    $data['youtube_data'] = $api_response;
                    $data['youtube_metrics'] = $youtube_metrics;

                    return view("analysis.youtube.yt-top-hashtags", compact("data"));
                    break;
                case 'sharing':
                    $data['from_section'] = "sharing";
                    $youtube_metrics = array('shares');
                    $api_response = YouTubeHelper::get_yt_sharing($request, $client_id, $youtube_metrics, $start_date, $end_date, $yt_channel_id, $access_token, $maxresults, $sort);
                    if(isset($api_response->error->code) && !empty($api_response->error->code)){
                        $request->session()->flash("message", $api_response->error->message);
                        return redirect("view-client/".$client_id);
                    }
                    $youtube_filters = array('shares' );
                    $data['youtube_filters'] = $youtube_filters;
                    $data['youtube_data'] = $api_response;
                    $data['youtube_metrics'] = $youtube_metrics;
                    return view("analysis.youtube.yt-top-sharing", compact("data"));
                    break;
                case 'playback_locations':
                    $data['from_section'] = "playback_locations";
                    $youtube_metrics = array( 'views', 'estimatedMinutesWatched',  'averageViewDuration');
                    $api_response = YouTubeHelper::get_yt_playback_locations($request, $client_id, $youtube_metrics, $start_date, $end_date, $yt_channel_id, $access_token, $maxresults, $sort);
                    if(isset($api_response->error->code) && !empty($api_response->error->code)){
                        $request->session()->flash("message", $api_response->error->message);
                        return redirect("view-client/".$client_id);
                    }
                    $youtube_filters = array('views', 'estimatedMinutesWatched' );
                    $data['youtube_filters'] = $youtube_filters;
                    $data['youtube_data'] = $api_response;
                    $data['youtube_metrics'] = $youtube_metrics;
                    return view("analysis.youtube.yt-playback-locations", compact("data"));
                    break;
                case 'video_wise':
                    $data['from_section'] = "video_wise";
                    $youtube_metrics = array( 'views', 'estimatedMinutesWatched',  'averageViewDuration');
                    $api_response = YouTubeHelper::get_yt_channel_videos($request, $client_id, $youtube_metrics, $start_date, $end_date, $yt_channel_id, $access_token, $maxresults, $sort);
                    if(isset($api_response->error->code) && !empty($api_response->error->code)){
                        $request->session()->flash("message", $api_response->error->message);
                        return redirect("view-client/".$client_id);
                    }
                    $youtube_filters = array('views', 'estimatedMinutesWatched', 'averageViewDuration' );
                    $data['youtube_filters'] = $youtube_filters;
                    $data['youtube_data'] = $api_response;
                    $data['youtube_metrics'] = $youtube_metrics;
                    return view("analysis.youtube.yt-video-wise-report", compact("data"));
                    break;
                case 'tag_wise':
                    $data['from_section'] = "tag_wise";
                    $youtube_filters = array('viewCount', 'videoCount', 'title', 'relevance', 'rating', 'date' );
                    $data['youtube_filters'] = $youtube_filters;
                    $data['tag'] = $tag;
                    $data['youtube_tags'] = YT_Tags::where('client_id', $client_id)->orderBy('id', 'desc')->get();
                    return view("analysis.youtube.yt-tag-wise-report", compact("data"));
                    break;
                case 'flag_kpi':
                    $data['from_section'] = "flag_kpi";
                    $youtube_metrics = array( 'views', 'estimatedMinutesWatched',  'averageViewDuration', 'comments', 'likes', 'dislikes', 'shares','subscribersGained', 'subscribersLost' );
                    $data['youtube_metrics'] = $youtube_metrics;
                    $youtube_filters = array('views', 'estimatedMinutesWatched', 'averageViewDuration' );
                    $data['youtube_filters'] = $youtube_filters;
                    return view("analysis.youtube.yt-video-flag-kpi", compact("data"));
                    break;
                case 'compare_wise':
                    // var_dump($_POST);die;
                    $data['from_section'] = "compare_wise";
                    $youtube_filters = array('views', 'estimatedMinutesWatched' );
                    $data['youtube_filters'] = $youtube_filters;
                    $youtube_metrics = array( 'views', 'estimatedMinutesWatched',  'averageViewDuration', 'comments', 'likes', 'dislikes', 'shares','subscribersGained', 'subscribersLost' );
                    $data['youtube_metrics'] = $youtube_metrics;
                    
                    $data['youtube_data'] = array();
                    if(!empty($compare_by_start_date) && !empty($compare_by_end_date)){
                        $api_response = YouTubeHelper::get_yt_channel_comparison($request, $client_id, $youtube_metrics, $start_date, $end_date, $compare_by_start_date, $compare_by_end_date, $yt_channel_id, $access_token, $maxresults, $sort);
                        if(isset($api_response->error->code) && !empty($api_response->error->code)){
                            $request->session()->flash("message", $api_response->error->message);
                            return redirect("view-client/".$client_id);
                        }
                        $data['youtube_data'] = $api_response;
                    }
                    
                    return view("analysis.youtube.yt-compare-wise-report", compact("data"));
                    break;
                default:
                    $youtube_metrics = array( 'views', 'estimatedMinutesWatched',  'averageViewDuration', 'comments', 'likes', 'dislikes', 'shares','subscribersGained', 'subscribersLost' );
                    $api_response = YouTubeHelper::get_yt_content($request, $client_id, $youtube_metrics, $start_date, $end_date, $yt_channel_id, $access_token, $maxresults, $sort);
                    if(isset($api_response->error->code) && !empty($api_response->error->code)){
                        $request->session()->flash("message", $api_response->error->message);
                        return redirect("view-client/".$client_id);
                    }
                    $youtube_filters = array('views', 'estimatedMinutesWatched' );
                    $data['youtube_filters'] = $youtube_filters;
                    $data['youtube_data'] = $api_response;
                    $data['youtube_metrics'] = $youtube_metrics;
                    return view("analysis.youtube.yt-video-analytics", compact("data"));
                    break;
            }
        }catch(\PDOException $ex) {
            $request->session()->flash("message", $ex->getMessage());
            return redirect("view-client/".$client_id);
        } catch (\Throwable $ex) {
            $request->session()->flash("message", $ex->getMessage());
            return redirect("view-client/".$client_id);
        }
    }

    //Video Details by ID
    public function get_youtube_video_data(Request $request){
        $data = array();
        $post = $request->all();
        $client_id = $request->id;
        $video_id = $request->videoid;
        
        $filter = $request->filter;
        $filter = base64_decode($filter);
        $filter = json_decode($filter);
        
        $start_date = $filter->start_date;
        $end_date = $filter->end_date;
        $section = $filter->section;
        $sort = $filter->sort;
        $maxresults = $filter->maxresults;

        $data['client_id'] = $client_id;
        try{
            $client_data = DomainManagementModel::find($client_id);
            $yt_channel_id = $client_data->yt_channel_id;
            $access_token = $client_data->gaccesstoken;
            $grefreshtoken = $client_data->grefreshtoken;
            $token_status = GeneralHelper::check_gtoken_status($request, $access_token, $client_id);
            
            $data['yt_channel_id'] = $yt_channel_id;

            if(!$token_status){
                $access_token = GeneralHelper::generate_gtoken($request, $grefreshtoken, $client_id);
                if(!$access_token){
                    return redirect('/gauth/'.$client_id);
                }
                DomainManagementModel::where('id', $client_id)->update(['gaccesstoken' => $access_token]);
            }
            $data['access_token'] = $access_token;
            
            if(isset($post) && !empty($post) && isset($post['filter_datepicker']) && !empty($post['filter_datepicker'])){
                $filter_datepicker = $post['filter_datepicker'];
                $explode_datepicker = explode('to', $filter_datepicker);
                $start_date = trim($explode_datepicker[0]);
                $end_date = trim($explode_datepicker[1]);
            }
            
            $youtube_metrics = array( 'views', 'estimatedMinutesWatched',  'averageViewDuration', 'comments', 'likes', 'dislikes', 'shares','subscribersGained', 'subscribersLost' );
            $api_response = YouTubeHelper::get_yt_video_by_id($request, $client_id, $youtube_metrics, $start_date, $end_date, $yt_channel_id, $video_id, $access_token, $maxresults, $sort);
            
            if(isset($api_response->error->code) && !empty($api_response->error->code)){
                $request->session()->flash("message", $api_response->error->message);
                return redirect("view-client/".$client_id);
            }
            
            $data['video_id'] = $video_id;
            $data['youtube_data'] = $api_response;
            $data['start_date'] = $start_date;
            $data['end_date'] = $end_date;
            $data['maxresults'] = $maxresults;
            $data['sort'] = $sort;
            
            return view("analysis.youtube.yt-video-details", compact("data"));
        }catch(\PDOException $ex) {
            $request->session()->flash("message", $ex->getMessage());
            return redirect("view-client/".$client_id);
        } catch (\Throwable $ex) {
            $request->session()->flash("message", $ex->getMessage());
            return redirect("view-client/".$client_id);
        }
    }

    /** Search terms video wise */
    public static function video_wise_search_terms($video_id, $start_date, $end_date, $yt_channel_id, $access_token, $maxresults, $sort){
        $youtube_metrics = array( 'views', 'estimatedMinutesWatched',  'averageViewDuration', 'averageViewPercentage');
        $api_response = YouTubeHelper::get_yt_video_search_terms($video_id, $youtube_metrics, $start_date, $end_date, $yt_channel_id, $access_token, $maxresults, $sort);
        if(isset($api_response->error->code) && !empty($api_response->error->code)){
            $request->session()->flash("message", $api_response->error->message);
            return redirect("view-client/".$client_id);
        }
        return $api_response;
    }

    /** Traffic video wise */
    public static function video_wise_traffic($video_id, $start_date, $end_date, $yt_channel_id, $access_token, $maxresults, $sort){
        $youtube_metrics = array( 'views', 'estimatedMinutesWatched',  'averageViewDuration', 'averageViewPercentage');
        $api_response = YouTubeHelper::get_yt_video_wise_traffic($video_id, $youtube_metrics, $start_date, $end_date, $yt_channel_id, $access_token, $maxresults, $sort);
        if(isset($api_response->error->code) && !empty($api_response->error->code)){
            $request->session()->flash("message", $api_response->error->message);
            return redirect("view-client/".$client_id);
        }
        return $api_response;
    }

    /** Demographic video wise */
    public static function video_wise_demographic($video_id, $start_date, $end_date, $yt_channel_id, $access_token, $maxresults, $sort){
        $youtube_metrics = array( 'viewerPercentage');
        $api_response = YouTubeHelper::get_yt_video_wise_demographic($video_id, $youtube_metrics, $start_date, $end_date, $yt_channel_id, $access_token, $maxresults, $sort);
        if(isset($api_response->error->code) && !empty($api_response->error->code)){
            $request->session()->flash("message", $api_response->error->message);
            return redirect("view-client/".$client_id);
        }
        return $api_response;
    }

    /** Social Sharing video wise */
    public static function video_wise_social_sharing($video_id, $start_date, $end_date, $yt_channel_id, $access_token, $maxresults, $sort){
        $youtube_metrics = array('shares');
        $api_response = YouTubeHelper::get_yt_video_wise_social_sharing($video_id, $youtube_metrics, $start_date, $end_date, $yt_channel_id, $access_token, $maxresults, $sort);
        if(isset($api_response->error->code) && !empty($api_response->error->code)){
            $request->session()->flash("message", $api_response->error->message);
            return redirect("view-client/".$client_id);
        }
        return $api_response;
    }

    /** Organic Audience Retention video wise */
    public static function video_wise_organic_audience_retention($video_id, $start_date, $end_date, $yt_channel_id, $access_token){
        $youtube_metrics = array('audienceWatchRatio', 'relativeRetentionPerformance');
        $api_response = YouTubeHelper::get_yt_video_wise_organic_audience_retention($video_id, $youtube_metrics, $start_date, $end_date, $yt_channel_id, $access_token);
        if(isset($api_response->error->code) && !empty($api_response->error->code)){
            $request->session()->flash("message", $api_response->error->message);
            return redirect("view-client/".$client_id);
        }
        return $api_response;
    }

    /** Tag wise owner video */
    public static function tag_wise_owner_vides($tag, $yt_channel_id, $access_token, $maxresults, $sort){
        $api_response = YouTubeHelper::get_yt_search_tag_owner($tag, $yt_channel_id, $access_token, $maxresults, $sort);
        if(isset($api_response->error->code) && !empty($api_response->error->code)){
            $request->session()->flash("message", $api_response->error->message);
            return redirect("view-client/".$client_id);
        }
        return $api_response;
    }

    /** Tag wise other video */
    public static function tag_wise_other_vidoes($tag, $yt_channel_id, $access_token, $maxresults, $sort){
        $api_response = YouTubeHelper::get_yt_search_tag_overall($tag, $yt_channel_id, $access_token, $maxresults, $sort);
        if(isset($api_response->error->code) && !empty($api_response->error->code)){
            $request->session()->flash("message", $api_response->error->message);
            return redirect("view-client/".$client_id);
        }
        return $api_response;
    }

    /** Ajax Get Video Details */
    public static function gettagdetailsFunc(){
        $video_id = $_POST['video_id'];
        $access_token = $_POST['access_token'];

        $api_response = YouTubeHelper::get_ajax_yt_video_id($video_id, $access_token);
        if(isset($api_response->error->code) && !empty($api_response->error->code)){
            echo json_encode(array("status" => false, "message" => $api_response->error->message));
            die;
        } //echo "<pre/>"; var_dump($api_response->items);die;
        $video_str = "";

        $video_str = "Views: ".$api_response->items[0]->statistics->viewCount."<br/>";
        $video_str .= "Like: ".$api_response->items[0]->statistics->likeCount."<br/>";
        $video_str .= "Dislike: ".$api_response->items[0]->statistics->likeCount."<br/>";

        echo json_encode(array("status" => true, "message" => $video_str));
        die;
    }

    /** Ajax Get Channel Videos Details */
    public static function getChannelVideosFunc(){
        $access_token = $_POST['access_token'];
        $yt_channel_id = $_POST['yt_channel_id'];
        $sort = $_POST['sort'];

        $api_response = YouTubeHelper::get_ajax_ChannelVideos($access_token, $yt_channel_id, $sort);
        if(isset($api_response->error->code) && !empty($api_response->error->code)){
            echo json_encode(array("status" => false, "message" => $api_response->error->message));
            die;
        } //echo "<pre/>"; var_dump($api_response->items);die;
        $video_str = "";

        echo json_encode(array("status" => true, "message" => $video_str));
        die;
    }

    /** Ajax Get Channel Videos Details */
    public static function all_yt_channel_ajaxFunc(){
        $access_token = $_POST['access_token'];
        $yt_channel_id = $_POST['yt_channel_id'];
        $sort = $_POST['sort'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $unique_tags_html = $duplicate_tags_html = "";

        $api_response = YouTubeHelper::ajax_yt_channel_data($yt_channel_id, $access_token);
        if(isset($api_response->error->code) && !empty($api_response->error->code)){
            echo json_encode(array("status" => false, "message" => $api_response->error->message));
            die;
        }

        $count = 1;
        foreach($api_response['unique'] as $tag){
            $unique_tags_html .= '<tr>';
            $unique_tags_html .= '<td>'.$count.'. '.$tag.'</td>';
            $unique_tags_html .= '</tr>';
            $count++;
        }
        $count = 1;
        foreach($api_response['duplicate'] as $tag){
            $duplicate_tags_html .= '<tr>';
            $duplicate_tags_html .= '<td>'.$count.'. '.$tag.'</td>';
            $duplicate_tags_html .= '</tr>';
            $count++;
        }


        echo json_encode(array("status" => true, 
            "unique_tags" => $unique_tags_html, 
            "unique_tags_count"  => count($api_response['unique']),
            "duplicate_tags" => $duplicate_tags_html,
            "duplicate_tags_count"  => count($api_response['duplicate'])
        ));
        die;
    }

    public static function all_yt_kpi_playlist_ajaxFunc(){
        $access_token = $_POST['access_token'];
        $yt_channel_id = $_POST['yt_channel_id'];
        $sort = $_POST['sort'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $non_playlist_videos_html = "";

        $api_response = YouTubeHelper::ajax_yt_playlist_data($yt_channel_id, $access_token);
        if(isset($api_response->error->code) && !empty($api_response->error->code)){
            echo json_encode(array("status" => false, "message" => $api_response->error->message));
            die;
        }

        if(!empty($api_response)){
            $count = 1;
            foreach($api_response as $video){ 
                $non_playlist_videos_html .= '<tr class="video_tr" id="'.$video['video_id'].'">';
                $non_playlist_videos_html .= '<td><i onclick="addTagDetailFunc(`'.$video['video_id'].'`);" style="font-size: 20px;" class="mdi mdi-information"></i> '.$count.' <a target="_blank" href="https://www.youtube.com/watch?v='.$video['video_id'].'"><img src="'.$video['video_image'].'" style="max-width:100px;"/> '.$video['video_title'].'</a></td>';
                $non_playlist_videos_html .= '</tr>';
                $count++;
            }
        }

        echo json_encode(array(
            "status" => true,
            "playlist_videos" => $non_playlist_videos_html
        ));
        die;
    }
    
    public static function all_yt_kpi_views_ajaxFunc(){
        $access_token = $_POST['access_token'];
        $yt_channel_id = $_POST['yt_channel_id'];
        $sort = $_POST['sort'];
        $overall_views_html = "";

        $overall_views_arr = array();
        $today_date = date('Y-m-d');
        $start_date = date('Y-m-d', strtotime($today_date.' -1 day'));
        $start_date = date("Y-m-d", strtotime($start_date." -1 month"));   
        
        for ($i=0; $i < 4; $i++) {
            $end_date = date("Y-m-d", strtotime($start_date." +6 days"));

            $api_response = YouTubeHelper::ajax_yt_views_data($yt_channel_id, $access_token, $start_date, $end_date);
            if(isset($api_response->error->code) && !empty($api_response->error->code)){
                echo json_encode(array("status" => false, "message" => $api_response->error->message));
                die;
            }

            if(isset($api_response->rows) && !empty($api_response->rows)){
                $views = $api_response->rows;
                
                $overall_views_arr[] = array(
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'view_count' => isset($views[0][0])?$views[0][0]:0
                    
                );
            }
            // echo $start_date.' - '.$end_date.'<br/>';
            $end_date = date('Y-m-d', strtotime($end_date.' +1 day'));
            $start_date = $end_date;
        }


        if(!empty($overall_views_arr)){
            $count = 1;
            foreach($overall_views_arr as $view){  //var_dump($view);die;
                $overall_views_html .= '<tr>';
                $overall_views_html .= '<td>'.$view['start_date'].'</td>';
                $overall_views_html .= '<td>'.$view['end_date'].'</td>';
                $overall_views_html .= '<td>'.$view['view_count'].'</td>';
                $overall_views_html .= '</tr>';
                $count++;
            }
        }

        echo json_encode(array(
            "status" => true,
            "overall_views" => $overall_views_html
        ));
        die;
    }

    public static function all_yt_last_video_ajaxFunc(){
        $access_token = $_POST['access_token'];
        $yt_channel_id = $_POST['yt_channel_id'];
        $sort = $_POST['sort'];
        $overall_views_html = "";

        $last_video_arr = array();
        $today = date('Y-m-d');
        $end_date = date('Y-m-d', strtotime($today.' -1 day'));
        $start_date = date('Y-m-d', strtotime($end_date.' -7 days'));

        $end_date = $end_date.'T00:00:00Z';
        $start_date = $start_date.'T00:00:00Z';

        $api_response = YouTubeHelper::ajax_yt_last_video($yt_channel_id, $access_token, $start_date, $end_date);
        if(isset($api_response->error->code) && !empty($api_response->error->code)){
            echo json_encode(array("status" => false, "message" => $api_response->error->message));
            die;
        }

        $total_video_count = $api_response->pageInfo->totalResults;

        echo json_encode(array("status" => true, "message" => 'total video published', 'last_videos_count' => $total_video_count));
        die;
    }

    public static function all_yt_benchmark_ajaxFunc(){
        $access_token = $_POST['access_token'];
        $yt_channel_id = $_POST['yt_channel_id'];
        $sort = $_POST['sort'];
        $overall_views_html = "";

        $last_video_arr = array();
        $today = date('Y-m-d');
        $end_date = date('Y-m-d', strtotime($today.' -1 day'));
        $start_date = date('Y-m-d', strtotime($end_date.' -7 days'));

        $api_response = YouTubeHelper::ajax_yt_data_overall($yt_channel_id, $access_token, $start_date, $end_date);
        if(isset($api_response->error->code) && !empty($api_response->error->code)){
            echo json_encode(array("status" => false, "message" => $api_response->error->message));
            die;
        }

        if(isset($api_response->rows) && !empty($api_response->rows)){
            $min_metric = array('estimatedMinutesWatched', 'averageViewDuration');
            $columnHeaders = $api_response->columnHeaders;
            $rows = $api_response->rows;

            foreach($columnHeaders as $key => $metric){
                $overall_views_html .= '<tr>';
                $overall_views_html .= '<td>'.ucfirst($metric->name);
                if(in_array($metric->name, $min_metric)){ $overall_views_html .= " (hours)"; }
                $overall_views_html .= '</td>';
                if(in_array($metric->name, $min_metric)){
                    $overall_views_html .= '<td>'. round(($rows[0][$key] / 60), 2).'</td>';
                }else{$overall_views_html .= '<td>'. $rows[0][$key].'</td>'; }
                $overall_views_html .= '</tr>';
            }
        }

        echo json_encode(array("status" => true, "message" => 'count', 'benchmark_count' => $overall_views_html));
        die;
    }
}
