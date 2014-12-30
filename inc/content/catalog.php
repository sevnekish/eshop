<?php
	require 'inc/config.inc.php';

	addToBasket();
?>

<form><p>Сортировать по:
	<select name = "sort" size = 1 onchange = "window.location=document.forms[0].sort.options[document.forms[0].sort.selectedIndex].value">
		<option value = "#">-</option>
		<option value = "<?=$_SERVER['SCRIPT_NAME'].'?id=catalog&page=1&sort=baz';?>">бренду</option>
		<option value = "<?=$_SERVER['SCRIPT_NAME'].'?id=catalog&page=1&sort=paz';?>">цене по убыванию</option>
		<option value = "<?=$_SERVER['SCRIPT_NAME'].'?id=catalog&page=1&sort=pza';?>">цене по возрастанию</option>
	</select></p>
</form>

<table id = "catalog">
	<tr>
		<th id = "brandW">Бренд</th><th id = "modelW">Модель</th><th id = "characW">Характеристики</th><th id = "description">Описание</th>
		<th id = "priceW">Цена</th><th id = "instockW">Наличие</th><th id = "basketW">В корзину</th>
	</tr>
	<?php
	//выводим таблицу товаров и инициализируем массив с ссылками для стрелок и номеров страниц
		$arrows = pageMaker('inc/content/catalogView.php');
	?>
</table>
<div align='center' class = 'pages'>
	<?php
	//получаем значение стрелок и номеров со ссылками на страницы
	$page_first = $arrows['first'];
	$page2first = $arrows['2first'];
	$page1first = $arrows['1first'];
	$cur_page = $arrows['cur'];
	$page1end = $arrows['1end'];
	$page2end = $arrows['2end'];
	$page_last = $arrows['last'];

	 echo $page_first . $page2first . $page1first . '<span>' .$cur_page . '</span>' . $page1end . $page2end . $page_last;
	 ?>
</div>