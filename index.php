<?php 
require 'config.php';
var_dump($_SESSION);
$_SESSION['IP'] = $_SERVER['REMOTE_ADDR'];

setcookie('cookie', 'test', time()+60*60*24*365, '/cours/36auth', 'localhost', FALSE, TRUE);
var_dump($_COOKIE);
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Connexion session</title>
</head>
<body>

<?php 

if(!isset($_SESSION['id'])){ 

?>

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
<br>
</body>
</html>

<form method="POST">
	<div>
		<label>Login :</label>
		<br>
		<input type="text" name="login">
	</div>
	<div>
		<label>Password :</label>
		<br>
		<input type="text" name="password">
	</div>
	<input type="checkbox" name="remember">Se souvenir de moi
	<div>
		<button name="loginForm">Se connecter</button>
	</div>
</form>

<?php }else{ ?>
	Bonjour <?php echo $_SESSION['login']; ?>.
	<a href="logout.php">Se deconnecter</a>
<?php } ?>

<?php
// FORMULAIRE SEND //

	// TEST POUR LE CRYPTAGE
/*$crypt = password_hash(trim(" test "), PASSWORD_DEFAULT);
var_dump(password_verify("test", $crypt));
*/
	if(isset($_POST['send'])){
		$date = time();
		$login = $_POST['login'];
		$email = $_POST['email'];
		$password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);

		$checkUser = $db->prepare("SELECT * FROM user WHERE login = :login");
		$checkUser->bindValue(":login", $login, PDO::PARAM_STR);
		$checkUser->execute();
		if(!$checkUser->rowCount()){
			$query= $db->prepare("INSERT INTO user(login, email, password, date) VALUES(:login, :email, :password, :date)");
			$query->bindValue(":login", $login, PDO::PARAM_STR);
			$query->bindValue(":email", $email, PDO::PARAM_STR);
			$query->bindValue(":password", $password, PDO::PARAM_STR);
			$query->bindValue(":date", $date, PDO::PARAM_INT);
			// On execute la requête et si elle fonction elle renvoie true
			if($query->execute()){
				$_SESSION['id'] = $db->lastInsertId();
				$_SESSION['login'] = $login;
				header("Location: ".$url);
			}
		}else {
			echo "L'utilisateur existe déjà.";
		}
	}
?>



<?php 
if(isset($_POST['loginForm'])){
	$login = $_POST['login'];
	$password = $_POST['password'];
	if(!empty($login) && !empty($password)){
		$query = $db->prepare("SELECT * FROM user WHERE login = :login");
		$query->bindValue(":login", $login, PDO::PARAM_STR);
		$query->execute();
		// On verifie que l'utilisateur existe ou pas (1 ou 0)
		if($query->rowCount()){
			$user = $query->fetch();
			$valid= password_verify($password, $user['password']);
			if($valid){
				if(isset($_POST['remember'])){
					$token = sha1(md5(uniqid().$_SERVER['REMOTE_ADDR']));
					setcookie('remember',$token, time()+60*60+24);
					$db->query('UPDATE FROM user SET token = $token WHERE id ='$user['id']);
				}
				$_SESSION['id'] = $user['id'];
				$_SESSION['login'] = $user['login'];
				header("Location: ".$url);
				echo "ONLINE";
			}else{
				echo "Le mot de passe n'est pas correct.";
			}
		}else{
			echo "L'utilisateur n'existe pas.";
		}
	}
}
?>

