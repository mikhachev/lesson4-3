<?php

session_start();

include("config.php");
include("function.php");


//
if (isset($_POST['login']) and isset($_POST['password'])){
    $login = trim($_POST['login']);
    $password = trim($_POST['password']);
    $msg = "";
} else
{
    $msg = "Зарегистируйтесь или введите свои учетные данные";
}

if (isset($_POST['newuser'])) {
    if (!empty($login) && !empty($password)) {
        $hashedPassword = md5($password);

        $sql = "SELECT login FROM user WHERE login = ?";
        $stm = $pdo->prepare($sql);
        $stm->execute([
            $login
        ]);

        if (empty($stm->fetchColumn())) {
            $sql = "INSERT INTO user (login, password) VALUES (?, ?)";
            $stm = $pdo->prepare($sql);
            $stm->execute([
                $login,
                $hashedPassword
            ]);

            login($login);
        } else {
            $msg = "Такой пользователь уже существует в базе данных.";
        }
    } else {
        $msg = "Вы ввели не все данные.";
    }
}

if (isset($_POST['sign_in'])) {
    if (!empty($login) && !empty($password)) {
        $hashedPassword = md5($password);

        $sql = "SELECT login FROM user WHERE login = ? AND password = ?";
        $stm = $pdo->prepare($sql);
        $stm->execute([
            $login,
            $hashedPassword
        ]);

        if (!empty($stm->fetchColumn())) {

            login($login);
        } else {
            $msg = "Пользователь не существует, или неверный пароль.";
        }
    } else {
        $msg = "Ошибка входа. Введите все необходимые данные.";
    }
}

?>

<p><?=$msg?></p>

<form method="POST">
    <input type="text" name="login" placeholder="Логин" />
    <input type="password" name="password" placeholder="Пароль" />
    <input type="submit" name="sign_in" value="Вход" />
    <input type="submit" name="newuser" value="Регистрация" />
</form>