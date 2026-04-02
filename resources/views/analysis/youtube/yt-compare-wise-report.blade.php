@extends('layouts.page-app')

@section("content")

<?php use App\Http\Controllers\AnalysisController; 
    $client_id = $data['client_id'];
    $section = $data['section'];
    $start_date = $data['start_date'];
    $end_date = $data['end_date'];
    $access_token = $data['access_token'];
    $maxresults = $data['maxresults'];
    $yt_channel_id = $data['yt_channel_id'];
    $sort = $data['sort'];
    $youtube_data = $data['youtube_data'];
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
                    <h4 class="mb-sm-0">Compare Wise Reports</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?php echo route('home'); ?>">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo url('/view-client/'.$client_id); ?>">Properties</a></li>
                            <li class="breadcrumb-item active">Compare Wise Reports</li>
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
                        @include('analysis.youtube.filters.yt-compare-filter')
                    </div>
                    <div class="card-body yt-video-data row">
                        @include('analysis.youtube.yt-common')

                        <?php if(!empty($youtube_data)) { ?>
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <?php if(isset($youtube_data['compare_to']) && !empty($youtube_data['compare_to'])){ $yt_data = $youtube_data['compare_to']['DataArr']; ?>
                                        <h4 class="card-title mb-0 flex-grow-1">Period: <?php echo date('F j, Y', strtotime($youtube_data['compare_to']['start_date'])).' - '.date('F j, Y', strtotime($youtube_data['compare_to']['end_date'])); ?></h4>

                                        <table class="table table-striped table-nowrap align-middle mb-0">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Content</th>
                                                        <th scope="col">Views</th>
                                                        <th scope="col">Estimated minutes watched</th>
                                                        <th scope="col">Average view duration</th>
                                                        <th scope="col">Comments</th>
                                                        <th scope="col">Likes</th>
                                                        <th scope="col">Dislikes</th>
                                                        <th scope="col">Shares</th>
                                                        <th scope="col">Subscribers Gained</th>
                                                        <th scope="col">Subscribers Lost</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><strong>Total</strong></td>
                                                        <?php foreach($yt_data as $vtotal) { $video_total = $vtotal['video_total']->rows[0]; 
                                                                foreach($video_total as $key => $total){ if($key == 1 || $key == 2){$total = round($total / 60, 2); }?>
                                                                    <td><?php echo $total; ?></td>  
                                                        <?php } break; } ?>
                                                    </tr>
                                                        <?php $rank = 1; foreach($yt_data as $data) { ?>
                                                            <?php $video_statistics = $data['video_statistics']; 
                                                                $video_metrics = $data['video_metrics']; 
                                                                $video_data = $data['video_data'];
                                                                $video_statistic_items = $video_statistics->items[0];
                                                                //echo "<pre>"; var_dump($data);die;
                                                            ?>
                                                            <tr>
                                                                <td class="fw-medium">
                                                                <?php echo $rank; ?> 

                                                                <a href="<?php echo url('/video-details/'.$client_id.'/'.$video_statistic_items->id.'/'.base64_encode(json_encode(array('start_date' => $start_date, 'end_date' => $end_date, 'maxresults' => $maxresults, 'sort' => $sort, 'section' => "video_details")))); ?>">
                                                                    <img src="<?php echo $video_statistic_items->snippet->thumbnails->default->url; ?>" style="max-width:70px;">
                                                                    <?php echo substr_replace($video_statistic_items->snippet->title, "...", 30); ?>
                                                                </a> 
                                                                </td>
                                                                <?php foreach($video_data as $key => $count){ if($key == 2 || $key == 3){$count = round($count / 60, 2); }?>
                                                                    <td><?php echo $count; ?></td>
                                                                <?php } ?>
                                                            </tr>
                                                        <?php $rank++; } ?>  
                                                    </tbody>
                                        </table>
                                    <?php } ?>

                                    <?php if(isset($youtube_data['compare_from']) && !empty($youtube_data['compare_from'])){ $yt_data = $youtube_data['compare_from']['DataArr']; ?>
                                        <hr/>
                                        <h4 class="card-title mb-0 flex-grow-1">Period: <?php echo date('F j, Y', strtotime($youtube_data['compare_from']['start_date'])).' - '.date('F j, Y', strtotime($youtube_data['compare_from']['end_date'])); ?></h4>

                                        <table class="table table-striped table-nowrap align-middle mb-0">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Content</th>
                                                        <th scope="col">Views</th>
                                                        <th scope="col">Estimated minutes watched</th>
                                                        <th scope="col">Average view duration</th>
                                                        <th scope="col">Comments</th>
                                                        <th scope="col">Likes</th>
                                                        <th scope="col">Dislikes</th>
                                                        <th scope="col">Shares</th>
                                                        <th scope="col">Subscribers Gained</th>
                                                        <th scope="col">Subscribers Lost</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><strong>Total</strong></td>
                                                        <?php foreach($yt_data as $vtotal) { $video_total = $vtotal['video_total']->rows[0]; 
                                                                foreach($video_total as $key => $total){ if($key == 1 || $key == 2){$total = round($total / 60, 2); }?>
                                                                    <td><?php echo $total; ?></td>  
                                                        <?php } break; } ?>
                                                    </tr>
                                                        <?php $rank = 1; foreach($yt_data as $data) { ?>
                                                            <?php $video_statistics = $data['video_statistics']; 
                                                                $video_metrics = $data['video_metrics']; 
                                                                $video_data = $data['video_data'];
                                                                $video_statistic_items = $video_statistics->items[0];
                                                                //echo "<pre>"; var_dump($data);die;
                                                            ?>
                                                            <tr>
                                                                <td class="fw-medium">
                                                                <?php echo $rank; ?> 

                                                                <a href="<?php echo url('/video-details/'.$client_id.'/'.$video_statistic_items->id.'/'.base64_encode(json_encode(array('start_date' => $start_date, 'end_date' => $end_date, 'maxresults' => $maxresults, 'sort' => $sort, 'section' => "video_details")))); ?>">
                                                                    <img src="<?php echo $video_statistic_items->snippet->thumbnails->default->url; ?>" style="max-width:70px;">
                                                                    <?php echo substr_replace($video_statistic_items->snippet->title, "...", 30); ?>
                                                                </a> 
                                                                </td>
                                                                <?php foreach($video_data as $key => $count){ if($key == 2 || $key == 3){$count = round($count / 60, 2); }?>
                                                                    <td><?php echo $count; ?></td>
                                                                <?php } ?>
                                                            </tr>
                                                        <?php $rank++; } ?>  
                                                    </tbody>
                                        </table>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>

                    </div>
                </div>
            </div><!--end col-->
        </div><!--end row-->
    </div>
</div>

<div>

@endsection