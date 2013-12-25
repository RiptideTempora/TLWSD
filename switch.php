<?php
session_start();
$req = null;
$page = null;
if(!empty($_GET['req'])) $req = $_GET['req'];
if(!empty($_POST['req'])) $req = $_POST['act'];
$req = explode("/", $req);
switch($req[1]) {
  case 'about':
    if(!empty($req[2])) $page = $req[2];
    include "includes/about.php";
  break;
}

?>