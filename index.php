<?php

include("controller.php");
//echo $url_dir;

?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Select из нескольких таблиц</title>
</head>
<body>
  <style>
    table td, table th 
    {
    border: 1px solid #ccc;
    padding: 5px;
    }
  </style>
  <h1>Список задач, которые Вам (<?=$login?>) необходимо решить:</h1>
    <div>
      <form method="POST">
        <input type="text" name = "description" placeholder = "Задача" value = "<?=$description?>" />
        <input type="submit" name = "save" value = "<?=$button_save?>"  />
      </form>
    </div>
    
     <table>
   
      <tr>
        <th>Описание задачи</th>
          <th>Дата добавления</th>
          <th>Статус</th>
          <th>Действия</th>
          <th>Ответственный</th>
          <th>Автор</th>
          <th>Делегировать</th>
       </tr>
       
<?php showTasks($myTasks, $userId, $user); ?>
    </table>
    <p><strong>Также, посмотрите, что от Вас требуют другие люди:</strong></p>
    <table>
      <tr>
        <th>Описание задачи</th>
        <th>Дата добавления</th>
        <th>Статус</th>
        <th>Действия</th>
        <th>Ответственный</th>
        <th>Автор</th>
      </tr>
<?php showTasks($myAssignedTasks, $userId);?>
   </table><br>
<?php echo "<a href=$url_dir/logout.php>Выход</a>";?>

</body>
</html>