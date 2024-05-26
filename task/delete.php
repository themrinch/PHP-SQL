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
		if (!empty($_POST)) {
			if (!empty($_POST['password'])) {
			
				$id = $_SESSION['id']; // id юзера из сессии
				$query = "SELECT * FROM users WHERE id='$id'"; // получаем юзера по id из сессии
				$result = mysqli_query($link, $query);
				$user = mysqli_fetch_assoc($result);
					
				$hash = $user['password']; // соленый пароль из БД
					
				// Проверяем соответствие хеша из базы введенному паролю
				if (password_verify($_POST['password'], $hash)) {
					
					// Все ок, выполняем удаление
					
					$query = "DELETE FROM users WHERE id='$id'";
					mysqli_query($link, $query);
					
					// Добавляем в сессию сообщение о том, что пользователь удалил свой аккаунт
					$_SESSION['flash'] = 'Вы удалили свой аккаунт!';
					
					// Переходим на страницу с авторизацией
					$addr = 'login.php';
					header("Location: $addr");
					die();
				} else {
					// Старый пароль введен неверно, выведем сообщение
					echo '<script>alert("Gароль введен неверно. Попробуйте снова!");</script>';
				}
			} else {
				echo '<script>alert("Заполните поле \"пароль\".");</script>';
			}
		}	
?>
<form action="" method="POST">
	<label for="password">PASSWORD:</label><br>
	<input name="password" type='password'><br><br>
	<input type="submit" name="Отправить">
</form>	
<?php
	} else {
?>
	<p>Пожалуйста, пройдите авторизацию на странице:</p>
	<a href="https://task/login.php">https://task/login.php</a>	
<?php 
	}
?>