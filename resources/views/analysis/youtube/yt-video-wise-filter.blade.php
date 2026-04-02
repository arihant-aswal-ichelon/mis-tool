<h4 class="card-title mb-0 flex-grow-1">Period: <?php echo date('F j, Y', strtotime($start_date)).' - '.date('F j, Y', strtotime($end_date)); ?></h4>
<form method="post" class="row" action="">
    @csrf
    <div class="col-sm-4">
        <label>Records</label>
        <select class="form-select mb-3" name="record" aria-label="record">
            <option value="10" <?php if(isset($data['maxresults']) && $data['maxresults'] == 10){echo "selected"; }?>>10</option>
            <option value="25" <?php if(isset($data['maxresults']) && $data['maxresults'] == 25){echo "selected"; }?>>25</option>
        </select>
    </div>
    <div class="col-sm-6">
        <label>Filter by Date</label>
        <input type="text" name="filter_datepicker" class="form-control flatpickr-input" id="filter-datepicker" data-provider="flatpickr" data-date-format="Y-m-d" data-range-date="true" readonly="readonly">
    </div>
    <input type="submit" class="btn btn-primary" value="Submit" />
</form>