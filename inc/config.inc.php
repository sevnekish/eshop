<?php
//переменные содержащие данные для доступа в базу данных
	define('DB_HOST', 'localhost');
	define('DB_LOGIN', 'dbadmin');
	define('DB_PASS', 'pass43w9ord');
	define('DB_NAME', 'eshopdb');

//инициализация массива с содержимым корзины
	$basket = array();
//инициализация количества товаров в корзине
	$count = 0;
//подключение к БД
	$link = mysqli_connect(DB_HOST, DB_LOGIN, DB_PASS, DB_NAME) or die(mysqli_connect_error());
	
//настройка кодировки
	mysqli_query($link, "SET NAMES 'utf8'");
	mysqli_query($link, "SET CHARACTER SET 'utf8'");
	mysqli_query($link, "SET SESSION collation_connection = 'utf8_general_ci'");

//инициализация корзины
	basketInit();
?>