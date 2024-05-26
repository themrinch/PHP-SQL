<?php 

	session_start();

	if (isset($_SESSION['auth'])) {	
		echo "Вы зашли как " . $_SESSION['login'] ."! Ваш статус " . $_SESSION['status'] . ".";
		if ($_SESSION['status'] == 'admin') {
?>
<!DOCTYPE html>
	<html>
		<head>
			
		</head>
		<body>
			<style>
				table, td, th {
					border-collapse: collapse;
					border: 1px solid black;
				}
				td, th {
					padding: 10px 20px;
					text-align: center;
				}
				th {
					background: #DCDCDC;
					font-weight: bold;
				}
			</style>
			<table>
				<tr>
					<th>LOGIN</th>
					<th>STATUS</th>
					<th>CHANGE STATUS</th>
					<th>BAN STATUS</th>
					<th>CHANGE BAN STATUS</th>
					<th>CHANGE INFO</th>
					<th>CHANGE LOGIN</th>
					<th>CHANGE PASSWORD</th>
					<th>DELETE</th>
				</tr>
				<?php
					// Подключение к базе данных
					$host = 'task'; // имя хоста
					$user = 'root'; // имя пользователя
					$password = 'root'; // пароль
					$db_name = 'task'; // имя БД
					
					$link = mysqli_connect($host, $user, $password, $db_name);
					mysqli_query($link, "SET NAMES 'utf8'");
					
					if (isset($_GET['del_id'])) {
						$del_id = $_GET['del_id'];
						$query = "DELETE FROM users WHERE id = $del_id";
						mysqli_query($link, $query) or die(mysqli_error($link));
						$addr = 'admin.php';
						header("Location: $addr");
						die();
					}
					
					if (isset($_GET['st_id'])) {
						$st_id = $_GET['st_id'];
						$query = "SELECT * FROM users WHERE id = $st_id";
						$res = mysqli_fetch_assoc(mysqli_query($link, $query));
						if ($res['status_id'] == 2) {
							$status_id = 1;
							$status = 'admin';
						} else {
							$status_id = 2;
							$status = 'user';
						}	
						$query = "UPDATE users SET status_id='$status_id' WHERE id = $st_id";
						mysqli_query($link, $query) or die(mysqli_error($link));
						if ($st_id == $_SESSION['id']) {
							$_SESSION['status'] = $status;
						}
						$addr = 'admin.php';
						header("Location: $addr");
						die();
					}
					
					if (isset($_GET['ban_id'])) {
						$ban_id = $_GET['ban_id'];
						$query = "SELECT * FROM users WHERE id = $ban_id";
						$res = mysqli_fetch_assoc(mysqli_query($link, $query));
						if ($res['banned'] == 0) {
							$banned = 1;
						} else {
							$banned = 0;
						}	
						$query = "UPDATE users SET banned='$banned' WHERE id = $ban_id";
						mysqli_query($link, $query) or die(mysqli_error($link));
						$addr = 'admin.php';
						header("Location: $addr");
						die();
					}
					
					// Получение всех пользователей:
					$query = "SELECT * FROM users";
					$result = mysqli_query($link, $query) or die(mysqli_error($link));
					for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);
						
					// Вывод на экран
					$result = '';
					foreach ($data as $elem) {
						if ($elem['status_id'] == 1) {
							$color = 'red';
							$status = 'admin';
							$text = 'Сделать юзером';
						} else {
							$color = 'green';
							$status = 'user';
							$text = 'Сделать админом';
						}
						if ($elem['banned'] == 0) {
							$ban_st = 'Не забанен';
							$ban_text = 'Забанить';
						} else {
							$ban_st = 'Забанен';
							$ban_text = 'Разбанить';
							$color = 'fuchsia';
						}		
						$result .= '<tr>';
							
						$result .= '<td style="color: ' . $color . '">' . $elem['login'] . '</td>';
						$result .= '<td style="color: ' . $color . '">' . $status . '</td>';
						$result .= '<td><a href="?st_id=' . $elem['id'] . '">' . $text . '</a></td>';
						$result .= '<td style="color: ' . $color . '">' . $ban_st . '</td>';
						$result .= '<td><a href="?ban_id=' . $elem['id'] . '">' . $ban_text . '</a></td>';
						$result .= '<td><a href="changeContactInfo.php?new_info_id=' . $elem['id'] . '">изменить</a></td>';
						$result .= '<td><a href="changeLogin.php?new_login_id=' . $elem['id'] . '">изменить</a></td>';
						$result .= '<td><a href="changePassword.php?new_pwd_id=' . $elem['id'] . '">изменить</a></td>';
						$result .= '<td><a href="?del_id=' . $elem['id'] . '">удалить</a></td>';
						
						$result .= '</tr>';
					}
						
					echo $result;
				?>
			</table>
			<br>
			<a href='https://task/index.php'>НАЗАД</a>
		</body>
	</html>
<?php
		} else {
			echo '<br>Данная страница доступна только администраторам!<br>';
			echo "<a href='https://task/index.php'>НАЗАД</a>";
		}	
	} else {
?>
	<p>Пожалуйста, пройдите авторизацию на странице:</p>
	<a href="https://task/login.php">https://task/login.php</a>	
<?php 
	} 
?>