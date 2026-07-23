<?php 
    include "conexao.php";

    if (isset($_POST["anuncio_id"])) {
        $anuncio_id = $_POST["anuncio_id"];
        $conn -> query("DELETE FROM anuncios WHERE id = '$anuncio_id'");

        header("location: ../painel-admin.php");

    }
?>