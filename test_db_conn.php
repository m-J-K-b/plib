<?php


include_once('_admin/base.inc.php');

$db = new DB_MainClass();
$connect_error = $db->Connect();
echo($connect_error);