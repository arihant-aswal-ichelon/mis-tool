@extends('layouts.page-app')
@section("content")
<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Client Properties</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Clients</a></li>
                            <li class="breadcrumb-item active">Client Properties</li>
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
                        <table id="scroll-horizontal" class="table nowrap align-middle" style="width:100%">
                            <thead>
                                <tr>
                                    <th scope="col">Property</th>
                                    <th scope="col">Domain</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($client_data->isNotEmpty()){ ?>
                                    <?php foreach($client_data as $key => $client){ ?>
                                        <?php if(!empty($client->lms_domain)){ 
                                            $explode_lms = explode('|', $client->lms_domain);
                                            foreach($explode_lms as $lms){
                                        ?>
                                            <tr>
                                                <td>LMS</td>
                                                <td>{{$lms}}</td>
                                                <td>
                                                    <div class="dropdown d-inline-block">
                                                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="ri-more-fill align-middle"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li><a href="{{url('present-client/'.$client->is_lms.'/'.$client->id.'/'.base64_encode($lms))}}" class="dropdown-item"><i class="mdi_icon mdi mdi-graphql text-muted"></i> Record</a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } } ?>
                                        <?php if(!empty($client->website_domain)){
                                            $explode_website = explode('|', $client->website_domain);
                                            foreach($explode_website as $website){
                                        ?>
                                            <tr>
                                                <td>Website</td>
                                                <td>{{$website}}</td>
                                                <td>
                                                    <div class="dropdown d-inline-block">
                                                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="ri-more-fill align-middle"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li><a href="{{url('present-client/'.$client->is_website.'/'.$client->id.'/'.base64_encode($website))}}" class="dropdown-item"><i class="mdi_icon mdi mdi-graphql text-muted"></i> Record</a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } } ?>
                                        <?php if(!empty($client->landing_domain)){ 
                                            $explode_landing = explode('|', $client->landing_domain);
                                            foreach($explode_landing as $landing){
                                        ?>
                                            <tr>
                                                <td>Landing Page</td>
                                                <td>{{$landing}}</td>
                                                <td>
                                                    <div class="dropdown d-inline-block">
                                                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="ri-more-fill align-middle"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li><a href="{{url('present-client/'.$client->is_landing.'/'.$client->id.'/'.base64_encode($landing))}}" class="dropdown-item"><i class="mdi_icon mdi mdi-graphql text-muted"></i> Record</a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } } ?>

                                        <?php if(!empty($client->landing_domain)){ 
                                            $explode_landing = explode('|', $client->landing_domain);
                                            foreach($explode_landing as $landing){
                                        ?>
                                            <tr>
                                                <td>Landing Page</td>
                                                <td>{{$landing}}</td>
                                                <td>
                                                    <div class="dropdown d-inline-block">
                                                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="ri-more-fill align-middle"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li><a href="{{url('present-client/'.$client->is_landing.'/'.$client->id.'/'.base64_encode($landing))}}" class="dropdown-item"><i class="mdi_icon mdi mdi-graphql text-muted"></i> Record</a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } } ?>

                                        <?php if($client->youtube == "active"){
                                        ?>
                                            <tr>
                                                <td>YouTube</td>
                                                <td>YouTube</td>
                                                <td>
                                                    <div class="dropdown d-inline-block">
                                                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="ri-more-fill align-middle"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li><a href="{{url('present-client/youtube/'.$client->id)}}" class="dropdown-item"><i class="mdi_icon mdi mdi-graphql text-muted"></i> Record</a></li>
                                                            <li><a href="{{url('present-client/assign-group/'.$client->id)}}" class="dropdown-item"><i class="mdi_icon mdi mdi-format-list-group text-muted"></i> Assign Group</a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } ?>

                                        <?php if($client->facebook == "active"){
                                        ?>
                                            <tr>
                                                <td>Facebook</td>
                                                <td>Instagram</td>
                                                <td>
                                                    <div class="dropdown d-inline-block">
                                                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="ri-more-fill align-middle"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li><a href="{{url('present-client/instagram/'.$client->id)}}" class="dropdown-item"><i class="mdi_icon mdi mdi-graphql text-muted"></i> Record</a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        
                                    <?php } ?>
                                <?php }else{ ?>
                                    <tr>
                                        <td colspan="4">No Record!!</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div><!--end col-->
        </div><!--end row-->
    </div>
</div>
@endsection