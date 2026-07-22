<?php 
    session_start();
    include "conexao.php";

    if ($_SESSION["id"]) {

        $id = $_SESSION["id"];
        $usuario = $conn -> query("SELECT * FROM usuarios WHERE id = '$id'") -> fetch_assoc();
        $anuncios = $conn -> query("SELECT * FROM anuncios WHERE id_usuario = '$id'");

        // INFORMAÇÕES DO USUÁRIO
        $fullName = $usuario["nome"];
        $firstName = explode(" ", $fullName)[0];
        $lastName = explode(" ", $fullName)[1];
        $iniciais = $firstName[0].$lastName[0];
        $telefone = $usuario["telefone"];
        $email = $usuario["email"];
        $senha = $usuario["senha"];
        $nivel = $usuario["nivel"];

    } else {
        header("location: index.php");
        exit;
    }
?>