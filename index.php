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
// FORMULAIRE SEND //

	// TEST POUR LE CRYPTAGE
/*$crypt = password_hash(trim(" test "), PASSWORD_DEFAULT);
var_dump(password_verify("test", $crypt));
*/
	if(isset($_POST['send'])){
		$date = time();
		$password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);

		$query= $db->prepare("INSERT INTO user(login, email, password, date) VALUES(:login, :email, :password, :date)");
		$query->bindValue(":login", $_POST['send'], PDO::PARAM_STR);
		$query->bindValue(":email", $_POST['email'], PDO::PARAM_STR);
		$query->bindValue(":password", $password, PDO::PARAM_STR);
		$query->bindValue(":date", $date, PDO::PARAM_INT);
		$query->execute();
		echo "Ok";
	}
?>

<form method="POST">
	
</form>