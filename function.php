<?php

function login($login)
{
    $_SESSION['login'] = $login;
    header("Location: /emikhachev/lesson4-3/index.php");
}

function logout()
{
    //unset($_SESSION['login']);
    session_destroy();
    header("Location: /emikhachev/lesson4-3/index.php");
}

function showTasks($tasks, $userId, $users = [])
{
    echo "<table>";
    echo "
      <tr>
        <th>Описание задачи</th>
          <th>Дата добавления</th>
          <th>Статус</th>
          <th>Действия</th>
          <th>Ответственный</th>
          <th>Автор</th>
          ";
    if (!empty($users)) {
        echo "<th>Делегировать</th>\n";
    }
    echo "</tr>\n";
    
    foreach ($tasks as $row) {
        echo "<tr>\n";
        echo "  <td>" . $row['description'] . "</td>\n";
        echo "  <td>" . $row['date_added'] . "</td>\n";
        echo "  <td>" . ($row['is_done'] ? "Выполнено" : "В процессе") . "</td>\n";
       if (!$row['is_done']){
	   	 echo "  <td>
            <a href='?id=" . $row['id'] . "&oper=edit'>Изменить</a>&nbsp;";
	   }else{
	   	 echo "  <td>  &nbsp;";
	   }
       
        if (empty($row['assigned_user_id']) || $row['assigned_user_id'] == $userId)
        {
            if ($row['is_done'])
            {
				echo "<a href='?id=" . $row['id'] . "&oper=undone'>Вернуть на доработку</a>&nbsp;";
			}else
			{
				echo "<a href='?id=" . $row['id'] . "&oper=done'>Выполнить </a>&nbsp;";
			}
            
        }
        
        echo "<a href='?id=" . $row['id'] . "&oper=delete'>Удалить</a>
        </td>\n";
        
        echo "  <td>";
        if (!empty($row['assigned_user_id'])) {
            echo $row['login'];
        } else {
            echo "Вы";
        }
        echo "</td>\n";
        echo "  <td>" . $row['author'] . "</td>\n";
        if (!empty($users)) {
            echo "  <td>";
            echo "<form method='POST'>";
            echo "  <select name='assigned_user_id'>";
            foreach ($users as $id => $login) {
                echo "<option value='user_" . $id . "_task_" . $row['id'] . "'>" . $login . "</option>";
            }
            echo "  </select>";
            echo "  <input type='submit' name='assign' value='Делегировать' />";
            echo "</form>";
            echo "</td>\n";
        }
        echo "</tr>\n";
    }
    echo "</table>";
}