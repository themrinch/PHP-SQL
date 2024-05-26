<?php
	
	// Начинаем сессию, чтобы с ней работать
	session_start();
	
	// Пользователь перестает быть авторизованным
	$_SESSION['auth'] = null;
	$_SESSION['login'] = null;
	$_SESSION['id'] = null;
	
	// Добавляем в сессию сообщение о том, что пользователь перестал быть авторизованным
	$_SESSION['flash'] = 'Вы вышли из аккаунта!';
	
	// Редирект на страницу login.php
	$addr = 'login.php';
	header("Location: $addr");
	die();
?>