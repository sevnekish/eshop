<?php
	sessionAdmin();
	logOut();
?>


<ul id = 'admin_menu'>
	<li><a href = "<? echo $_SERVER['SCRIPT_NAME'] . '?id=additem';?>">Добавление товара</a></li>
	<li><a href = "<? echo $_SERVER['SCRIPT_NAME'] . '?id=adduser';?>">Добавление пользователя</a></li>
	<li><a href = "<? echo $_SERVER['SCRIPT_NAME'] . '?id=orders';?>">Просмотр заказов</a></li>
	<li><a href = "<? echo $_SERVER['REQUEST_URI'] . '&logout';?>">Выход</a></li>
</ul>