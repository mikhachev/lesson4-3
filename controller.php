<?php

session_start();

include("config.php");
include("function.php");

$login = !empty($_SESSION['login']) ? $_SESSION['login'] : false;

if (empty($login)) {
    echo "<a href=$url_dir/regform.php>Войдите на сайт</a>";
    //header("$myBase"."/regform.php", true, 302);
    die;
}

$sql = "SELECT id FROM user WHERE login = :login";
$stm = $pdo->prepare($sql);
$stm -> bindParam(':login', $login, PDO::PARAM_STR);
$stm->execute();

$userId = $stm->fetchColumn();

$description = "";
if (empty($_GET['oper']))
{
    $oper = null;
} else
{
    $oper = (string)$_GET['oper'];
}

if ($oper == 'edit') 
{
   $button_save = 'Введите новое значение'; 
}else
{
   $button_save = 'Сохранить'; 
}

/*//
if (!isset($_GET['id']) && isset($_POST['save']) && !empty($_POST['description'])) {
    $description = (string)$_POST['description'];
    $sql = "INSERT INTO task (user_id, description, date_added) VALUES (:user_id, :description, NOW())";
    $stm = $pdo->prepare($sql);
    echo  "$user_id"." $description";
    $stm -> bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stm -> bindValue(':description', $description, PDO::PARAM_STR);
    $stm->execute();
    
    backHome(); 
}
*/
if (!isset($_GET['id']) && isset($_POST['save']) && !empty($_POST['description'])) {
    $description = (string)$_POST['description'];
    $sql = "INSERT INTO task (user_id, description, date_added) VALUES (?, ?, NOW())";
    $stm = $pdo->prepare($sql);
    $stm->execute([
        $userId,
        $description
    ]);
    
    backHome(); 
}

if (!empty($oper) && !empty($_GET['id'])) {
    $id = (int)$_GET['id'];
    
   
    if (!empty($_POST['description'])) {
        $description = $_POST['description'];
    
        $sql = "UPDATE task SET description = ? WHERE id = ? AND user_id = ?";
        $stm = $pdo->prepare($sql);
        $stm->execute([
            $description,
            $id,
            $userId
        ]);
        
        backHome(); 
    }
    
    if ($oper == 'edit') {
        $sql = "SELECT description FROM tasks WHERE id = ?";
        $stm = $pdo->prepare($sql);
        $stm->execute([$id]);
        
        $description = $stm->fetchColumn();
    }
    
     if ($oper == 'delete') {  
        $sql = "DELETE FROM task WHERE id = ? AND user_id = ?";
        $stm = $pdo->prepare($sql);
        $stm->execute([
            $id,
            $userId
        ]);
        
        backHome();
    }
    
   if ($oper == 'done') {
        $sql = "UPDATE task SET is_done = 1 WHERE id = ? AND (user_id = ? OR assigned_user_id = ?)";
        $stm = $pdo->prepare($sql);
        $stm->execute([
            $id,
            $userId,
            $userId
        ]);
        
        backHome();
    }
     
     if ($oper == 'undone') {
        $sql = "UPDATE task SET is_done = 0 WHERE id = ? AND (user_id = ? OR assigned_user_id = ?)";
        $stm = $pdo->prepare($sql);
        $stm->execute([
            $id,
            $userId,
            $userId
        ]);
        
        backHome();
    }
}

if (!empty($_POST['assign']) && !empty($_POST['assigned_user_id'])) {
    $formData = explode("_", $_POST['assigned_user_id']);
    $assignedUserId = (int)$formData[1];
    $taskId = (int)$formData[3];
    
    if (!empty($userId) && !empty($taskId)) {
        $sql = "UPDATE task SET assigned_user_id = ? WHERE id = ? AND user_id = ?";
        $stm = $pdo->prepare($sql);
        $stm->execute([
            $assignedUserId,
            $taskId,
            $userId
        ]);
        
         backHome(); 
    }
}

if (!empty($_POST['assign']) && !empty($_POST['assigned_user_id']))
{
    $formData = explode("_", $_POST['assigned_user_id']);
    $assignedUserId = (int)$formData[1];
    $taskId = (int)$formData[3];

    if (!empty($userId) && !empty($taskId)) {
        $sql = "UPDATE task SET assigned_user_id = ? WHERE id = ? AND user_id = ?";
        $stm = $pdo->prepare($sql);
        $stm->execute([
            $assignedUserId,
            $taskId,
            $userId
        ]);

         backHome();
    }
}

//
$sql = "SELECT t.*, u.login, u2.login author
        FROM task t
        LEFT JOIN user u ON t.assigned_user_id = u.id
        LEFT JOIN user u2 ON t.user_id = u2.id
        WHERE user_id = ?";
$stm = $pdo->prepare($sql);
$stm->execute([
    $userId
]);

$myTasks = $stm->fetchAll();

$sql = "SELECT t.*, u.login, u2.login author
        FROM task t
        LEFT JOIN user u ON t.assigned_user_id = u.id
        LEFT JOIN user u2 ON t.user_id = u2.id
        WHERE assigned_user_id = ? ";
$stm = $pdo->prepare($sql);
$stm->execute([
    $userId
]);

$myAssignedTasks = $stm->fetchAll();

//

$sql = "SELECT * FROM user WHERE id != ?";
$stm = $pdo->prepare($sql);
$stm->execute([
    $userId
]);

$userList = $stm->fetchAll();
$user = [];

foreach ($userList as $item) {
    $user[$item['id']] = $item['login'];
}


?>