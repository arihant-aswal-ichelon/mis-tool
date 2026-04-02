@extends('layouts.page-app')

@section("content")

<?php use App\Http\Controllers\AnalysisController; 
    $client_id = $data['client_id'];
    $section = $data['section'];
    $start_date = $data['start_date'];
    $end_date = $data['end_date'];
    $access_token = $data['access_token'];
    $tag = $data['tag'];
    $maxresults = $data['maxresults'];
    $yt_channel_id = $data['yt_channel_id'];
    $sort = $data['sort'];
?>
<style>
    table {
        width: 100%;
        border: 1px solid #ddd;
    }

    td {
        padding: 16px;
    }

    tr:nth-child(even) {
        background-color: #eee;
    }
</style>
<div class="page-content">
    <div class="container-fluid">
        
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Tag Wise Reports</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?php echo route('home'); ?>">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo url('/view-client/'.$client_id); ?>">Properties</a></li>
                            <li class="breadcrumb-item active">Tag Wise Reports</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        @include('analysis.youtube.yt-tag-wise-filter')
                    </div>
                    <div class="card-body yt-video-data row">
                        @include('analysis.youtube.yt-common')

                        <?php if(isset($tag) && !empty($tag)){ ?>
                            <div class="col-sm-12">
                                <?php $owner_channel_videos = AnalysisController::tag_wise_owner_vides($tag, $yt_channel_id, $access_token, $maxresults, $sort); ?>
                                <h5>Channel Name: <?php echo isset($owner_channel_videos->items[0]->snippet->channelTitle)?$owner_channel_videos->items[0]->snippet->channelTitle:""; ?></h5>
                                <table class="table table-striped table-nowrap align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col">Action</th>
                                            <th scope="col">Video Title</th>
                                            <th scope="col">Thumbnail</th>
                                            <th scope="col">Published At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php  foreach($owner_channel_videos->items as $video){// echo "<pre/>"; var_dump($video);die; ?>
                                            <tr class="video_tr" id="<?php echo isset($video->id->videoId)?$video->id->videoId:""; ?>">
                                                <td scope="col" ><i onclick="addTagDetailFunc('<?php echo isset($video->id->videoId)?$video->id->videoId:""; ?>', '<?php echo $access_token; ?>
                                                ');" style="font-size: 20px;" class="mdi mdi-information"></i></td>
                                                <td scope="col"><?php echo $video->snippet->title; ?></td>
                                                <td scope="col">
                                                    <a target="_blank" href="https://www.youtube.com/watch?v=<?php echo isset($video->id->videoId)?$video->id->videoId:""; ?>">
                                                        <img src="<?php echo isset($video->snippet->thumbnails->maxres->url)?$video->snippet->thumbnails->maxres->url:$video->snippet->thumbnails->high->url;?>" style="max-width:50px;" />
                                                    </a>
                                                </td>
                                                <td scope="col"><?php echo date('F j, Y, g:i a', strtotime($video->snippet->publishedAt)); ?></td>
                                            </tr>
                                        <?php  }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <hr/>
                            <h5>Other Channel Videos</h5>
                            <div class="col-sm-12">
                                <?php $other_channel_videos = AnalysisController::tag_wise_other_vidoes($tag, $yt_channel_id, $access_token, $maxresults, $sort); ?>
                                <table class="table table-striped table-nowrap align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col">Action</th>
                                            <th scope="col">Channel</th>
                                            <th scope="col">Video Title</th>
                                            <th scope="col">Thumbnail</th>
                                            <th scope="col">Published At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php  foreach($other_channel_videos->items as $video){ //echo "<pre/>"; var_dump($other_channel_videos);die; ?>
                                            <tr class="video_tr <?php if($yt_channel_id == $video->snippet->channelId){echo "activechannel"; }?>" id="<?php echo isset($video->id->videoId)?$video->id->videoId:""; ?>">
                                                <td scope="col" ><i onclick="addTagDetailFunc('<?php echo isset($video->id->videoId)?$video->id->videoId:""; ?>', '<?php echo $access_token; ?>
                                                ');" style="font-size: 20px;" class="mdi mdi-information"></i></td>
                                                <td scope="col"><?php echo $video->snippet->channelTitle; ?></td>
                                                <td scope="col"><?php echo substr_replace($video->snippet->title, "...", 30); ?></td>
                                                <td scope="col">
                                                    <a target="_blank" href="https://www.youtube.com/watch?v=<?php echo isset($video->id->videoId)?$video->id->videoId:""; ?>">
                                                        <img src="<?php echo isset($video->snippet->thumbnails->maxres->url)?$video->snippet->thumbnails->maxres->url:$video->snippet->thumbnails->high->url;?>" style="max-width:50px;" />
                                                    </a>
                                                </td>
                                                <td scope="col"><?php echo date('F j, Y, g:i a', strtotime($video->snippet->publishedAt)); ?></td>
                                            </tr>
                                        <?php  }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php } ?>

                    </div>
                </div>
            </div><!--end col-->
        </div><!--end row-->
    </div>
</div>

<div>

<script type="text/javascript">
    function addTagDetailFunc(video_id, access_token){
        $.ajax({
            url : "{{url('gettagdetails')}}",                
            data : {'video_id' : video_id, 'access_token': access_token},
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
</script>
@endsection