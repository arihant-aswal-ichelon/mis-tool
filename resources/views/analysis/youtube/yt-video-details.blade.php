@extends('layouts.page-app')

@section("content")

<?php use App\Http\Controllers\AnalysisController;

    $youtube_data = $data['youtube_data'][0];
    $client_id = $data['client_id'];
    $yt_channel_id = $data['yt_channel_id'];
    $access_token = $data['access_token'];
    $start_date = $data['start_date'];
    $end_date = $data['end_date'];
    $video_id = $data['video_id'];

?>
<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Video analytics</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?php echo route('home'); ?>">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo url('/view-client/'.$client_id); ?>">Properties</a></li>
                            <li class="breadcrumb-item active">Video Analysis</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body yt-video-data row">
                        <?php if(!empty($youtube_data)){ //echo "<pre/>"; var_dump($youtube_data['video_data']->items[0]->statistics);die; ?>

                            <div class="col-sm-12">
                                <div class="row">
                                    <img src="<?php echo isset($youtube_data['video_data']->items[0]->snippet->thumbnails->maxres->url)?$youtube_data['video_data']->items[0]->snippet->thumbnails->maxres->url:$youtube_data['video_data']->items[0]->snippet->thumbnails->high->url;?>" />
                                </div>
                                <label class="mt-10">Title:</label>
                                <h5><?php echo $youtube_data['video_data']->items[0]->snippet->title;?></h5>
                                <label class="mt-10">Description:</label>
                                <p><?php echo $youtube_data['video_data']->items[0]->snippet->description;?></p>
                                <label class="mt-10">Tags:</label>
                                <p class="addtagclass">
                                    <?php 
                                        if(isset($youtube_data['video_data']->items[0]->snippet->tags) 
                                            && !empty($youtube_data['video_data']->items[0]->snippet->tags)){
                                                $tags_arr = $youtube_data['video_data']->items[0]->snippet->tags;

                                                foreach($tags_arr as $tag){ //var_dump($tag);die; ?>
                                                    <a href="javascript:void(0);" onclick="addTagsFunc('<?php echo $tag; ?>');"><i class="bx bx-plus-circlebx bxs-plus-circle"></i></a>
                                                    <?php echo $tag; ?> |
                                            <?php }
                                        }
                                    ?>
                                </p>
                                <label class="mt-10">Published Date:</label>
                                <p><?php echo date('F j, Y, g:i a', strtotime($youtube_data['video_data']->items[0]->snippet->publishedAt)); ?></p>
                            </div>
                            <div class="col-sm-12">
                                <?php if(!empty($youtube_data['video_statistics']->rows)){ ?>
                                    <hr/>
                                    <h5 align="center">Your Statistics in this period</h5>
                                    <?php //echo "<pre>"; var_dump($yt_top_watched);die; ?>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-nowrap align-middle mb-0">
                                                <thead>
                                                    <tr>
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
                                                            <td class="fw-medium">
                                                                <?php echo $youtube_data['video_statistics']->rows[0][1]; ?>
                                                            </td>
                                                            <td><?php echo round($youtube_data['video_statistics']->rows[0][2] / 60, 2); ?></td>
                                                            <td><?php echo round($youtube_data['video_statistics']->rows[0][3] / 60, 2); ?></td>
                                                            <td><?php echo $youtube_data['video_statistics']->rows[0][4]; ?></td>
                                                            <td><?php echo $youtube_data['video_statistics']->rows[0][5]; ?></td>
                                                            <td><?php echo $youtube_data['video_statistics']->rows[0][6]; ?></td>
                                                            <td><?php echo $youtube_data['video_statistics']->rows[0][7]; ?></td>
                                                            <td><?php echo $youtube_data['video_statistics']->rows[0][8]; ?></td>
                                                            <td><?php echo $youtube_data['video_statistics']->rows[0][9]; ?></td>
                                                        </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                <?php } ?>  

                                <a class="mt-10 see_more" href="<?php echo url('/youtube-details/'.$client_id.'/'.base64_encode(json_encode(array('start_date' => $start_date, 'end_date' => $end_date, 'maxresults' => $data['maxresults'], 'sort' => $data['sort'], 'section' => "content")))); ?>">See More<a>
                                <hr/>
                                
                                <h5 align="center">This Video Audience Retention</h5>
                                <?php
                                        $report_arr = AnalysisController::video_wise_organic_audience_retention($video_id, $start_date, $end_date, $yt_channel_id, $access_token);    
                                        //echo "<pre/>"; var_dump($report_arr);die;
                                ?>

                                <table class="table table-striped table-nowrap align-middle mb-0">
                                    <thead>
                                        <th scope="col">S.No.</th>
                                        <th scope="col">Elapsed Video Time Ratio</th>
                                        <th scope="col">Audience Watch Ratio</th>
                                        <th scope="col">Relative Retention Performance</th>
                                    </thead>
                                    <tbody>
                                        <?php if(!empty($report_arr)){ $count = 1; foreach($report_arr->rows as $report){ ?>
                                            <tr>
                                                <td><?php echo  $count; ?></td>
                                                <td><?php echo  round($report[0], 2); ?></td>
                                                <td><?php echo  round($report[1], 2); ?></td>
                                                <td><?php echo  round($report[2], 2); ?></td>
                                            </tr>
                                        <?php $count++; } } ?>
                                    </tbody>
                                </table>

                                <a class="mt-10 see_more" href="<?php echo url('/youtube-details/'.$client_id.'/'.base64_encode(json_encode(array('start_date' => $start_date, 'end_date' => $end_date, 'maxresults' => $data['maxresults'], 'sort' => $data['sort'], 'section' => "content")))); ?>">See More<a>
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
        var options = {
            series: [{
                data: [0.43, 0.44, 0.45, 0.46, 0.49, 0.51]
            }],
            chart: {
                type: 'line',
                height: 350
            },
            stroke: {
                curve: 'stepline',
            },
            dataLabels: {
                enabled: false
            },
            title: {
                text: 'Stepline Chart',
                align: 'left'
            },
            markers: {
                hover: {
                    sizeOffset: 4
                }
            },
            xaxis:{categories:["10%","40%","60%","80%","100%"]}
        };

        var chart = new ApexCharts(document.querySelector("#line_chart_basic"), options);
        chart.render();

        function addTagsFunc(tagname){
            $.ajax({
                url : "{{url('addtagname')}}",                
                data : {'tagname' : tagname, 'client_id': '<?php echo $client_id; ?>'},
                type : 'post',
                dataType : 'json',
                success : function(result){
                    $('.invalid-tooltip, .valid-tooltip').remove();
                    if(result.status == false){
                        $("p.addtagclass").append('<div class="invalid-tooltip" style="display:block;">'+result.message+'</div>');
                    }else if(result.status == true){
                        $("p.addtagclass").append('<div class="valid-tooltip" style="display:block;">'+result.message+'</div>');
                    }else{
                        $("p.addtagclass").append('<div class="invalid-tooltip" style="display:block;">'+result.message+'</div>');
                    }
                }
            });
        }
</script>
@endsection