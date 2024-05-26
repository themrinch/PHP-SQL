<?php
	// Подключение к базе данных
	$host = 'task'; // имя хоста
	$user = 'root'; // имя пользователя
	$password = 'root'; // пароль
	$db_name = 'task'; // имя БД
	
	$link = mysqli_connect($host, $user, $password, $db_name);
	
	mysqli_query($link, "SET NAMES 'utf8'");
	
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
	
	// Функция для проверки номера телефона
	function validateNumber($number) {
		$patt = '~' .
			'^(?:\+7|8)\d{10}$|' .
			'^8[\s-]\d{3}-\d(?:-\d{3})+$|' .
			'^\s?8\s?\(\d{4}\)\s?\d{2}(?:-\d{2}){2}$|' .
			'^8-\d{3}-\d{7}$|' .
			'^8\s?\(\d{3}\)\s?\d{2}\s?\d{3}\s?\d{2}$|' .
			'^8-\d{3}\s?\d{2}\s?\d{3}\s?\d{2}$|' .
			'^8\s?\(\d{3}\)\s?-\s?\d{3}(?:\s?-\s?\d{2}){2}$|' .
			'^\+\s?7(?:\s?\d{3}){2}\s?\d{4}$|' .
			'^8\s?\(\s?\d{3}\s?\)\s?\d{7}$|' .
			'^8(?:\s?\d{3}){2}\s?\d{4}$' .
		'~';
		return preg_match($patt, $number);
	}	
	
	if (isset($_SESSION['auth'])) {
		
		if (isset($_GET['new_info_id'])) {
			$id = $_GET['new_info_id'];
			$addr = 'admin.php';
		} else {
			$id = $_SESSION['id'];
			$addr = 'personalArea.php';
		}
		$query = "SELECT * FROM users WHERE id='$id'";
		$result = mysqli_query($link, $query);
		$user = mysqli_fetch_assoc($result);
		if (!empty($_POST)) {
			if (!empty($_POST['first_name']) or !empty($_POST['second_name']) or !empty($_POST['middle_name']) or !empty($_POST['birthday']) or !empty($_POST['number']) or !empty($_POST['email'])) {
				$first_name = $_POST['first_name'];
				$second_name = $_POST['second_name'];
				$middle_name = $_POST['middle_name'];
				$birthday = $_POST['birthday'];
				$number = $_POST['number'];
				$email = $_POST['email'];
				
				if (validateDate($birthday) or (empty($birthday))) {
					if (!empty($birthday)) {
						$birthday = date('Y-m-d', strtotime($birthday));
					}
					
					if (validateNumber($number) or (empty($number))) {
					
						if ((filter_var($email, FILTER_VALIDATE_EMAIL)) or (empty($email))) {
							
							$query = "UPDATE users SET
									first_name = IFNULL(NULLIF('$first_name', ''), first_name),
									second_name = IFNULL(NULLIF('$second_name', ''), second_name),
									middle_name = IFNULL(NULLIF('$middle_name', ''), middle_name),
									birthday = IFNULL(NULLIF('$birthday', ''), birthday),
									number = IFNULL(NULLIF('$number', ''), number),
									email = IFNULL(NULLIF('$email', ''), email)
									WHERE id = $id";
							mysqli_query($link, $query) or die(mysqli_error($link));
							header("Location: $addr");
							die();
						} else {
							echo '<script>alert("Email введен неверно!");</script>';
						}
					} else {
						echo '<script>alert("Номер телефона введен неверно!");</script>';
					}	
				} else {
					echo '<script>alert("Укажите дату рождения в формате дд.мм.гггг!");</script>';
				}
			} else {
				echo '<script>alert("Заполните хотя бы одно поле!");</script>';
			}
		}
?>
<form action="" method="POST"> 
	<label for='first_name'>FIRST NAME:</label><br> 
	<?php echo input('first_name'); ?><br>
	<label for='second_name'>SECOND NAME:</label><br> 
	<?php echo input('second_name'); ?><br>
	<label for='middle_name'>MIDDLE NAME:</label><br> 
	<?php echo input('middle_name'); ?><br>
	<label for='birthday'>BIRTHDAY:</label><br> 
	<?php echo input('birthday'); ?><br>
	<label for='number'>PHONE NUMBER:</label><br> 
	<?php echo input('number'); ?><br>
	<label for='email'>EMAIL:</label><br> 
	<?php echo input('email'); ?><br><br>	
	<input type='submit' value='Отправить'> 
</form>
<?php	
	} else {	
?>
	<p>Пожалуйста, пройдите авторизацию на странице:</p>
	<a href="https://task/login.php">https://task/login.php</a>	
<?php 
	} 
?>