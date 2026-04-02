@extends('layouts.page-app')
@section("content")

<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Instagram Reporting</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?php echo route('home'); ?>">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo url('/view-client/'.$client_id); ?>">Properties</a></li>
                            <li class="breadcrumb-item active">Instagram Reporting</li>
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
                                <?php if(isset($data['igData']) && !empty($data['igData'])){
                                        $IgBusinessAccount = $data['igData'];
                                        // var_dump($IgBusinessAccount); die;
                                        echo '<table id="scroll-horizontal" class="table nowrap align-middle" style="width:100%">';
                                        echo '<thead><tr><th>Metric</th>';

                                        // First, create column headers (dates)
                                        foreach ($IgBusinessAccount as $period) {
                                            echo '<th>' . $period['sinceDate'] . ' to ' . $period['untilDate'] . '</th>';
                                        }
                                        echo '</tr></thead><tbody>';

                                        // Get all possible metrics from the first period's data object
                                        $metrics = array_keys(get_object_vars($IgBusinessAccount[0]['data']));

                                        // Create a row for each metric
                                        foreach ($metrics as $metric) {
                                            echo '<tr>';
                                            echo '<td>' . ucfirst(str_replace('_', ' ', $metric)) . '</td>';
                                            
                                            // Add values for each period
                                            foreach ($IgBusinessAccount as $period) {
                                                $value = $period['data']->{$metric};
                                                // Format numbers with commas for better readability
                                                if (is_int($value)) {
                                                    $value = number_format($value);
                                                }
                                                echo '<td>' . $value . '</td>';
                                            }
                                            
                                            echo '</tr>';
                                        }

                                        echo '</tbody></table>';   
                                } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
