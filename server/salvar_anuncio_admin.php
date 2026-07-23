<?php 
    session_start();
    include "conexao.php";

    if ($_POST["anuncio_id"] == "") {
        
        $id_usuario = $_SESSION["id"];
        $nome = $_POST["ad_nome"];
        $descricao = $_POST["ad_descricao"];
        $categoria = $_POST["ad_categoria"];
        $status = "ativo";

        $nomeImg = uniqid() . "_" . str_replace(" ", "_", $_FILES["ad_image"]["name"]);
        $tempImg = $_FILES["ad_image"]["tmp_name"];

        $extensao = strtolower(pathinfo($nomeImg, PATHINFO_EXTENSION));
        $permitidas = ["jpg", "jpeg", "png"];

        if (!in_array($extensao, $permitidas)) {
            echo "Formato de imagem inválido.";
            exit();
        }

        if ($_FILES["ad_image"]["size"] > 20000000) {
            echo "Imagem muito grande.";
            exit();
        }

        $caminho = "../uploads/" . $nomeImg;
        move_uploaded_file($tempImg, $caminho);
        $caminhoBanco = $nomeImg;

        $conn -> query("INSERT INTO anuncios (id_usuario, nome, descricao, categoria, `status`, imagem)
        VALUES ('$id_usuario', '$nome', '$descricao', '$categoria', '$status', '$nomeImg')");
        
        header("location: ../painel-admin.php");


    } else {
        echo "Editar Anúncio";
    }
?>