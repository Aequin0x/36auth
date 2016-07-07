<?php 
require 'config.php';
function getUser(){
	global $db;
	if(isset($_SESSION['id'])){
		$userId = $_SESSION['id'];
		$query = $db->query('SELECT login, email, role FROM user WHERE id ='.$userId);
		$_SESSION['user'] = $query->fetch();
		return $_SESSION['user'];
	}
}
if(getUser()['role']=='admin'){
	echo "Bienvenu sur votre BACK OFFICE";
}else{
	header('HTTP/1.0 403 Forbidden');
}

?>