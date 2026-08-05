<?php 
    session_start();
    include "../conexao.php";
    $_SESSION["backScreen"] = "anuncios";
    $usuario_id = $_SESSION["id"];

    if (isset($_GET["id"])) {

        // EXCLUIR ANÚNCIO

        $id = $_GET["id"];

        $result = $conn -> query("SELECT * FROM anuncios WHERE id = '$id'");
        if ($result -> num_rows == 0) {
            $_SESSION["toast"] = "Anúncio não encontrado/O anúncio informado não existe./erro";
            header("location: ../../admin.php");
            exit;
        }

        $anuncio = $result -> fetch_assoc();

        $sql = "DELETE FROM anuncios WHERE id = '$id'";

        if ($conn -> query($sql)) {
            
            if (!empty($anuncio["imagem"])) {
                $path_adFile = "../../" . $anuncio["imagem"];

                if (file_exists($path_adFile)) {
                    unlink($path_adFile);
                }
            }

            $_SESSION["toast"] = "Anúncio excluído/O anúncio foi removido do sistema com sucesso./sucesso";
            header("location: ../../admin.php");
            exit;

        }
        
    }
?>