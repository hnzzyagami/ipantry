<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
include_once '../../db/conn.php';
include_once '../../db/dao.php';


$scanned_txt = "063348006936";
//$scanned_item = lookup_remote_db($scanned_txt);
$scanned_item = lookup_local_db($scanned_txt);
var_dump($scanned_item);
//print_r($scanned_item);
