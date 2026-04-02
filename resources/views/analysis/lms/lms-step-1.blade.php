@extends('layouts.page-app')

@section("content")

<?php
$lead_sources = $lms_data['lead_sources'];
?>
<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Lead Sources</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?php echo route('home'); ?>">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:$_SERVER['REQUEST_URI']; ?>">Client</a></li>
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
                    <div class="card-body">
                        <div class="col-lg-4 col-md-6">
                            <div class="mb-3">
                                <form method="post" action="">
                                    @csrf
                                    <input type="hidden" class="form-control" name="lms_client_id" value="<?php echo $lms_data['lms_client_id']; ?>" id="lms_client_id">
                                    <label for="choices-multiple-remove-button" class="form-label text-muted">Select Sources</label>
                                    <p class="text-muted">Set <code>multiple</code> source to filter. (CTRL + CLICK)</p>
                                    <select class="form-control" id="choices-multiple-remove-button" data-choices data-choices-removeItem name="lms_sources[]" multiple>
                                        <?php foreach($lead_sources as $source){// var_dump($source);die;?>
                                            <option value="<?php echo $source['lead_source_id']; ?>"><?php echo $source['lead_source_name']; ?></option>
                                        <?php } ?>
                                    </select>
                                    <input type="submit" class="btn btn-primary" value="Submit" />
                                </form>
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