<?php 

require 'config.php';
var_dump($_SESSION);
unset($_SESSION['id'],$_SESSION['login']);
setcookie('remember', '', time()-1);
//Replace logout.php
$url = str_replace('logout.php', '', $url);
header("Location: ".$url);
?>