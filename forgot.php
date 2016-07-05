<?php 
	require 'config.php';
?>
<form method="POST">
	<div>
		<label>Login :</label>
		<input type="text" name="login">
	</div>
	<div>
		<button name="forget">Envoyer</button>
	</div>
</form>
<?php 
if(isset($_POST['forget'])){
	$login = $_POST['login'];
	$checkUser = $db->prepare("SELECT * FROM user WHERE login = :login");
	$checkUser->bindValue(":login", $login, PDO::PARAM_STR);
	$checkUser->execute();
	if($checkUser->rowCount()){
		$forget = sha1(md5(uniqid(rand(), true)));
		$db->query("UPDATE user SET forget = '$forget' WHERE login = '".$login."'");
		echo "Bonjour ".$login.", vous pouvez redefinir votre mot de passe sur <a href='".$url."?forget=".$forget."'>".$url."?forget=".$forget."</a>";
	}else{
		echo "L'utilisateur n'existe pas";
		
	}
}
?>