<?php
	require 'inc/lib.inc.php';
	require 'inc/config.inc.php';


//буферизация
	ob_start();

//определяем title и header
	$title_header = titleHeaderSet();
	$id = $title_header['id'];
	$title = $title_header['title'];
	$headerCont = $title_header['header'];

?>
<!DOCTYPE HTML PUBLIC " -//w3c//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<link type="text/css" rel="stylesheet" href="inc/css/style.css"/>
		<title><?=$title?></title>
	</head>

	<body>

	<!--header-->
	<div id = 'header'>
		<div id = "basket">
			<a href = "index.php?id=basket">Корзина: <? echo $count;//здесь будет значение из куки ?></a>
		</div>
		<div id = "registration">
			<a href = "">Войти/Регистрация</a>
		</div>
	</div>
	<!--/header-->

	<!--content-->
	<div id = 'content'>
		<h2 class="headerCont"><?=$headerCont?></h2>
		<?php
		//определяем содержимое content
			$content = contentSet($id);
			include "$content";
		?>
	</div>
	<!--/content-->

	<!--menu-->
	<div id = 'menu'>
		<ul>
			<li><a href = 'index.php'>Главная</a></li>
			<li><a href = 'index.php?id=contacts'>Контакты</a></li>
			<li><a href = 'index.php?id=catalog'>Каталог</a></li>
			<li><a href = 'index.php?id=reviews'>Отзывы</a></li>
		</ul>
	</div>
	<!--/menu-->

	<!--footer-->
	<div id = 'footer'>
		<div id = 'administration'>
			<a href="index.php?id=admin">Администрирование</a>
		</div>
	</div>
	<!--/footer-->

	</body>
</html>