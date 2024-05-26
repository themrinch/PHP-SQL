<?php
	session_start();
	
	// Выводим флеш-сообщение и удаляем его из сессии во избежание повторного показа 
	if (isset($_SESSION['flash'])) {
		echo $_SESSION['flash'];
		echo '<br>';
		unset($_SESSION['flash']);
	}
	if (!empty($_SESSION['auth'])) {
		echo "Вы зашли как " . $_SESSION['login'] ."!  Ваш статус " . $_SESSION['status'] . ".<br>";
	} else {
		echo "Пожалуйста, пройдите авторизацию на странице: ";
		echo '<br><a href="https://task/login.php">https://task/login.php</a>';
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<?php
			if (!empty($_SESSION['auth']) and ($_SESSION['status'] == 'admin')) {
				echo "<a href='https://task/admin.php'>ADMIN</a>";
			}	
		?>
	</head>
	<body>
		<p>Текст для любого пользователя</p>
		<?php
			if (!empty($_SESSION['auth'])) {
				echo 'Текст только для авторизованного пользователя';
		?>		
				<br>
				<a href="https://task/1.php">1</a><br>
				<a href="https://task/2.php">2</a><br>
				<a href="https://task/3.php">3</a><br>
				<a href="https://task/users.php">USERS</a><br>
				<a href="https://task/personalArea.php">PERSONAL AREA</a><br>
				<a href="https://task/delete.php">DELETE ACCOUNT</a><br>
				<a href="https://task/logout.php">LOG OUT</a>
		<?php	
			}	
		?>
		<p>Текст для любого пользователя</p>
	</body>
</html>
