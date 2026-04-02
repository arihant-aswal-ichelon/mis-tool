@extends('layouts.page-app')

@section("content")

<?php
$lms_leads = $lms_data['lms_leads'];
$lms_stages = $lms_data['lms_stages'];
$lead_sources = $lms_data['lead_sources'];
$lead_source_count = $lms_data['lead_source_count'];
$filters = $lms_data['filters'];
// var_dump($filters);die;
?>
<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">LMS Analysis</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?php echo route('home'); ?>">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo url('/view-client/'.$lms_data['lms_client_id']); ?>">Client</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:$_SERVER['REQUEST_URI']; ?>">Lead Sources</a></li>
                            <li class="breadcrumb-item active">LMS Analysis</li>
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
                        <h4 class="card-title mb-0 flex-grow-1">Filters</h4>
                    </div>
                    <div class="card-body">
                        <form method="post" action="">
                            @csrf
                            <div class="row align-items-center g-3">
                                <div class="col-lg-4">
                                    <label for="choices-multiple-remove-button" class="form-label text-muted">Select Sources</label>
                                    <p class="text-muted">Set <code>multiple</code> source to filter.</p>
                                    <select class="form-control" id="choices-multiple-remove-button" data-choices data-choices-removeItem name="lms_sources[]" multiple>
                                        <?php foreach($lead_sources as $source){// var_dump($source);die;?>
                                            <option value="<?php echo $source['lead_source_id']; ?>" <?php if(isset($filters['filter_source']) && !empty($filters['filter_source']) && in_array($source['lead_source_id'],$filters['filter_source'])){echo "selected"; }?>><?php echo $source['lead_source_name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <label for="choices-multiple-remove-button" class="form-label text-muted">Select Stages</label>
                                    <p class="text-muted">Set <code>multiple</code> stage to filter.</p>
                                    <select class="form-control" id="choices-multiple-remove-button" data-choices data-choices-removeItem name="lms_stages[]" multiple>
                                        <?php foreach($lms_stages as $stage){// var_dump($source);die;?>
                                            <option value="<?php echo $stage['id']; ?>" <?php if(isset($filters['filter_stage']) && !empty($filters['filter_stage']) && in_array($stage['id'],$filters['filter_stage'])){echo "selected"; }?>><?php echo $stage['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div> 
                                <div class="col-lg-4 mt-12">
                                    <input type="submit" class="btn btn-primary" value="Submit" />
                                    <a class="btn btn-primary" href="<?php echo isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:$_SERVER['REQUEST_URI']; ?>">Reset</a>
                                </div>
                            </div>
                        </form>
                        <hr class="mb-0">
                        <div class="card-header align-items-center d-flex mb-3">
                            <h4 class="card-title mb-0 flex-grow-1">Today's Lead Count</h4>
                        </div>
                        <div class="row">
                            <?php if(!empty($lead_source_count)){ ?>
                                <?php foreach($lead_source_count as $count){ //var_dump($count);die;?>
                                    <div class="col-md-3">
                                        <div class="card card-animate mb-0">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <p class="fw-medium text-muted mb-0"><?php echo $count['source']?></p>
                                                        <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value" data-target="<?php echo $count['lead_count']; ?>"><?php echo $count['lead_count']; ?></span></h2>
                                                    </div>
                                                    <div>
                                                        <div class="avatar-sm flex-shrink-0">
                                                            <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-activity text-info"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!-- end card body -->
                                        </div> <!-- end card-->
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        </div>
                        <hr class="mb-0">
                        <div class="card-header align-items-center d-flex mb-3">
                            <h4 class="card-title mb-0 flex-grow-1">Leads Data</h4>
                        </div>
                        <div class="row">
                            <?php if(!empty($lms_leads)){ ?>
                                    <?php foreach($lms_leads as $count){ //var_dump($count);die;?>
                                        <div class="col-md-3">
                                            <div class="card card-animate mb-0">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between">
                                                        <div>
                                                            <p class="fw-medium text-muted mb-0"><?php echo $count['source']?></p>
                                                            <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value" data-target="<?php echo $count['lead_count']; ?>"><?php echo $count['lead_count']; ?></span></h2>
                                                        </div>
                                                        <div>
                                                            <div class="avatar-sm flex-shrink-0">
                                                                <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-activity text-info"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div><!-- end card body -->
                                            </div> <!-- end card-->
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                        </div>
                    </div>
                </div>
            </div><!--end col-->
        </div><!--end row-->
    </div>
</div>
<div>
@endsection