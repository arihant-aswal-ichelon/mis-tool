<?php

file_put_contents("data_log.txt","\n". file_get_contents('php://input'), FILE_APPEND | LOCK_EX);

echo json_encode(array("status" => true, "message" => "Test"));
die;