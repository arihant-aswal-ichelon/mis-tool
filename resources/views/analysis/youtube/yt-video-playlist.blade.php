@extends('layouts.page-app')

@section("content")

<?php
    $youtube_data = $data['youtube_data']; 
    $client_id = $data['client_id'];
    $start_date = $data['start_date'];
    $end_date = $data['end_date'];
    $section = $data['section'];
?>
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
                        @include('analysis.youtube.yt-filter')
                    </div>
                    <div class="card-body yt-video-data row">
                        @include('analysis.youtube.yt-common')
                        <?php if(!empty($youtube_data)){ //echo "<pre/>"; var_dump($youtube_data);die; ?>
                            <div class="col-sm-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-nowrap align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th scope="col"><strong>Total</strong></td>
                                                <th scope="col">Views from playlist</th>
                                                <th scope="col">Views per playlist start</th>
                                                <th scope="col">Watch time (hours)</th>
                                                <th scope="col">Playlist starts</th>
                                                <th scope="col">Average time in playlist</th>
                                                <th scope="col">Playlist average view duration</th>
                                                <th scope="col">Playlist average percentage viewed</th>
                                            </tr>
                                        </thead>
                                    <tbody>
                                        <tr>
                                            <?php //echo "<pre/>"; var_dump($youtube_data);die;
                                                if(isset($youtube_data['statistics']) && !empty($youtube_data['statistics'])){
                                                    $statistics = $youtube_data['statistics']->rows;
                                                    foreach($youtube_data['statistics']->rows as $ky => $count) { ?>
                                                        <tr>
                                                            <td><?php echo $count[0]; ?></td>
                                                            <td><?php echo $count[1]; ?></td>
                                                            <td><?php echo $count[2]; ?></td>
                                                            <td><?php echo round($count[3] / 60, 2); ?></td>
                                                            <td><?php echo $count[4]; ?></td>
                                                            <td><?php echo round($count[5] / 60, 2); ?></td>
                                                            <td><?php echo round($count[6] / 60, 2); ?></td>
                                                            <td><?php echo round($count[7], 2); ?>%</td>
                                                        </tr>
                                                <?php } ?>
                                            <?php } ?>  
                                        </tbody>
                                    </table>
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