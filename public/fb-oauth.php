<?php

$input = $_REQUEST;
$data = "\n".json_encode($input)."\n";
$fp = fopen('fbAuth_log_input.txt', 'a');
fwrite($fp, $data);
fclose($fp);


echo json_encode(array('status' => true));
die;