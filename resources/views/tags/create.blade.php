@extends('layouts.page-app')
@section("content")
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Add Tag</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{url('tag')}}">Tag</a></li>
                            <li class="breadcrumb-item active">Add Tag</li>
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
                        <div class="live-preview">
                            <form class="row g-3" method="post" action="{{url('add-tag')}}">
                                @csrf
                                <div class="col-md-4">
                                    <label for="validationDefault04" class="form-label">Client*</label>
                                    <select class="form-select" name="client_id" id="validationDefault04" required="">
                                        <option selected="" disabled="" value="">Choose...</option>
                                        <?php foreach($clients as $client){ ?>
                                            <option value="<?php echo $client->id; ?>"><?php echo $client->name; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="validationDefault01" class="form-label">Tag*</label>
                                    <input type="text" name="tag_name" class="form-control" id="validationDefault01" value="" required="">
                                </div>
                                <div class="col-md-4">
                                    <label for="validationDefault05" class="form-label">Status</label>
                                    <div class="form-check">
                                        <input type="radio" value="active" class="form-check-input" checked="checked" id="validationFormCheck2" name="status">
                                        <label class="form-check-label" for="validationFormCheck2">Active</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="radio" value="inactive" class="form-check-input" id="validationFormCheck3" name="status">
                                        <label class="form-check-label" for="validationFormCheck3">InActive</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div> <!-- end col -->
        </div>
        <!-- end row -->
    </div> <!-- container-fluid -->
</div>
@endsection