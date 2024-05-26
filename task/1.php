<?php 

	session_start();

	if (isset($_SESSION['auth'])) {	
		echo "Вы зашли как " . $_SESSION['login'] ."!";
?>
<!DOCTYPE html>
	<html>
		<head>
			
		</head>
		<body>
			<p>Текст только для авторизованного пользователя</p>
		</body>
	</html>
<?php
	} else {
?>
	<p>Пожалуйста, пройдите авторизацию на странице:</p>
	<a href="https://task/login.php">https://task/login.php</a>	
<?php 
	} 
?>