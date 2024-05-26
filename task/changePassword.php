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
		if (isset($_GET['new_pwd_id']) and $_SESSION['status'] == 'admin') {
			$id = $_GET['new_pwd_id'];
			$addr = 'admin.php';
			$admin = True;
		} else {
			$id = $_SESSION['id'];
			$addr = 'personalArea.php';
			$admin = False;
		}
		if (!empty($_POST)) {
			if (!empty($_POST['old_password'] or $admin) and !empty($_POST['new_password']) and !empty($_POST['confirm'])) {	
			
				$query = "SELECT * FROM users WHERE id='$id'"; // получаем юзера по id
				$result = mysqli_query($link, $query);
				$user = mysqli_fetch_assoc($result);
				
				$hash = $user['password']; // соленый пароль из БД
				
				// Проверяем соответствие хеша из базы введенному старому паролю
				if (password_verify($_POST['old_password'], $hash) or $admin) {
					
					// Проверяем новый пароль и его подтверждение
					if ($_POST['new_password'] == $_POST['confirm']) {
						
						// Проверяем новый пароль на корректность
						if (preg_match("/^[0-9a-z!@#$%^&*]{8,25}$/i", $_POST['new_password'])) {
						
							// Все ок, меняем пароль...						
							// Хеш нового пароля
							$newPasswordHash = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
							
							// Выполним UPDATE запрос
							$query = "UPDATE users SET password='$newPasswordHash' WHERE id='$id'";
							mysqli_query($link, $query);
							
							header("Location: $addr");
							die();
						} else {
							echo '<script>alert("Пароль может содержать только латинские буквы, цифры и символы. Длина пароля должна быть от 8 до 25 символов. Попробуйте снова!");</script>';
						}	
					} else {
						// Пароль и подтверждение НЕ совпадают - выведем сообщение
						echo '<script>alert("Новый пароль и его подтверждение не совпадают. Попробуйте снова!");</script>';
					}	
				} else {
					// Старый пароль введен неверно, выведем сообщение
					echo '<script>alert("Старый пароль введен неверно. Попробуйте снова!");</script>';
				}
			} else {
				echo '<script>alert("Заполните поля \"старый пароль\", \"новый пароль\", \"подтверждение нового пароля\".");</script>';
			}
		}
?>
<form action="" method="POST">
<?php 
		if (!($admin)) {
?>
	<label for='old_password'>OLD PASSWORD:</label><br>
	<input name="old_password" type='password'><br>
<?php 
		}
?>
	<label for='new_password'>NEW PASSWORD:</label><br>
	<input name="new_password" type='password'><br>
	<label for='confirm'>CONFIRM NEW PASSWORD:</label><br>
	<input name="confirm" type='password'><br><br>
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
