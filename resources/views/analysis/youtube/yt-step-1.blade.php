@extends('layouts.page-app')

@section("content")

<?php 
    $channel_data = $data['channel_data'];
    $youtube_data = $data['youtube_data'];
    $client_id = $data['client_id'];
    $start_date = $data['start_date'];
    $end_date = $data['end_date'];
    $yt_top_watched = $data['yt_top_watched'];
    // var_dump($channel_data);die;
?>
<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Channel analytics</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?php echo route('home'); ?>">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo url('/view-client/'.$client_id); ?>">Properties</a></li>
                            <li class="breadcrumb-item active">Analysis</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body row">
                        <?php if(!empty($channel_data)){ //echo "<pre/>"; var_dump($channel_data);die; ?>
                            <h4>Channel Title: <?php echo $channel_data->items[0]->snippet->title; ?></h4>
                            <div class="col-lg-4 col-md-6">
                                <div class="mb-3">
                                    <div class="card card-animate mb-0">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <p class="fw-medium text-muted mb-0">Total Views</p>
                                                    <h2 class="mt-4 ff-secondary fw-semibold"><?php echo $channel_data->items[0]->statistics->viewCount; ?></h2>
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

                            <div class="col-lg-4 col-md-6">
                                <div class="mb-3">
                                    <div class="card card-animate mb-0">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <p class="fw-medium text-muted mb-0">Total Subscribers</p>
                                                    <h2 class="mt-4 ff-secondary fw-semibold"><?php echo $channel_data->items[0]->statistics->subscriberCount; ?></h2>
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

                            <div class="col-lg-4 col-md-6">
                                <div class="mb-3">
                                    <div class="card card-animate mb-0">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <p class="fw-medium text-muted mb-0">Total Videos</p>
                                                    <h2 class="mt-4 ff-secondary fw-semibold"><?php echo $channel_data->items[0]->statistics->videoCount; ?></h2>
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

                        <?php } ?>
                        <hr/>
                        <div class="card-header align-items-center d-flex">
                            @include('analysis.youtube.yt-overall-filter')
                        </div>
                        <?php if(!empty($youtube_data)){ 
                                $min_metric = array('estimatedMinutesWatched', 'averageViewDuration');
                                $columnHeaders = $youtube_data->columnHeaders;
                                $rows = $youtube_data->rows;    
                            ?>
                            <?php foreach($columnHeaders as $key => $metric){ //echo "<pre/>"; var_dump($rows[0][$key]);die; ?>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="mb-3">
                                            <div class="card card-animate mb-0">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between">
                                                        <div>
                                                            <p class="fw-medium text-muted mb-0"><?php echo ucfirst($metric->name); ?> <?php if(in_array($metric->name, $min_metric)){ echo " (hours)"; } ?></p>
                                                            <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value" data-target="<?php if(in_array($metric->name, $min_metric, true)){echo round(($rows[0][$key] / 60), 2);}else{echo $rows[0][$key]; } ?>"><?php if(in_array($metric->name, $min_metric)){echo round(($rows[0][$key] / 60), 2);}else{echo $rows[0][$key]; } ?></span></h2>
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
                            <?php } ?>
                            <a class="see_more" href="<?php echo url('/youtube-details/'.$client_id.'/'.base64_encode(json_encode(array('start_date' => $start_date, 'end_date' => $end_date, 'maxresults' => $data['maxresults'], 'sort' => $data['sort'], 'section' => "content")))); ?>">See More<a>
                        <?php } ?>
                        <hr/>
                        <div class="col-sm-12">
                            <?php if(!empty($yt_top_watched)){ ?>
                                <h5 align="center">Your top content in this period</h5>
                                <?php //echo "<pre>"; var_dump($yt_top_watched);die; ?>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-nowrap align-middle mb-0">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Content</th>
                                                    <th scope="col">Views</th>
                                                    <th scope="col">Average view duration</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $count=1; foreach($yt_top_watched as $key => $metric){ //echo "<pre>"; var_dump($metric);die; ?>
                                                    <tr>
                                                        <td class="fw-medium">
                                                           <?php echo $count; ?> <a href="<?php echo url('/video-details/'.$client_id.'/'.$metric['video_statistics']->items[0]->id.'/'.base64_encode(json_encode(array('start_date' => $start_date, 'end_date' => $end_date, 'maxresults' => $data['maxresults'], 'sort' => $data['sort'], 'section' => "video_details")))); ?>"><img src="<?php echo $metric['video_statistics']->items[0]->snippet->thumbnails->default->url; ?>"/> <?php echo $metric['video_statistics']->items[0]->snippet->title; ?></a>
                                                        </td>
                                                        <td><?php echo $metric['video_data'][1]; ?></td>
                                                        <td><?php echo round($metric['video_data'][2] / 60, 2); ?></td>
                                                    </tr>
                                                <?php $count++; } ?>
                                            </tbody>
                                        </table>
                                    </div>
                            <?php } ?>  

                            <a class="mt-10 see_more" href="<?php echo url('/youtube-details/'.$client_id.'/'.base64_encode(json_encode(array('start_date' => $start_date, 'end_date' => $end_date, 'maxresults' => $data['maxresults'], 'sort' => $data['sort'], 'section' => "content")))); ?>">See More<a>

                        </div>
                    </div>
                </div>
            </div><!--end col-->
        </div><!--end row-->
    </div>
</div>

<div>
@endsection