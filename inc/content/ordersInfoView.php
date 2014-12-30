<?php
?>
<hr>
<h2>Заказ номер: <?php echo $order_id;?></h2>
<p><b>Заказчик</b>:  <?php echo $name;?></p>
<p><b>Email</b>: <?php echo $email;?></p>
<p><b>Телефон</b>: <?php echo $telephone;?></p>
<p><b>Адрес доставки</b>: <?php echo $address;?></p>
<p><b>Дата размещения заказа</b>: <?php echo $date;?></p>

<h3>Купленные товары:</h3>
<table id = "catalog">
	<tr>
		<th>N п/п</th>
		<th>Бренд</th>
		<th>Модель</th>
		<th>Цена</th>
		<th>Количество</th>
	</tr>