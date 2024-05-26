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
		if (isset($_GET['new_login_id']) and $_SESSION['status'] == 'admin') {
			$id = $_GET['new_login_id'];
			$addr = 'admin.php';
			$admin = True;
		} else {
			$id = $_SESSION['id'];
			$addr = 'personalArea.php';
			$admin = False;
		}
		if (!empty($_POST)) {
			if (!empty($_POST['new_login']) and (!empty($_POST['password']) or $admin)) {	

				$query = "SELECT * FROM users WHERE id='$id'"; // получаем юзера по id
				$result = mysqli_query($link, $query);
				$user = mysqli_fetch_assoc($result);
				
				$hash = $user['password']; // соленый пароль из БД
				
				// Проверяем соответствие хеша из базы введенному старому паролю
				if (password_verify($_POST['password'], $hash) or $admin) {
					
					// Пишем логин из формы в переменную для удобства работы:
					$new_login = $_POST['new_login'];
					
					// Получаем логин пользователя
					$old_login = $user['login'];
					
					if ($new_login != $old_login) {
						// Новый введенный логин отличен от старого логина пользователя
					
						if (preg_match("/^[A-Za-z0-9]+$/", $new_login)) {
							
							// Новый логин содержит только латинские буквы и цифры
							
							// Пробуем получить юзера с таким логином
							$query = "SELECT * FROM users WHERE login='$new_login'";
							$existing_user = mysqli_fetch_assoc(mysqli_query($link, $query));
							
							// Если юзера с таким логином нет
							if (empty($existing_user)) {
								// Формируем и отсылаем SQL запрос:
								$query = "UPDATE users SET login='$new_login' WHERE id = $id";
								mysqli_query($link, $query) or die(mysqli_error($link));
								
								if (empty($_GET['new_login_id'])) {
									// Записываем в сессию новый логин пользователя
									$_SESSION['login'] = $new_login;
								}
								// После успешной смены логина возвращаемся на страницу личного кабинета
								header("Location: $addr");
								die();
							
							} else {
								// Логин занят, выведем сообщение об этом
								echo '<script>alert("Данный логин уже занят. Пожалуйста, выберите другой!");</script>';
							}
						} else {
							// Новый логин содержит не только латинские буквы и цифры, выводим сообщение
							echo '<script>alert("Логин может содержать только латинские буквы и цифры, попробуйте снова!");</script>';
						}
					} else {
						// Новый логин повторяет текущий, выводим сообщение
						echo '<script>alert("Пожалуйста, выберите логин, отличный от текущего!");</script>';
					}
				} else {
					// Старый пароль введен неверно, выведем сообщение
					echo '<script>alert("Пароль введен неверно. Попробуйте снова!");</script>';
				}
			} else {
				echo '<script>alert("Заполните поля \"новый логин\", \"пароль\".");</script>';
			}
		}
?>
<form action="" method="POST">
	<label for="new_login">NEW LOGIN:</label><br>
	<input name="new_login"><br>
<?php 
		if (!($admin)) {
?>
	<label for='password'>PASSWORD:</label><br>
	<input name="password" type='password'>
<?php 
		}
?>
	<br><br>
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