<?php
include_once("../Config/config.php");
include_once("../Config/database.php");

/* ini_set('max_input_vars', 3000);
error_reporting(0); // Desativa a exibição de todos os tipos de erros
ini_set('display_errors', '0'); // Garante que erros não sejam exibidos no navegador
ini_set('display_startup_errors', '0'); // Garante que erros de inicialização não sejam exibidos no navegador */

function validaUsuario($conn)
{
    
    session_start();

    $login = $_SESSION["username"];
    $senha = $_SESSION["password"];

    $sql = "SELECT 
           TB01066_USUARIO Usuario,
           TB01066_SENHA Senha
       FROM 
           TB01066
       WHERE 
           TB01066_USUARIO = ?
           AND TB01066_SENHA = ?";
    $stmt = sqlsrv_prepare($conn, $sql, array($login, $senha));
    sqlsrv_execute($stmt);
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $usuario = $row['Usuario'];
        $senha = $row['Senha'];
    }
    if ($usuario != NULL) {
        return strtoupper($login);

    } /* else {
        return print "<script>window.alert('É necessário fazer login!')</script>
                        <script>location.href='../Public/login.php'</script>";
    } */
}

