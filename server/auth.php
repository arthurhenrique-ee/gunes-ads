<?php 
    session_start();
    include "conexao.php";

    if ($_SESSION["id"]) {

        $id = $_SESSION["id"];
        $query = "SELECT * FROM usuarios WHERE id = '$id'";
        $usuario = $conn -> query($query) -> fetch_assoc();

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