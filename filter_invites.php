<?php
// return works by inviterName
include("connect-db.php");
include("request-db.php");

session_start();

$inviterName = $_GET['inviterName'];

$result = getWorksByInviter($inviterName, $_SESSION['username']);

header("Content-type: application/json");
header("Access-Control-Allow-Origin: *");
echo json_encode($result);
