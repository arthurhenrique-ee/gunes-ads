<?php 
    session_start();
    include "conexao.php";

    if (isset($_POST["email"]) && isset($_POST["password"])) {

        $email = $_POST["email"];
        $senha = $_POST["password"];

        $query = "SELECT * FROM usuarios WHERE email = '$email'";
        $result = $conn -> query($query);

        if ($result -> num_rows == 1) {

            $user = $result -> fetch_assoc();

            if ($senha == $user["senha"]) {

                $_SESSION["id"] = $user["id"];
                $_SESSION["nome"] = $user["nome"];
                $_SESSION["telefone"] = $user["telefone"];
                $_SESSION["email"] = $user["email"];
                $_SESSION["senha"] = $user["senha"];

                header("location: ../painel.php");
                exit;

            } else {
                $_SESSION["formError"] = true;
                header("location: ../index.php");
                exit;
            }

        } else {
            $_SESSION["formError"] = true;
            header("location: ../index.php");
            exit;
        }
        

    } else {
        header("location: ../index.php");
        exit;
    }
?>