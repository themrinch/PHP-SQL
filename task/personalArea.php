<?php
	// Подключение к базе данных
	$host = 'task'; // имя хоста
	$user = 'root'; // имя пользователя
	$password = 'root'; // пароль
	$db_name = 'task'; // имя БД
	
	$link = mysqli_connect($host, $user, $password, $db_name);
	
	mysqli_query($link, "SET NAMES 'utf8'");
	
	session_start();
		
	
	if (isset($_SESSION['auth'])) {
	
		$id = $_SESSION['id'];
		$query = "SELECT * FROM users WHERE id=" . $id;
		$result = mysqli_query($link, $query);
		$user = mysqli_fetch_assoc($result);
		
		if (!empty($user)) {
			$login = $user['login'];
			$first_name = $user['first_name'];
			$second_name = $user['second_name'];
			$middle_name = $user['middle_name'];
			$birthday = $user['birthday'];
			$number = $user['number'];
			$email = $user['email'];
			
			echo "LOGIN: $login<br>";
			echo "FIRST NAME: $first_name<br>";
			echo "MIDDLE NAME: $middle_name<br>";
			echo "SECOND NAME: $second_name<br>";
			echo "BIRTHDAY: $birthday<br>";
			echo "PHONE NUMBER: $number<br>";
			echo "EMAIL: $email<br><br>";
			
			echo "<a href='https://task/changeContactInfo.php'>СМЕНИТЬ КОНТАКТНУЮ ИНФОРМАЦИЮ</a><br>";
			echo "<a href='https://task/changeLogin.php'>СМЕНИТЬ ЛОГИН</a><br>";
			echo "<a href='https://task/changePassword.php'>СМЕНИТЬ ПАРОЛЬ</a><br>";
			echo "<a href='https://task/index.php'>НАЗАД</a>";
			
		}
	} else {	
?>
	<p>Пожалуйста, пройдите авторизацию на странице:</p>
	<a href="https://task/login.php">https://task/login.php</a>	
<?php 
	} 
?>