@extends('layouts.page-app')

@section("content")

<?php
    $client_id = $data['client_id'];
?>

<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Assign YT Group</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?php echo route('home'); ?>">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo url('/view-client/'.$client_id); ?>">Properties</a></li>
                            <li class="breadcrumb-item active">Assign YT Group</li>
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
                        <form class="row" action="#">
                            @csrf
                            <div class="row"><label>Sort By </label></div>
                            <div class="col-sm-8">
                                <select class="form-select mb-3" name="sort" aria-label="sort">
                                    <?php foreach($data['youtube_filters'] as $sort){ ?>
                                        <option value="<?php echo $sort; ?>" <?php if($sort == $data['sort']){echo "selected"; }?>><?php echo ucfirst($sort); ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <input type="button" id="search_videos" class="btn btn-primary" value="Search" />
                            </div>
                        </form>
                    </div>
                    <div class="card-body yt-video-data row">
                        <div class="table-responsive">
                                <table class="table table-striped table-nowrap align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col"></th>
                                            <th scope="col">Group</th>
                                        </tr>
                                    </thead>
                                    <tbody id="yt_channel_videos"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--end col-->
        </div><!--end row-->
    </div>
</div>

<div>

@endsection