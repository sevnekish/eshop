<?php
//подключенние проверки сессии

///подключение БД
	require 'inc/config.inc.php';
//вызов Функции сохранения отзыва в бд + сопутствующие проверки
	saveReview();
//вызов функции удаления отзыва
	deleteReview();
?>
<p><b>Оставить отзыв: </b></p>
<form action = "<?php echo $_SERVER['REQUIRED_URI'];?>" method = "POST">
	<table>
		<tr>
			<td><b>Ваше имя: </b></td>
			<td><input type = "text" name = "name" maxlength= "55"></td>
		</tr>
		<tr>
			<td><b>Ваш email: </b></td>
			<td><input type = "text" name = "email" maxlength = "55"></td>
		</tr>
		</tr>
		<tr>
			<td><b>Отзыв: </b></td>
			<td><textarea name = "review" maxlength = "250"></textarea></td>
		</tr>
		<tr>
			<td><b>Ваша оценка: </b></td>
			<td>
				<select name = 'rate'>
					<option value = '5'>5</option>
					<option value = '4'>4</option>
					<option value = '3'>3</option>
					<option value = '2'>2</option>
					<option value = '1'>1</option>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="2">Здесь будет каптча</td>
		</tr>
		<tr>
			<td></td><td><input type = "submit" value = "Отправить"></td>
		</tr>
	</table>
</form>
<?php
//вызов функции вывода отзывов, принимающую путь к представлению отзыва в html
	showReview('inc/content/reviewView.php', 3);
	mysqli_close($link);
?>
