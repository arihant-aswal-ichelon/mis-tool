<form method="post" class="row" action="">
    @csrf
    <div class="col-sm-2">
        <label>Records</label>
        <select class="form-select mb-3" name="record" aria-label="record">
            <option value="10" <?php if(isset($data['maxresults']) && $data['maxresults'] == 10){echo "selected"; }?>>10</option>
            <option value="25" <?php if(isset($data['maxresults']) && $data['maxresults'] == 25){echo "selected"; }?>>25</option>
        </select>
    </div>
    <div class="col-sm-3">
        <label>Sort By</label>
        <select class="form-select mb-3" name="sort" aria-label="sort">
            <?php foreach($data['youtube_filters'] as $sort){ ?>
                <option value="<?php echo $sort; ?>" <?php if($sort == $data['sort']){echo "selected"; }?>><?php echo ucfirst($sort); ?></option>
            <?php } ?>
        </select>
    </div>

    <div class="col-sm-5">
        <label>Tag Search</label>
        <select class="form-select mb-3" name="tag" required aria-label="tag">
            <option value="">-- Select Tag --</option>
            <?php foreach($data['youtube_tags'] as $tag){  ?>
                <option value="<?php echo $tag->tag_name; ?>" <?php if($tag->tag_name == $data['tag']){echo "selected"; }?>><?php echo ucfirst($tag->tag_name); ?></option>
            <?php } ?>
        </select>
    </div>
    
    <input type="submit" class="btn btn-primary" value="Submit" />
</form>