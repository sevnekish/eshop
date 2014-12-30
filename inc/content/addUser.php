<?php
//подключение бд
	require "inc/config.inc.php";
//безопасность
	sessionAdmin();
//генерируем соль
	$salt = genSalt();
//вызываем функцию добавления нового пользователя в бд
	$result = addUser(login, password, salt, iteration, rights);
?>
<p><?=$result;?></p>
<form action ="<?=$_SERVER['REQUEST_URI'];?>" method="POST">
	<table>
		<tr>
			<td>Логин: </td>
			<td><input type = "text" name = "login" maxlength="20"></td>
		</tr>
		<tr>
			<td>Пароль: </td>
			<td><input type = "text" name = "password"></td>
		</tr>
		<tr>
			<td>Соль: </td>
			<td><input type = "text" name = "salt" value="<?=$salt;?>"></td>
		</tr>
		<tr>
			<td>Число интераций: </td>
			<td><input type = "text" name = "iteration" max="3"></td>
		</tr>
		<tr>
			<td>Права: </td>
			<td>
				<select name = "rights">
					<option value = "user">пользователя</option>
					<option value = "admin">администратора</option>
				</select>
			</td>
		</tr>
		<tr>
			<td></td>
			<td><input type = "submit" name = "add" value = "Добавить"></td>
		</tr>
	</table>
</form>