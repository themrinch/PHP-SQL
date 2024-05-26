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
		<th>Users</th>
	</tr>
	<?php
		// Подключение к базе данных
		$host = 'task'; // имя хоста
		$user = 'root'; // имя пользователя
		$password = 'root'; // пароль
		$db_name = 'task'; // имя БД
		
		$link = mysqli_connect($host, $user, $password, $db_name);
		mysqli_query($link, "SET NAMES 'utf8'");
		
		// Получение всех пользователей:
		$query = "SELECT * FROM users";
		$result = mysqli_query($link, $query) or die(mysqli_error($link));
		for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);
			
		// Вывод на экран
		$result = '';
		foreach ($data as $elem) {
			$result .= '<tr>';
				
			$result .= '<td><a href="profile.php?id=' . $elem['id'] . '">' . $elem["login"] . '</a></td>';
				
			$result .= '</tr>';
		}
			
		echo $result;
	?>
</table>
<br>
<a href="https://task/index.php">INDEX</a>
