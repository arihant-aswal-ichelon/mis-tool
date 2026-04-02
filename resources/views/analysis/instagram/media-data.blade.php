@extends('layouts.page-app')
@section("content")

<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Instagram Media Details</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?php echo route('home'); ?>">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo url('/view-client/'.$client_id); ?>">Properties</a></li>
                            <li class="breadcrumb-item active">Instagram Media Details</li>
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
                        <a href="{{url('present-client/instagram-insights/'.$igaccountid.'/'.$client_id)}}" class="btn btn-primary">Back</a>
                        <div class="row">
                            <div class="col-md-12 mx-auto">
                                <table id="scroll-horizontal" class="table table-bordered nowrap align-middle" style="width:100%;table-layout: fixed;">
                                    <tbody>
                                        <?php if(isset($InstagramBusinessAccountList) && !empty($InstagramBusinessAccountList)){
                                            // echo "<pre/>";
                                            // var_dump($InstagramBusinessAccountList);die;
                                            ?>
                                            <tr>
                                                <td scope="col">Media Type</td>
                                                <td><?php echo isset($InstagramBusinessAccountList->media_type)?$InstagramBusinessAccountList->media_type:""; ?></td>
                                            </tr>
                                            <tr>
                                                <td scope="col">Media Product Type</td>
                                                <td><?php echo isset($InstagramBusinessAccountList->media_product_type)?$InstagramBusinessAccountList->media_product_type:""; ?></td>
                                            </tr>
                                            <tr>
                                                <td scope="col">Media Url</td>
                                                <td>
                                                    <?php if($InstagramBusinessAccountList->media_type == "VIDEO"){?>
                                                        <a style="font-size:25px;" href="<?php echo isset($InstagramBusinessAccountList->media_url)?$InstagramBusinessAccountList->media_url:""; ?>" target="_blank" class="btn btn-primary"><i class="ri-instagram-line"></i></a>
                                                    <?php } ?>
                                                    <?php if($InstagramBusinessAccountList->media_type == "IMAGE"){?>
                                                        <a href="<?php echo isset($InstagramBusinessAccountList->media_url)?$InstagramBusinessAccountList->media_url:""; ?>" target="_blank"><img src="<?php echo isset($InstagramBusinessAccountList->media_url)?$InstagramBusinessAccountList->media_url:""; ?>" style="max-width:100px;" /></a>
                                                    <?php } ?>
                                                    
                                                </td>
                                            </tr>
                                            <tr>
                                                <td scope="col">Thumbnail Url</td>
                                                <td><a href="<?php echo isset($InstagramBusinessAccountList->thumbnail_url)?$InstagramBusinessAccountList->thumbnail_url:""; ?>" target="_blank"><img src="<?php echo isset($InstagramBusinessAccountList->thumbnail_url)?$InstagramBusinessAccountList->thumbnail_url:""; ?>" style="max-width:100px;" /></a></td>
                                            </tr>
                                            <tr>
                                                <td scope="col">Likes</td>
                                                <td><?php echo isset($InstagramBusinessAccountList->like_count)?$InstagramBusinessAccountList->like_count:""; ?></td>
                                            </tr>
                                            <tr>
                                                <td scope="col">Comments</td>
                                                <td><?php echo isset($InstagramBusinessAccountList->comments_count)?$InstagramBusinessAccountList->comments_count:""; ?></td>
                                            </tr>
                                            <tr>
                                                <td scope="col">IG Url</td>
                                                <td><a style="font-size:25px;" target="_blank" href="<?php echo isset($InstagramBusinessAccountList->permalink)?$InstagramBusinessAccountList->permalink:""; ?>" /><i class="ri-instagram-line"></i></td>
                                            </tr>
                                            <tr>
                                                <td scope="col">Published On</td>
                                                <td><?php echo isset($InstagramBusinessAccountList->timestamp)?date("d-m-Y", strtotime($InstagramBusinessAccountList->timestamp)):""; ?></td>
                                            </tr>
                                            <tr>
                                                <td scope="col">Caption</td>
                                                <td>
                                                    <div class="content" style="word-wrap: break-word;">
                                                        <?php echo isset($InstagramBusinessAccountList->caption)?$InstagramBusinessAccountList->caption:""; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td scope="col">Comments</td>
                                                <td>
                                                    <?php if(isset($InstagramBusinessAccountList->comments->data) && !empty($InstagramBusinessAccountList->comments->data)){ ?>
                                                        <table class="table table-bordered nowrap align-middle">
                                                            <?php foreach($InstagramBusinessAccountList->comments->data as $comment){ ?>
                                                                <tr>
                                                                    <td><?php echo $comment->text; ?></td>
                                                                    <td><?php echo date("d-m-Y", strtotime($comment->timestamp)); ?></td>
                                                                </tr>
                                                            <?php } ?>
                                                        </table>
                                                    <?php } ?>
                                                </td>
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
