@extends('layouts.page-app')
@section("content")

<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Instagram Posts</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?php echo route('home'); ?>">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo url('/view-client/'.$client_id); ?>">Properties</a></li>
                            <li class="breadcrumb-item active">Instagram Posts</li>
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
                                <table id="scroll-horizontal" class="table table-bordered nowrap align-middle" style="width:100%;table-layout: fixed;">
                                    <thead>
                                        <tr>
                                            <th scope="col">Action</th>
                                            <th scope="col">Media Type</th>
                                            <th scope="col">Media Product Type</th>
                                            <th scope="col">Media Url</th>
                                            <th scope="col">Thumbnail Url</th>
                                            <th scope="col">Likes</th>
                                            <th scope="col">Comments</th>
                                            <th scope="col">IG Url</th>
                                            <th scope="col">Published On</th>
                                            <th scope="col">Caption</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(isset($data['igData']) && !empty($data['igData'])){
                                                $IgBusinessAccount = $data['igData']; 
                                                foreach($IgBusinessAccount->data as $Igcontent){
                                        ?>
                                                    <tr mediaId="<?php echo $Igcontent->id; ?>">
                                                        <td>
                                                            <a href="{{url('present-client/instagram-insights/'.$instagramAccountId.'/'.$client_id.'/'.$Igcontent->id)}}">Details</a></td>
                                                        
                                                        <td><?php echo isset($Igcontent->media_type)?$Igcontent->media_type:""; ?></td>
                                                
                                                        <td><?php echo isset($Igcontent->media_product_type)?$Igcontent->media_product_type:""; ?></td>
                                                    
                                                        <td>
                                                            <?php if($Igcontent->media_type == "VIDEO"){?>
                                                                <a style="font-size:25px;" href="<?php echo isset($Igcontent->media_url)?$Igcontent->media_url:""; ?>" target="_blank" class="btn btn-primary"><i class="ri-instagram-line"></i></a>
                                                            <?php } ?>
                                                            <?php if($Igcontent->media_type == "IMAGE"){?>
                                                                <a href="<?php echo isset($Igcontent->media_url)?$Igcontent->media_url:""; ?>" target="_blank"><img src="<?php echo isset($Igcontent->media_url)?$Igcontent->media_url:""; ?>" style="max-width:100px;" /></a>
                                                            <?php } ?>
                                                            
                                                        </td>
                                                
                                                        <td><a href="<?php echo isset($Igcontent->thumbnail_url)?$Igcontent->thumbnail_url:""; ?>" target="_blank"><img src="<?php echo isset($Igcontent->thumbnail_url)?$Igcontent->thumbnail_url:""; ?>" style="max-width:100px;" /></a></td>
                                                    
                                                        <td><?php echo isset($Igcontent->like_count)?$Igcontent->like_count:""; ?></td>
                                                
                                                        <td><?php echo isset($Igcontent->comments_count)?$Igcontent->comments_count:""; ?></td>
                                                    
                                                        <td><a style="font-size:25px;" target="_blank" href="<?php echo isset($Igcontent->permalink)?$Igcontent->permalink:""; ?>" /><i class="ri-instagram-line"></i></td>

                                                        <td><?php echo isset($Igcontent->timestamp)?date("d-m-Y", strtotime($Igcontent->timestamp)):""; ?></td>
                                                
                                                        <td><?php echo isset($Igcontent->caption)?$Igcontent->caption:""; ?></td>
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
