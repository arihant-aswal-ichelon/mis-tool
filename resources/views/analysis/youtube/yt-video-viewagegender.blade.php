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
                        <?php if(!empty($youtube_data)){ //echo "<pre/>"; var_dump($youtube_data['statistics']);die; ?>
                            <div class="col-sm-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-nowrap align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th scope="col">Viewer age</th>
                                                <th scope="col">Views</th>
                                            </tr>
                                        </thead>
                                    <tbody>
                                        <tr>
                                            <?php if(isset($youtube_data['statistics']) && !empty($youtube_data['statistics'])){
                                                $traffic_statistics = $youtube_data['statistics']->rows;
                                                foreach($traffic_statistics as $ky => $count) {?>
                                                    <tr>
                                                        <td><?php echo $count[0]; ?> Years</td>
                                                        <td><?php echo $count[1]; ?>%</td>
                                                    </tr>
                                                <?php } } ?>  
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