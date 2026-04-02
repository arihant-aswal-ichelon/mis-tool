@extends('layouts.page-app')

@section("content")

<?php use App\Http\Controllers\AnalysisController;

    $youtube_data = $data['youtube_data'];
    $client_id = $data['client_id'];
    $yt_channel_id = $data['yt_channel_id'];
    $access_token = $data['access_token'];
    $start_date = $data['start_date'];
    $end_date = $data['end_date'];
    $section = $data['section'];
    $sort = $data['sort'];
    $maxresults = $data['maxresults'];
    
    $common_video_arr = $report_arr = array();
    foreach($youtube_data as $data) {
        $video_statistics = $data['video_statistics'];
        $video_statistic_items = $video_statistics->items[0]; 
        
        $video_id = $video_statistic_items->id;
        $video_img = $video_statistic_items->snippet->thumbnails->default->url;
        $video_title = $video_statistic_items->snippet->title;

        $common_video_arr[] = array('video_id' => $video_id,
        'video_img' => $video_img, 'video_title' => $video_title);
    }
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
                    <h4 class="mb-sm-0">Channel Analytics</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?php echo route('home'); ?>">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo url('/view-client/'.$client_id); ?>">Properties</a></li>
                            <li class="breadcrumb-item active">Channel Analytics</li>
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
                        @include('analysis.youtube.yt-video-wise-filter')
                    </div>
                    <div class="card-body yt-video-data row">
                        @include('analysis.youtube.yt-common')
                        <?php  if(!empty($youtube_data)){ ?>
                            <div class="col-sm-12">
                                <p>Scroll To Report: <a href="#Video-Search-Terms">Video Search Terms</a> |  <a href="#Video-Traffic">Video Traffic</a> |  <a href="#Video-Demographic">Video Demographic</a> |  <a href="#Video-Social-Sharing">Video Social Sharing</a> </p>
                            </div>
                            <hr/>
                            <div class="col-sm-12">
                            <h4>Video Search Terms Report</h4>
                            <div class="table-responsive">
                                <table id="Video-Search-Terms" class="table table-striped table-nowrap align-middle mb-0">
                                    <?php foreach($common_video_arr as $video){ 
                                        $video_id = $video['video_id'];
                                        $report_arr = AnalysisController::video_wise_search_terms($video_id, $start_date, $end_date, $yt_channel_id, $access_token, $maxresults, $sort);   
                                        
                                    ?>
                                        <tr>
                                            <td>
                                                <img src="<?php echo $video['video_img']; ?>" style="max-width:70px;">
                                                <?php echo substr_replace($video['video_title'], "...", 30); ?>
                                            </td>
                                            <td>
                                                
                                                    <table>
                                                        <thead>
                                                            <th scope="col">Keyword</th>
                                                            <th scope="col">Views</th>
                                                            <th scope="col">Estimated minutes watched</th>
                                                            <th scope="col">Average view duration</th>
                                                            <th scope="col">Average view percentage</th>
                                                        </thead>
                                                        <tbody>
                                                            <?php if(!empty($report_arr)){ foreach($report_arr->rows as $report){ ?>
                                                                <tr>
                                                                    <td><?php echo  $report[0]; ?></td>
                                                                    <td><?php echo  $report[1]; ?></td>
                                                                    <td><?php echo  round($report[2] / 60, 2); ?></td>
                                                                    <td><?php echo  round($report[3] / 60, 2); ?></td>
                                                                    <td><?php echo  $report[4]; ?>%</td>
                                                                </tr>
                                                            <?php } } ?>
                                                        </tbody>
                                                    </table>
                                                
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    </table>
                                </div>
                            </div>

                            <hr/>

                            <div class="col-sm-12">
                            <h4>Video Traffic Report</h4>
                            <div class="table-responsive">
                                <table id="Video-Traffic" class="table table-striped table-nowrap align-middle mb-0">
                                    <?php foreach($common_video_arr as $video){ 
                                        $video_id = $video['video_id'];
                                        $report_arr = AnalysisController::video_wise_traffic($video_id, $start_date, $end_date, $yt_channel_id, $access_token, $maxresults, $sort);    
                                       //echo "<pre/>"; var_dump($report_arr);die;
                                    ?>
                                        <tr>
                                            <td>
                                                <img src="<?php echo $video['video_img']; ?>" style="max-width:70px;">
                                                <?php echo substr_replace($video['video_title'], "...", 30); ?>
                                            </td>
                                            <td>
                                                
                                                    <table>
                                                        <thead>
                                                            <th scope="col">Source</th>
                                                            <th scope="col">Views</th>
                                                            <th scope="col">Estimated minutes watched</th>
                                                            <th scope="col">Average view duration</th>
                                                            <th scope="col">Average view percentage</th>
                                                        </thead>
                                                        <tbody>
                                                            <?php if(!empty($report_arr)){ foreach($report_arr->rows as $report){ ?>
                                                                <tr>
                                                                    <td><?php echo  $report[0]; ?></td>
                                                                    <td><?php echo  $report[1]; ?></td>
                                                                    <td><?php echo  round($report[2] / 60, 2); ?></td>
                                                                    <td><?php echo  round($report[3] / 60, 2); ?></td>
                                                                    <td><?php echo  $report[4]; ?>%</td>
                                                                </tr>
                                                            <?php } } ?>
                                                        </tbody>
                                                    </table>
                                                
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    </table>
                                </div>
                            </div>

                            <hr/>

                            <div class="col-sm-12">
                            <h4>Video Demographic Report</h4>
                            <div class="table-responsive">
                                <table id="Video-Demographic" class="table table-striped table-nowrap align-middle mb-0">
                                    <?php foreach($common_video_arr as $video){ 
                                        $video_id = $video['video_id'];
                                        $report_arr = AnalysisController::video_wise_demographic($video_id, $start_date, $end_date, $yt_channel_id, $access_token, $maxresults, $sort);    
                                        //echo "<pre/>"; var_dump($report_arr);die;
                                    ?>
                                        <tr>
                                            <td>
                                                <img src="<?php echo $video['video_img']; ?>" style="max-width:70px;">
                                                <?php echo substr_replace($video['video_title'], "...", 30); ?>
                                            </td>
                                            <td>
                                                
                                                    <table>
                                                        <thead>
                                                            <th scope="col">Age Group</th>
                                                            <th scope="col">Gender</th>
                                                            <th scope="col">Viewer Percentage</th>
                                                        </thead>
                                                        <tbody>
                                                            <?php if(!empty($report_arr)){ foreach($report_arr->rows as $report){ ?>
                                                                <tr>
                                                                    <td><?php echo  $report[0]; ?></td>
                                                                    <td><?php echo  $report[1]; ?></td>
                                                                    <td><?php echo  $report[2]; ?>%</td>
                                                                </tr>
                                                            <?php } } ?>
                                                        </tbody>
                                                    </table>
                                                
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    </table>
                                </div>
                            </div>

                            <hr/>

                            <div class="col-sm-12">
                            <h4>Video Social Sharing Report</h4>
                            <div class="table-responsive">
                                <table id="Video-Social-Sharing" class="table table-striped table-nowrap align-middle mb-0">
                                    <?php foreach($common_video_arr as $video){ 
                                        $video_id = $video['video_id'];
                                        $report_arr = AnalysisController::video_wise_social_sharing($video_id, $start_date, $end_date, $yt_channel_id, $access_token, $maxresults, $sort);    
                                        // echo "<pre/>"; var_dump($report_arr);die;
                                    ?>
                                        <tr>
                                            <td>
                                                <img src="<?php echo $video['video_img']; ?>" style="max-width:70px;">
                                                <?php echo substr_replace($video['video_title'], "...", 30); ?>
                                            </td>
                                            <td>
                                                
                                                    <table>
                                                        <thead>
                                                            <th scope="col">Sharing Service</th>
                                                            <th scope="col">Shares</th>
                                                        </thead>
                                                        <tbody>
                                                            <?php if(!empty($report_arr)){ foreach($report_arr->rows as $report){ ?>
                                                                <tr>
                                                                    <td><?php echo  $report[0]; ?></td>
                                                                    <td><?php echo  $report[1]; ?></td>
                                                                </tr>
                                                            <?php } } ?>
                                                        </tbody>
                                                    </table>
                                                
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    </table>
                                </div>
                            </div>

                            <hr/>

                            <!-- <div class="col-sm-12">
                            <h4>Video Organic Audience Retention Report</h4>
                            <div class="table-responsive">
                                <table id="Video-Organic-Audience-Retention" class="table table-striped table-nowrap align-middle mb-0">
                                    <?php //foreach($common_video_arr as $video){ 
                                        //$video_id = $video['video_id'];
                                       // $report_arr = AnalysisController::video_wise_organic_audience_retention($video_id, $start_date, $end_date, $yt_channel_id, $access_token, $maxresults, $sort);    
                                        // echo "<pre/>"; var_dump($report_arr);die;
                                    ?>
                                        <tr>
                                            <td>
                                                <img src="<?php //echo $video['video_img']; ?>" style="max-width:70px;">
                                                <?php //echo substr_replace($video['video_title'], "...", 30); ?>
                                            </td>
                                            <td>
                                                
                                                    <table>
                                                        <thead>
                                                            <th scope="col">Elapsed Video Time Ratio</th>
                                                            <th scope="col">Audience Watch Ratio</th>
                                                            <th scope="col">Relative Retention Performance</th>
                                                        </thead>
                                                        <tbody>
                                                            <?php //if(!empty($report_arr)){ foreach($report_arr->rows as $report){ ?>
                                                                <tr>
                                                                    <td><?php //echo  round($report[0], 2); ?></td>
                                                                    <td><?php //echo  round($report[1], 2); ?></td>
                                                                    <td><?php //echo  round($report[2], 2); ?></td>
                                                                </tr>
                                                            <?php //} } ?>
                                                        </tbody>
                                                    </table>
                                                
                                            </td>
                                        </tr>
                                    <?php //} ?>
                                    </table>
                                </div>
                            </div> -->
                            
                        <?php } ?>
                    </div>
                </div>
            </div><!--end col-->
        </div><!--end row-->
    </div>
</div>

<div>
@endsection