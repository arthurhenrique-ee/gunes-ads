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

        $result = $conn -> query("SELECT * FROM usuarios WHERE id = '$id'");

        if ($result -> num_rows == 0) {
             $_SESSION["toast"] = "Usuário não encontrado/O usuário informado não existe./erro";
            header("location: ../../admin.php");
            exit;
        }

        $usuario = $result -> fetch_assoc();

        $sql = "DELETE FROM usuarios WHERE id = '$id'";

        if ($conn -> query($sql)) {

            if (!empty($usuario["contrato_pdf"])) {
                $caminho_contrato = "../../" . $usuario["contrato_pdf"];

                if (file_exists($caminho_contrato)) {
                    unlink($caminho_contrato);
                }
            }

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