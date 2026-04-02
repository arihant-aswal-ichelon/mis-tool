<hr/>

<div class="col-sm-12 mb-15">
    <a class="btn btn-primary" href="<?php echo url('/present-client/instagram-insights/'.$instagramAccountId.'/'.$client_id.'/'); ?>">Overview</a>

    <a class="btn btn-primary <?php if($section == "content"){echo 'section-active';}?>" href="<?php echo url('/present-client/instagram-insights/'.$instagramAccountId.'/'.$client_id.'/?fields='.base64_encode(json_encode(array('section' => "content", 'from_section' => isset($data['from_section'])?$data['from_section']:"")))); ?>">Content</a>

    <a class="btn btn-primary <?php if($section == "hashtagSearch"){echo 'section-active';}?>" href="<?php echo url('/present-client/instagram-insights/'.$instagramAccountId.'/'.$client_id.'/?fields='.base64_encode(json_encode(array('section' => "hashtagSearch", 'from_section' => isset($data['from_section'])?$data['from_section']:"")))); ?>">HashTag Search</a>

    <a class="btn btn-primary <?php if($section == "reporting"){echo 'section-active';}?>" href="<?php echo url('/present-client/instagram-insights/'.$instagramAccountId.'/'.$client_id.'/?fields='.base64_encode(json_encode(array('section' => "reporting", 'from_section' => isset($data['from_section'])?$data['from_section']:"")))); ?>">Reporting</a>
</div>

<hr/>