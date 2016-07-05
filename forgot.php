<?php 
	require 'config.php';
?>

<?php if(isset($_GET['forgetToken'])){ 
	$forget = $_GET['forgetToken'];
	$checkUser = $db->prepare("SELECT * FROM user WHERE forget = :forget");
	$checkUser->bindValue(":forget", $forget, PDO::PARAM_STR);
	$checkUser->execute();
	if($checkUser->rowCount()){ ?>
	<form method="POST">
		<div>
			<label>Nouveau mot de passe :</label>
			<input type="password" name="password">
		</div>
		<div>
			<label>Confirmer le nouveau mot de passe :</label>
			<input type="password" name="cf_password">
		</div>
		<div>
			<button name="newPassword">Envoyer</button>
		</div>
	</form>
	<?php 
		if(isset($_POST['newPassword'])){
			$user = $checkUser->fetch();
			$query = $db->prepare("UPDATE user SET password = :password WHERE id = :id");
			$password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT);
			$query ->bindValue(':password', $password, PDO::PARAM_STR);
			$query ->bindValue(':id', $user['id'], PDO::PARAM_STR);
			if($query->execute()){
				echo "Votre mot de passe a bien été mis à jour.";
				// ON remove le token car il n'a plus rien a faire dans la BDD une fois que le mdp est redefini
				$removeToken = $db->prepare("UPDATE user SET forget = NULL, dateForget = NULL WHERE forget = :forget");
				$removeToken->bindValue(':forget', $forget, PDO::PARAM_STR);
				$removeToken->execute();
			}
		}	
	}else{
		// On supprime le token s'il n'est pas valide (expiré)
		$removeToken = $db->prepare("UPDATE user SET forget = NULL, dateForget = NULL WHERE forget = :forget");
		$removeToken->bindValue(':forget', $forget, PDO::PARAM_STR);
		$removeToken->execute();
		echo "Votre token est invalide ou la date est éxpirée.";
		} 
	}else{ ?>
<form method="POST">
	<div>
		<label>Login :</label>
		<input type="text" name="login">
	</div>
	<div>
		<button name="forget">Envoyer</button>
	</div>
</form>
<?php } ?>
<?php 

if(isset($_POST['forget'])){
	$login = $_POST['login'];
	$checkUser = $db->prepare("SELECT * FROM user WHERE login = :login");
	$checkUser->bindValue(":login", $login, PDO::PARAM_STR);
	$checkUser->execute();
	if($checkUser->rowCount()){
		$forget = sha1(md5(uniqid(rand(), true)));
		$now = new Datetime();
		$now->modify("+3 day");
		$date = $now->format('Y-m-d H:i:s');
		$db->query("UPDATE user SET forget = '$forget' WHERE login = '".$login."'");
		echo "Bonjour ".$login.", vous pouvez redefinir votre mot de passe sur <a href='".$url."?forgetToken=".$forget."'>".$url."?forgetToken=".$forget."</a>";
	}else{
		echo "L'utilisateur n'existe pas";
		
	}
}

?>