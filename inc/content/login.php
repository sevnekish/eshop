<?php
	require '../lib.inc.php';
	require '../config.inc.php';

	session_start();
	header("HTTP/1.0 401 Unauthorized");

	if(sendIsFine(login, password))
	{
		$login = cleanStr($_POST['login']);
		$password = cleanStr($_POST['password']);
	//переменная страницы на которую заходил, но к ней не было доступа
		$ref = cleanStr($_GET['ref']);
		global $link;
	//если $ref нет
		if(!$ref)
			$ref = $_SERVER['SCRIPT_NAME'];

		if($login and $password)//возможно надо, чтобы не были NULL
		{
			if($result = userExists($login))
			{

				$sql = "SELECT hash, salt, i, rights FROM users";

				$result = mysqli_query($link, $sql) or die(mysqli_error($link));
				$result = mysqli_fetch_assoc($result);

				$hash = $result['hash'];
				$salt = $result['salt'];
				$iterations = $result['i'];
				$admin = $result['rights'];
				if((genHash($password, $salt, $iterations) == $hash) && ($admin == 'admin'))
				{
					$_SESSION['admin'] = true;
					header("Location: $ref");
					exit;
				}
				else
					echo 'Неправильный логин или пароль';
			}
			else
				echo 'Неправильный логин или пароль';
		}

	}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>Авторизация</title>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
</head>
<body>
<h1>Авторизация</h1>
<form action="<?= $_SERVER['REQUEST_URI']?>" method="post">
	<table>
		<tr>
			<td>Логин</td>
			<td><input type="text" name="login"/></td>
		</tr>
		<tr>
			<td>Пароль</td>
			<td><input type="text" name="password"/></td>
		</tr>
		<tr>
			<td></td>
			<td><button type="submit">Войти</button></td>
		</tr>
	</table>
</form>
</body>
</html>
