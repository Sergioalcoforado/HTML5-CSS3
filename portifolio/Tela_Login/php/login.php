<?php

include_once('connection.php');

$user_email = filter_input (INPUT_POST, 'user_email', FILTER_SANITIZE_SPECIAL_CHARS);
$user_password = filter_input (INPUT_POST, 'user_password', FILTER_SANITIZE_SPECIAL_CHARS);

$sql_logar = mysqli_query($conn, "SELECT * FROM tbl_users WHERE user_email= '$user_email' LIMIT 1");

$usuario = $sql_logar->fetch_assoc();

if(password_verify($user_password, $usuario['user_password'])) {

    if(!isset($_SESSION)){
        session_start();

    // Grava no BD se o usuário está logado
    $queryLogin =  $conn -> query("UPDATE tbl_users SET logon = 1 WHERE user_email= '$user_email'");


    // Grava no BD o IP do usuário e hora do login
    $ip = $_SERVER['REMOTE_ADDR'];
    $queryIP =  $conn -> query("UPDATE tbl_users SET last_ip = '$ip', log_date = date_add(now(), interval 3 hour) WHERE user_email = '$user_email'");

    // Grava registro na tabela histórico logon
    $historicoLogin = mysqli_query($conn, "INSERT INTO historico_logon (username, logon, last_ip) values ('$user_email', date_add(now(), interval 3 hour), '$ip');");
    
    }

    $_SESSION['username'] = $usuario['username'];
    $_SESSION['user_email'] = $usuario['user_email'];
    $_SESSION['rm'] = $usuario['rm'];
    $_SESSION['om'] = $usuario['om'];
    $_SESSION['user_aim'] = $usuario['user_aim'];

    header("location: ../start_screem.php");

}else {
    echo "<script>alert('Falha no login do usuário.');
    window.location.href='../index.html'</script>";
}

?>
