<?php require "config.php" ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Connexion session</title>
</head>
<body>

		<form method="POST">
			<label>Login :</label>
			<br>
			<input type="text" name="login">
			<br>
			<label>Email :</label>
			<br>
			<input type="text" name="email">
			<br>
			<label>Mot de passe :</label>
			<br>
			<input type="password" name="password">
			<br>
			<label>Confirmation du mot de passe :</label>
			<br>
			<input type="password" name="cf_password">
			<br>
			<button name="send">Envoyer</button>
		</form>

</body>
</html>

<?php 
	if(isset($_POST['send'])){
		$query= $db->prepare("INSERT INTO user(login, email, password, cf_password VALUES(:login, :email, :password, :cf_password");
		$query->bindValue(":login", $_POST['send'], PDO::PARAM_STR);
		$query->bindValue(":email", $_POST['email'], PDO::PARAM_STR);
		$query->bindValue(":password", $_POST['password'], PDO::PARAM_STR);
		$query->bindValue(":cf_password", $_POST['cf_password'], PDO::PARAM_STR);
		$query->execute();
		echo "Ok";
	}
?>