<?php 
    session_start();

    if ($_SESSION["id"]) {
        $id = $_SESSION["id"];
        $nome = $_SESSION["nome"];
        $primeiroNome = explode(" ", $nome)[0];
        $telefone = $_SESSION["telefone"];
        $email = $_SESSION["email"];
        $senha = $_SESSION["senha"];
    } else {
        header("location: index.php");
        exit;
    }
?>