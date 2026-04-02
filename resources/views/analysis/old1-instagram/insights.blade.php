@extends('layouts.page-app')
@section("content")

<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Instagram Insights</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?php echo route('home'); ?>">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo url('/view-client/'.$client_id); ?>">Properties</a></li>
                            <li class="breadcrumb-item active">Instagram Insights</li>
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
                        @include('analysis.instagram.filters.common')
                        <div class="row">
                            <div class="col-md-12 mx-auto">
                                <table id="scroll-horizontal" class="table table-bordered nowrap align-middle" style="width:100%">
                                    <tbody>
                                        <?php if(isset($data['igData']) && !empty($data['igData'])){
                                                $IgBusinessAccount = $data['igData']; ?>
                                            <tr>
                                                <td>IG Name: </td><td><?php echo $IgBusinessAccount->name; ?></td>
                                            </tr>
                                            <tr>
                                                <td>IG Username: </td><td><?php echo $IgBusinessAccount->username; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Followers: </td><td><?php echo $IgBusinessAccount->followers_count; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Following: </td><td><?php echo $IgBusinessAccount->follows_count; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Posts: </td><td><?php echo $IgBusinessAccount->media_count; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Biography: </td><td><?php echo $IgBusinessAccount->biography; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Website: </td><td><?php echo $IgBusinessAccount->website; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Profile Picture: </td><td><img src="<?php echo $IgBusinessAccount->profile_picture_url; ?>" /></td>
                                            </tr>
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
