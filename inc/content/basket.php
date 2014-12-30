<?php
	deleteItemFromBasket();
?>

<table id = "catalog">
	<tr>
		<th>N п/п</th>
		<th>Бренд</th>
		<th>Модель</th>
		<th>Цена</th>
		<th>Количество</th>
		<th>Удалить</th>
	</tr>
<?php
	showBasket('inc/content/basketView.php');
?>

</table>

<p>Сумма заказа: <?=$total_price;?></p>


<div align="left">
	<input type = 'button' value="Оформить заказ" onclick="location.href='index.php?id=orderform'">
</div>