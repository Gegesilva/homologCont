<?php
include "../Config/config.php";
include "../Config/database.php";
session_start();

$login = $_POST['login'];
$senha = $_POST['password'];

// Inicia a sessão
$_SESSION["login"] = $login;
$_SESSION["password"] = $senha;

// Verifica se as variáveis de sessão já estão definidas

$sql = "SELECT 
            TB01066_USUARIO Usuario,
            TB01066_SENHA Senha,
            TB01066_TIPO Tipo

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

/* print_r( $usuario);
print_r( $senha); */

// Verifica se o usuário e senha estão corretos
if ($usuario != NULL) {

    echo "<script>location.href='../app/index.php'</script>";
} else {


    echo "<script>window.alert('Usuario e/ou senha invalidos!')</script>";
    echo "<script>location.href='../Public/login.php'</script>";

}


?>