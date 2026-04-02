@extends('layouts.page-app')

@section("content")

<?php
    $client_id = $data['client_id'];
    $start_date = $data['start_date'];
    $end_date = $data['end_date'];
    $section = $data['section'];
    $yt_channel_id = $data['yt_channel_id'];
    $access_token = $data['access_token'];
    $sort = $data['sort'];  
?>

<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Flag KPI</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?php echo route('home'); ?>">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo url('/view-client/'.$client_id); ?>">Properties</a></li>
                            <li class="breadcrumb-item active">Flag KPI Reports</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header align-items-center d-flex"></div>
                    <div class="card-body yt-video-data row">
                        @include('analysis.youtube.yt-common')
                        <div class="col-sm-6">
                            <h5>Videos Unique Tags <strong id="unique_tags_count"></strong></h5>
                            <div class="table-responsive" style="max-height:300px; overflow:auto;">
                                <table class="table table-striped table-nowrap align-middle mb-0">
                                    <tbody id="unique_tags_html"></tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <h5>Videos Common Tags <strong id="duplicate_tags_count"></strong></h5>
                            <div class="table-responsive" style="max-height:300px; overflow:auto;">
                                <table class="table table-striped table-nowrap align-middle mb-0">
                                    <tbody id="duplicate_tags_html"></tbody>
                                </table>
                            </div>
                        </div>
                        
                        <hr/>
                        
                        <div class="col-sm-12">
                            <h5>Non Playlist Videos</h5>
                            <div class="table-responsive" style="max-height:300px; overflow:auto;">
                                <table class="table table-striped table-nowrap align-middle mb-0">
                                    <tbody id="non_playlist_html"></tbody>
                                </table>
                            </div>
                        </div>

                        <hr/>
                        
                        <div class="col-sm-12">
                            <h5>Week Wise Overall Views</h5>
                            <div class="table-responsive" style="max-height:300px; overflow:auto;">
                                <table class="table table-striped table-nowrap align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col">Start Date</th>
                                            <th scope="col">End Date</th>
                                            <th scope="col">Views</th>
                                        </tr>
                                    </thead>
                                    <tbody id="overall_views_html"></tbody>
                                </table>
                            </div>
                        </div>

                        <hr/>

                        <div class="col-lg-4 col-md-6">
                            <div class="mb-3">
                                <div class="card card-animate mb-0" id="last_video_date_box">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <p class="fw-medium text-muted mb-0">Weekly Video Published Count</p>
                                                <h2 class="mt-4 ff-secondary fw-semibold" id="last_video_date"></h2>
                                            </div>
                                            <div>
                                                <div class="avatar-sm flex-shrink-0">
                                                    <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-activity text-info"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr/>
                        
                        <div class="col-sm-12">
                            <h5>Channel BenchMark Count In Last 7 Days</h5>
                            <div class="table-responsive" style="max-height:300px; overflow:auto;">
                                <table class="table table-striped table-nowrap align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col">Parameter</th>
                                            <th scope="col">Counts</th>
                                        </tr>
                                    </thead>
                                    <tbody id="overall_benchmark_html"></tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div><!--end col-->
        </div><!--end row-->
    </div>
</div>

<script type="text/javascript">

    function addTagDetailFunc(video_id){
        $.ajax({
            url : "{{url('gettagdetails')}}",                
            data : {'video_id' : video_id, 'access_token': '<?php echo $access_token; ?>'},
            type : 'post',
            dataType : 'json',
            success : function(result){
                $('.invalid-tooltip, .valid-tooltip').remove();
                if(result.status == false){
                    $("tr.video_tr#"+video_id).append('<span class="invalid-tooltip" style="display:block;">'+result.message+'</span>');
                }else if(result.status == true){
                    $("tr.video_tr#"+video_id).append('<span class="valid-tooltip" style="display:block;">'+result.message+'</span>');
                }else{
                    $("tr.video_tr#"+video_id).append('<span class="invalid-tooltip" style="display:block;">'+result.message+'</span>');
                }
            }
        });
    }

    $(document).ready(function(){    
        setInterval(all_yt_channel_Func(), 1000); 
        setInterval(all_yt_playlist_Func(), 2000);
        setInterval(all_yt_views_Func(), 3000);
        setInterval(all_yt_last_video_Func(), 4000);
        setInterval(all_yt_benchmark_Func(), 5000);
    });

    function all_yt_channel_Func(){
        $("tbody#unique_tags_html, tbody#duplicate_tags_html").empty().append('<tr><td><img src="<?php echo url('assets/images/ajax-spinner.gif'); ?>" style="max-width:30px;" /><td></tr>');
        
        $.ajax({
            url : "{{url('all_yt_channel_ajax')}}",                
            data : {'yt_channel_id':'<?php echo $yt_channel_id; ?>', 'access_token': '<?php echo $access_token; ?>', 'sort':'<?php echo $sort; ?>', 'start_date':'<?php echo $start_date; ?>', 'end_date':'<?php echo $end_date; ?>'},
            type : 'post',
            dataType : 'json',
            success : function(result){
                $("#unique_tags_html").empty().append(result.unique_tags);
                $("#unique_tags_count").empty().append('('+result.unique_tags_count+')');

                $("#duplicate_tags_html").empty().append(result.duplicate_tags);
                $("#duplicate_tags_count").empty().append('('+result.duplicate_tags_count+')');
            }
        });
    }

    function all_yt_playlist_Func(){
        $("tbody#non_playlist_html").empty().append('<tr><td><img src="<?php echo url('assets/images/ajax-spinner.gif'); ?>" style="max-width:30px;" /><td></tr>');
        
        $.ajax({
            url : "{{url('all_yt_kpi_playlist_ajax')}}",                
            data : {'yt_channel_id':'<?php echo $yt_channel_id; ?>', 'access_token': '<?php echo $access_token; ?>', 'sort':'<?php echo $sort; ?>', 'start_date':'<?php echo $start_date; ?>', 'end_date':'<?php echo $end_date; ?>'},
            type : 'post',
            dataType : 'json',
            success : function(result){
                $("#non_playlist_html").empty().append(result.playlist_videos);
            }
        });
    }

    function all_yt_views_Func(){
        $("tbody#overall_views_html").empty().append('<tr><td><img src="<?php echo url('assets/images/ajax-spinner.gif'); ?>" style="max-width:30px;" /><td></tr>');
        
        $.ajax({
            url : "{{url('all_yt_kpi_views_ajax')}}",                
            data : {'yt_channel_id':'<?php echo $yt_channel_id; ?>', 'access_token': '<?php echo $access_token; ?>', 'sort':'<?php echo $sort; ?>', 'start_date':'<?php echo $start_date; ?>', 'end_date':'<?php echo $end_date; ?>'},
            type : 'post',
            dataType : 'json',
            success : function(result){
                $("#overall_views_html").empty().append(result.overall_views);
            }
        });
    }

    function all_yt_last_video_Func(){
        $("#last_video_date_box").removeClass('error');
        $("#last_video_date").empty().append('<tr><td><img src="<?php echo url('assets/images/ajax-spinner.gif'); ?>" style="max-width:30px;" /><td></tr>');
        
        $.ajax({
            url : "{{url('all_yt_last_video_ajax')}}",                
            data : {'yt_channel_id':'<?php echo $yt_channel_id; ?>', 'access_token': '<?php echo $access_token; ?>', 'sort':'<?php echo $sort; ?>', 'start_date':'<?php echo $start_date; ?>', 'end_date':'<?php echo $end_date; ?>'},
            type : 'post',
            dataType : 'json',
            success : function(result){
                if(result.last_videos_count == 0){
                    $("#last_video_date_box").addClass('error');
                }
                $("#last_video_date").empty().append(result.last_videos_count);
            }
        });
    }

    function all_yt_benchmark_Func(){
        $("tbody#overall_benchmark_html").empty().append('<tr><td><img src="<?php echo url('assets/images/ajax-spinner.gif'); ?>" style="max-width:30px;" /><td></tr>');
        
        $.ajax({
            url : "{{url('all_yt_benchmark_ajax')}}",                
            data : {'yt_channel_id':'<?php echo $yt_channel_id; ?>', 'access_token': '<?php echo $access_token; ?>', 'sort':'<?php echo $sort; ?>', 'start_date':'<?php echo $start_date; ?>', 'end_date':'<?php echo $end_date; ?>'},
            type : 'post',
            dataType : 'json',
            success : function(result){
                $("tbody#overall_benchmark_html").empty().append(result.benchmark_count);
            }
        });
    }

</script>
@endsection