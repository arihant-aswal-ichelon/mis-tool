@extends('layouts.page-app')
@section("content")

<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Instagram Business Account</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?php echo route('home'); ?>">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo url('/view-client/'.$client_id); ?>">Properties</a></li>
                            <li class="breadcrumb-item active">Instagram Business Account</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <!-- Main content -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mx-auto">
                                <table id="scroll-horizontal" class="table nowrap align-middle" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th scope="col">IG Name</th>
                                            <th scope="col">IG Username</th>
                                            <th scope="col">Followers</th>
                                            <th scope="col">Following</th>
                                            <th scope="col">Posts</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(!empty($IgBusinessAccountArr)){ ?>
                                            <?php foreach($IgBusinessAccountArr as $IgBusinessAccount){ ?>
                                            <tr>
                                                <td><?php echo $IgBusinessAccount->name; ?></td>
                                                <td><?php echo $IgBusinessAccount->username; ?></td>
                                                <td><?php echo $IgBusinessAccount->followers_count; ?></td>
                                                <td><?php echo $IgBusinessAccount->follows_count; ?></td>
                                                <td><?php echo $IgBusinessAccount->media_count; ?></td>
                                                <td>
                                                    <div class="dropdown d-inline-block">
                                                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="ri-more-fill align-middle"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li><a href="{{url('present-client/instagram-insights/'.$IgBusinessAccount->id.'/'.$client_id)}}" class="dropdown-item"><i class="mdi_icon mdi mdi-graphql text-muted"></i> Record</a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
