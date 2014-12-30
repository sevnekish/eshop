<hr>
<div style = "display: inline-block">
	<p><a href="mailto:<?=$email?>"><?=$name?></a>@</p>
</div>
<div style = "display: inline-block; float: right;">
	<p>Оценил(а) в <?=$rate;?> <? ballCheck($rate);?>. <?=$date?></p>
</div>
<br><pre><?=$review?></pre>

<?php
	session_start();
//отображение кнопки Удалить
	if(isset($_SESSION['admin']))
	{
		echo '<p align="right"><a href = "' . $_SERVER['REQUEST_URI'] . "&del=" . $id . '">Удалить</a></p>';
	}
?>