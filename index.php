<?php
ob_start();

session_start();
echo undefined_function();
$Controller = isset($_GET['Controller']) ? $_GET['Controller'] : 'home';
$Action = isset($_GET['Action']) ? $_GET['Action'] : 'index';

require("controllers/mother_controller.php");

if (file_exists("controllers/" . $Controller . "_controller.php")) {
    require("controllers/" . $Controller . "_controller.php");
    $ClassName = ucfirst($Controller) . "_Ctrl";
    $Ctrl = new $ClassName();
    $Ctrl->$Action();
} else {
    header("Location:index.php?Controller=error&Action=error_404");
}
