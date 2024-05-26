<?php
	// Подключение к базе данных
	$host = 'task'; // имя хоста
	$user = 'root'; // имя пользователя
	$password = 'root'; // пароль
	$db_name = 'task'; // имя БД
	
	$link = mysqli_connect($host, $user, $password, $db_name);
	
	mysqli_query($link, "SET NAMES 'utf8'");
	
	session_start();
	
	if (isset($_SESSION['flash'])) {
		echo $_SESSION['flash'];
		echo '<br>';
		unset($_SESSION['flash']);
	}
	
	//Если форма авторизации отправлена...
	if (!empty($_POST['password']) and !empty($_POST['login'])) {
		
		// Пишем логин и пароль из формы в переменные для удобства работы:
		$login = $_POST['login'];
		
		// Формируем и отсылаем SQL запрос:
		$query = "SELECT *, statuses.name as status FROM users
		LEFT JOIN statuses ON users.status_id = statuses.id
		WHERE login='$login'"; // получаем юзера по логину
		$result = mysqli_query($link, $query);
		
		// Преобразуем ответ из БД в нормальный массив PHP:
		$user = mysqli_fetch_assoc($result);
		
		if(!empty($user)) {
			// Пользователь с таким логином есть, теперь надо проверять пароль...
			$hash = $user['password']; // соленый пароль из БД
			
			// Проверяем соответствие хеша из базы введенному паролю
			if (password_verify($_POST['password'], $hash)) {
				
				// Если пользователь не забанен
				if ($user['banned'] != 1) {
					// Пользователь прошел авторизацию, запишем в сессию пометку об этом
					$_SESSION['auth'] = true;
					
					// Выводим сообщение об успешной авторизации пользователя
					//echo '<script>alert("Пользователь найден.");</script>';
					
					// Добавляем флеш-сообщение об успешной авторизации в сессию
					$_SESSION['flash'] = 'Авторизация прошла успешно!';
					
					// Записываем в сессию логин пользователя
					$_SESSION['login'] = $login;
					// Записываем в сессию id пользователя, полученный из БД
					$_SESSION['id'] = $user['id'];
					// Записываем в сессию статус пользователя, полученный из БД
					$_SESSION['status'] = $user['status'];
					
					// Редирект на страницу index.php
					$addr = 'index.php';
					header("Location: $addr");
					
					// В случае успешной авторизации форма для ввода логина и пароля не отображается на экране
					die();
				} else {
					echo '<script>alert("Ваш профиль находится в бане!");</script>';
				}	
			} else {
				// Пароль не подошел, выведем сообщение
				echo '<script>alert("Введен неверный логин или пароль. Попробуйте снова!");</script>';
			}
		} else {
			// Пользователя с таким логином нет, выведем сообщение
			echo '<script>alert("Введен неверный логин или пароль. Попробуйте снова!");</script>';
		}
	}
?>
<form action='' method='POST'> 
	<label for='login'>LOGIN:</label><br> 
	<input name='login'><br> 
	<label for='password'>PASSWORD:</label><br> 
	<input name='password' type='password'><br><br> 
	<input type='submit' value='Отправить'> 
</form>
<br>
<a href="https://task/register.php">REGISTRATION</a> 