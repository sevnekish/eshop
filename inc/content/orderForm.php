<?php
	//подключение бд
require "inc/config.inc.php";

	addOrder();
?>

<form action = "<?php echo $_SERVER['REQUEST_URI'];?>" method ="POST">
	<table>
		<tr>
			<td>Заказчик: </td>
			<td><input type = "text" name = "name"></td>
		</tr>
		<tr>
			<td>Email заказчика: </td>
			<td><input type = "text" name = "email"></td>
		</tr>
		<tr>
			<td>Телефон: </td>
			<td><input type = "text" name = "telephone"></td>
		</tr>
		<tr>
			<td>Адрес доставки: </td>
			<td><input type = "text" name = "address"></td>
		</tr>
		<tr>
			<td></td>
			<td><input type = "submit" name = "send" value="Заказать"></td>
		</tr>
	</table>
</form>