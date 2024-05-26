<?php 

	// Подключение к базе данных
	$host = 'task'; // имя хоста
	$user = 'root'; // имя пользователя
	$password = 'root'; // пароль
	$db_name = 'task'; // имя БД
	
	$link = mysqli_connect($host, $user, $password, $db_name);
	
	mysqli_query($link, "SET NAMES 'utf8'");

	if (isset($_GET['id'])) {	
		$id = $_GET['id'];
		
		// Формируем и отсылаем SQL запрос:
		$query = "SELECT * FROM users WHERE id = '$id'"; // получаем юзера по id
		$result = mysqli_query($link, $query);
		
		// Преобразуем ответ из БД в нормальный массив PHP:
		$user = mysqli_fetch_assoc($result);
		
		if(!empty($user)) {
			// Пользователь с таким id есть, выводим информацию
			$login = $user['login'];
			$first_name = $user['first_name'];
			$middle_name = $user['middle_name'];
			$second_name = $user['second_name'];
			$birthday = $user['birthday'];
			$registration_date = $user['registration_date'];
			$age = date_diff(date_create($birthday), date_create('now'))->y;
			if ($age == date('Y')) {
				$age = '';
			}	
			
			echo "LOGIN: $login<br>";
			echo "FIRST NAME: $first_name<br>";
			echo "MIDDLE NAME: $middle_name<br>";
			echo "SECOND NAME: $second_name<br>";
			echo "AGE: $age<br>";
			echo "REGISTRATION DATE: $registration_date<br><br>";
			echo "<a href='https://task/users.php'>USERS</a>";
		} else {
			$addr = 'users.php';
			header("Location: $addr");
			die();
		}	
	} else {
		$addr = 'users.php';
		header("Location: $addr");
		die();
	}
?>