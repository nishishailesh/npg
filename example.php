<?php
ob_start();
require_once 'dxfwriter.php';    
$shape = new DXF();
$shape->addLayer("mylayer", DXF_COLOR_RED);
$shape->addLayer("text", DXF_COLOR_BLUE);
$shape->addCircle($_POST['x'], $_POST['y'],$_POST['z'],$_POST['r'], "mylayer");
$shape->addCircle($_POST['x']+2, $_POST['y'+2],$_POST['z'],$_POST['r'], "mylayer");
$shape->addCircle($_POST['x']+1, $_POST['y']+1,$_POST['z'],$_POST['r'], "mylayer");
$shape->addText($_POST['x'], $_POST['y'],$_POST['z'],$_POST['str'], 5, "text");
$shape->SaveFile("myFile.dxf"); 
?>
