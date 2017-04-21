<?php
SESSION_START();
$isLogged = false;
if(is_numeric(@$_SESSION["id"])){
	$isLogged = true;
}
if($isLogged){
    include("html/index.php");
}
else {
    include("html/login.php");
}

?>

