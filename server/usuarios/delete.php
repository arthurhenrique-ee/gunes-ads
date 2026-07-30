<?php 
    session_start();
    include "../conexao.php";
    $_SESSION["backScreen"] = "usuarios";
    $id_usuario = $_SESSION["id"];

    if (isset($_GET["id"])) {

        // EXLUIR USUÁRIO

        $id = $_GET["id"];

        if ($id_usuario == $id) {
            $_SESSION["toast"] = "Ação não permitida/Você não pode excluir o próprio usuário enquanto estiver logado./erro";
            header("location: ../../admin.php");
            exit;
        }

        $sql = "DELETE FROM usuarios WHERE id = '$id'";
        if ($conn -> query($sql)) {
            $_SESSION["toast"] = "Usuário excluído/O usuário foi removido do sistema com sucesso./sucesso";
            header("location: ../../admin.php");
            exit;
        } else {
            $_SESSION["toast"] = "Falha ao remover usuário/Ocorreu um erro interno ao tentar excluir o cadastro./erro";
            header("location: ../../admin.php");
            exit;
        }

    }
?>