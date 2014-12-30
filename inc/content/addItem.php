<?php
//подключение бд
	require 'inc/config.inc.php';
//безопасность
	sessionAdmin();
//вызываем функцию добавления товара в бд
	addItem();
?>

<form action = "<?=$_SERVER['REQUEST_URI'];?>" method="POST">
	<br>Бренд:
	<br><input type = "text" name = "brand">
	<br>Модель:
	<br><input type = "text" name = "model">
	<br>Характиристики:
	<br><textarea name = "charac"></textarea>
	<br>Описание:
	<br><textarea name = "desc"></textarea>
	<br>Ссылки на фотографии через ';' :
	<br><textarea name = "photos"></textarea>
	<br>Цена:
	<br><input type = "text" name = "price">
	<br>Количество товара на складе:
	<br><input type = "text" name = "instock">

	<br><input type="submit" name="add" value="Добавить">
</form>