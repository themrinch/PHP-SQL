<?php
	// Подключение к базе данных
	$host = 'task'; // имя хоста
	$user = 'root'; // имя пользователя
	$password = 'root'; // пароль
	$db_name = 'task'; // имя БД
	
	$link = mysqli_connect($host, $user, $password, $db_name);
	
	mysqli_query($link, "SET NAMES 'utf8'");
	
	// Начинаем сессию, чтобы с ней работать
	session_start();
	
	// Функция для input в форме
	function input($name)
	{
		if (isset($_POST[$name])) {
			$value = $_POST[$name];
		} else {
			$value = '';
		}	
		return '<input name="' . $name . '" value="' . $value .'">';
	}
	
	// Функция для проверки даты
	function validateDate($date) {
		return (date('d.m.Y', strtotime($date)) == $date);
	}	
	
	//Если форма авторизации отправлена...
	if (!empty($_POST)) {
		if (!empty($_POST['password']) and !empty($_POST['login']) and !empty($_POST['confirm'])) {
			if ($_POST['password'] == $_POST['confirm']) {
				// Пароль и подтверждение совпадают
			
				// Пишем логин и пароль из формы в переменные для удобства работы:
				$login = $_POST['login'];
				$password = $_POST['password'];
				
				$birthday = $_POST['birthday'];
				$email = $_POST['email'];
				$date = date('Y-m-d');
				
				if (preg_match("/^[A-Za-z0-9]+$/", $login)) {
					// Логин содержит только латинские буквы и цифры
					if (preg_match("/^[0-9a-z!@#$%^&*]{8,25}$/i", $password)) {
						// Введен корректный пароль
						$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
						
						if ((filter_var($email, FILTER_VALIDATE_EMAIL)) or (empty($email))) {
							
							// Введен корректны email или не введен совсем
							if (validateDate($birthday) or (empty($birthday))) {
								
								// Дата рождение введена в верном формате или не введена совсем
								if (!empty($birthday)) {
									$birthday = date('Y-m-d', strtotime($birthday));
								}
								
								// Пробуем получить юзера с таким логином
								$query = "SELECT * FROM users WHERE login='$login'";
								$user = mysqli_fetch_assoc(mysqli_query($link, $query));
								
								// Если юзера с таким логином нет
								if (empty($user)) {
									
									// Формируем и отсылаем SQL запрос:
									$query = "INSERT INTO users SET login='$login', password='$password', 
																	birthday='$birthday', email='$email',
																	registration_date='$date', status_id=2,
																	banned=0";
									mysqli_query($link, $query);
									
									// Пишем в сессию пометку об авторизации
									$_SESSION['auth'] = true;
									
									// Записываем в сессию логин пользователя
									$_SESSION['login'] = $login;
									
									// Записываем в сессию статус пользователя
									$_SESSION['status'] = 'user';
									
									// Получаем id вставленной записи и пишем его в сессию
									$id = mysqli_insert_id($link);
									$_SESSION['id'] = $id;

									if ($id == 1) {
										// Если это первый пользователь, назначаем ему статус администратора
										$query = "UPDATE users SET status_id=1 WHERE id=$id";
										mysqli_query($link, $query);
										
										// Записываем в сессию статус администратора
										$_SESSION['status'] = 'admin';
									}	
									
									// После успешной регистрации переходим на страницу index.php
									$addr = 'index.php';
									header("Location: $addr");
									die();
								} else {
									// Логин занят, выведем сообщение об этом
									echo '<script>alert("Данный логин уже занят. Пожалуйста, выберите другой!");</script>';
								}
							} else {
								// Дата рождения введена в неверном формате, выводим сообщение
								echo '<script>alert("Укажите дату рождения в формате дд.мм.гггг!");</script>';
							}	
						} else {
							// Email введен в неверном формате, выводим сообщение
							echo '<script>alert("Email введен неверно!");</script>';
						}
					} else {
						// Пароль содержит не только латинские буквы, цифры и символы, 
						// и(или) имеет длину менее 8 символов или более 25 символов, выводим сообщение
						echo '<script>alert("Пароль может содержать только латинские буквы, цифры и символы. Длина пароля должна быть от 8 до 25 символов. Попробуйте снова!");</script>';
					}
				} else {
					// Логин содержит не только латинские буквы и цифры, выводим сообщение
					echo '<script>alert("Логин может содержать только латинские буквы и цифры, попробуйте снова!");</script>';
				}
			} else {
				// Пароль и подтверждение НЕ совпадают - выведем сообщение
				echo '<script>alert("Пароль и подтверждение пароля не совпадают. Попробуйте снова!");</script>';
			}	
		} else {
			echo '<script>alert("Заполните поля \"логин\", \"пароль\", \"подтверждение пароля\".");</script>';
		}
	}
?>
<form action="" method="POST"> 
	<label for='login'>LOGIN:</label><br> 
	<?php echo input('login'); ?><br> 
	<label for='password'>PASSWORD:</label><br> 
	<input name='password' type='password'><br>
	<label for='confirm'>CONFIRM PASSWORD:</label><br>	
	<input name='confirm' type='password'><br>
	<label for='birthday'>BIRTHDAY:</label><br> 
	<?php echo input('birthday'); ?><br>
	<label for='email'>EMAIL:</label><br> 
	<?php echo input('email'); ?><br><br>	
	<input type='submit' value='Отправить'> 
</form> 