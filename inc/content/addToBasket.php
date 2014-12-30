<?php
	require '../config.inc.php';
	require '../lib.inc.php';

	addToBasket();
	header('Location: ' . $_SERVER['HTTP_REFERER']);
	exit;
?>