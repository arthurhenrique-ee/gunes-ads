<?php 
    session_start();
    include "../conexao.php";
    $_SESSION["backScreen"] = "usuarios";

    if (isset($_GET["id"])) {
        $id = $_GET["id"];
        $id_usuario = $_SESSION["id"];

        if ($id == $id_usuario) {
            $_SESSION["toast"] = "Ação não permitida/Você não pode desativar o próprio usuário enquanto estiver logado./erro";
            header("location: ../../admin.php");
            exit;
        }

        $result = $conn -> query("SELECT * FROM usuarios WHERE id = '$id'");

        if ($result -> num_rows == 0) {
            $_SESSION["toast"] = "Usuário não encontrado/O usuário informado não existe./erro";
            header("location: ../../admin.php");
            exit;
        }

        $usuario = $result -> fetch_assoc();

        if ($usuario["status"] == "ativo") {
            $status = "inativo";
            $_SESSION["toast"] = "Usuário desativado/O usuário não poderá mais acessar o painel./info";
        } else {
            $status = "ativo";
            $_SESSION["toast"] = "Usuário reativado/O usuário voltou a ter acesso ao painel./sucesso";
        }

        $sql = "UPDATE usuarios SET `status` = '$status' WHERE id = '$id'";
        if ($conn -> query($sql)) {
            header("location: ../../admin.php");
            exit;
        } else {
            header("location: ../../admin.php");
            exit;
        }
    }
?>