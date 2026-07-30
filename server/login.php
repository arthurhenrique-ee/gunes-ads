<?php 
    session_start();
    include "conexao.php";

    if (isset($_POST["email"]) && isset($_POST["password"])) {

        $emailField = $_POST["email"];
        $senhaField = $_POST["password"];

        $query = "SELECT * FROM usuarios WHERE email = '$emailField'";
        $result = $conn -> query($query);

        if ($result -> num_rows == 1) {

            $usuario = $result -> fetch_assoc();

            if ($senhaField == $usuario["senha"]) {

                $_SESSION["id"] = $usuario["id"];
                $_SESSION["nivel"] = $usuario["nivel"];

                if ($usuario["nivel"] == "admin") {
                    header("location: ../admin.php");
                    exit;
                } else if ($usuario["nivel"] == "user") {
                    header("location: ../painel.php");
                    exit;
                }

            } else {
                $_SESSION["erroLogin"] = "E-mail ou senha inválidos.";
                header("location: ../index.php");
                exit;
            }
        } else {
            $_SESSION["erroLogin"] = "E-mail ou senha inválidos.";
            header("location: ../index.php");
            exit;
        }
    } else {
        header("location: ../index.php");
        exit;
    }
?>