<?php
session_start();

$Controller = $_GET['Controller'];
$Action = $_GET['Action'];

if (file_exists("Controller/" . $Controller . "_controller.php")) {
    require("Controller/" . $Controller . "_controller.php");
    $ClassName = ucfirst($Controller) . "_Ctrl";
    $Ctrl = new $ClassName();
    $Ctrl->$Action();
} else {
    header("Location:index.php?ctrl=error&action=error_404");
}
