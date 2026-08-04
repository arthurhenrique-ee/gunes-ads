<?php 
    session_start();
    include "../conexao.php";
    $_SESSION["backScreen"] = "anuncios";

    if (empty($_POST["id"])) {

        // CREATE ANÚNCIO

        if (isset($_POST["institucional"])) {

            // ANÚNCIO INSTITUCIONAL

            // DEFINIÇÃO DE CAMPOS OBRIGATÓRIOS
            $requiredFields = ['titulo', 'duracao', 'data-inicio', 'data-fim', 'status-inicial'];
            $erro = false;

            // VERIFICA SE TODOS OS CAMPOS FORAM PREENCHIDOS
            foreach ($requiredFields as $field) {
                if (!isset($_POST[$field]) || trim($_POST[$field]) === '') {
                    $erro = true;
                    break;
                }
            }
            // VERIFICA SE O ARQUIVO FOI ENVIADO
            if (!isset($_FILES['ad-file']) || $_FILES['ad-file']['error'] !== UPLOAD_ERR_OK) {
                $erro = true;
            }

            if (!$erro) { // TODOS OS CAMPOS PREENCHIDOS

                // CRIAR ANÚNCIO INSTITUCIONAL

                // INFORMAÇÕES DO ANÚNCIO
                $usuario_id = $_SESSION["id"];
                $titulo = $_POST["titulo"];
                $duracao = $_POST["duracao"];
                $dataInicio = $_POST["data-inicio"];
                $dataFim = $_POST["data-fim"];
                $statusInicial = $_POST["status-inicial"];
                $observacoes = $_POST["observacoes"];

                // ARQUIVO DE IMAGEM DO ANÚNCIO
                $adFile = $_FILES['ad-file'];
                $nome_adFile = uniqid() . "_" . str_replace(" ", "_", $adFile['name']);
                $tmp_adFile = $adFile['tmp_name'];
                $path_adFile = "uploads/anuncios/" . $nome_adFile;
                $extensao_adFile = strtolower(pathinfo($adFile['name'], PATHINFO_EXTENSION));
                $extensoes = ['jpg', 'jpeg', 'png', 'webp'];

                // VERIFICAÇÃO DA EXTENSÃO
                if (!in_array($extensao_adFile, $extensoes)) {
                    $_SESSION["toast"] = "Arquivo inválido/Envie apenas imagens JPG, JPEG, PNG ou WEBP./erro";
                    header("location: ../../admin.php");
                    exit;
                }

                // TAMANHO DO ARQUIVO
                if ($adFile['size'] > 2 * 1024 * 1024) { // 2 MB
                    $_SESSION["toast"] = "Arquivo muito grande/O tamanho máximo permitido é 2 MB./erro";
                    header("location: ../../admin.php");
                    exit;
                }

                // DIRECIONAMENTO DO ARQUIVO > UPLOADS
                $destino = "../../uploads/anuncios/" . $nome_adFile;
                if (!move_uploaded_file($tmp_adFile, $destino)) {
                    $_SESSION["toast"] = "Erro ao enviar a imagem/Não foi possível salvar o arquivo./erro";
                    header("location: ../../admin.php");
                    exit;
                }

                // INSERT NO BANCO DE DADOS
                $sql = "INSERT INTO anuncios (usuario_id, institucional, titulo, imagem, data_inicio, data_fim, `status`, duracao, observacoes)
                        VALUES
                        ('$usuario_id', TRUE, '$titulo', '$path_adFile', '$dataInicio', '$dataFim', '$statusInicial', '$duracao', '$observacoes')";
                if ($conn -> query($sql)) {
                    $_SESSION["toast"] = "Anúncio criado/O anúncio foi criado e já está disponível no sistema./sucesso";
                } else {
                    $_SESSION["toast"] = "Erro/Não foi possível cadastrar o anúncio. Tente novamente./erro";
                }

                header("location: ../../admin.php");
                exit;


            } else { // PREENCHA TODOS OS CAMPOS
                $_SESSION["toast"] = "Preencha todos os campos/Preencha todos os campos obrigatórios antes de continuar/erro";
                header("location: ../../admin.php");
                exit;
            }

        } else {
            // ANÚNCIO VINCULADO

            // CAMPOS OBRIGATÓRIOS
            $requiredFields = ['id_usuario', 'titulo', 'id_plano', 'data-inicio', 'data-fim', 'status-inicial'];
            $erro = false;

            foreach ($requiredFields as $field) {
                if (!isset($_POST[$field]) || trim($_POST[$field]) === '') {
                    $erro = true;
                    break;
                }
            }
            // VERIFICA SE O ARQUIVO FOI ENVIADO
            if (!isset($_FILES['ad-file']) || $_FILES['ad-file']['error'] !== UPLOAD_ERR_OK) {
                $erro = true;
            }

            if (!$erro) { // TODOS OS CAMPOS PREENCHIDOS

                // CRIAR ANÚNCIO VINCULADO
                
                // INFORMAÇÕES DO ANÚNCIO
                $id_usuario = $_POST["id_usuario"];
                $id_plano = $_POST["id_plano"];
                $titulo = $_POST["titulo"];
                $dataInicio = $_POST["data-inicio"];
                $dataFim = $_POST["data-fim"];
                $statusInicial = $_POST["status-inicial"];
                $observacoes = $_POST["observacoes"];

                $sql = "SELECT duracao FROM planos WHERE id = $id_plano";
                $result = $conn -> query($sql);

                if ($result -> num_rows == 0) {
                    $_SESSION["toast"] = "Plano inválido/O plano selecionado não existe./erro";
                    header("location: ../../admin.php");
                    exit;
                }

                $plano = $result -> fetch_assoc();
                $duracao = $plano["tempo_anuncio"];

                // ARQUIVO DE IMAGEM DO ANÚNCIO
                $adFile = $_FILES["ad-file"];
                $nome_adFile = uniqid() . "_" . str_replace(" ", "_", $$adFile["name"]);
                $tmp_adFile = $adFile["tmp_name"];
                $path_adFile = "uploads/anuncios/" . $nome_adFile;
                $extensao_adFile = strtolower(pathinfo($adFile["name"], PATHINFO_EXTENSION));
                $extensoes = ['jpg', 'jpeg', 'png', 'webp'];

                // EXTENSÃO DA IMAGEM
                if (!in_array($extensao_adFile, $extensoes)) {
                    $_SESSION["toast"] = "Arquivo inválido/Envie apenas imagens JPG, JPEG, PNG ou WEBP./erro";
                    header("location: ../../admin.php");
                    exit;
                }

                // TAMANHO DO ARQUIVO
                if ($adFile["size"] > 2 * 1024 * 1024) { // 2 MB
                    $_SESSION["toast"] = "Arquivo muito grande/O tamanho máximo permitido é 2 MB./erro";
                    header("location: ../../admin.php");
                    exit;
                }

                // DIRECIONAMENTO DO ARQUIVO > UPLOADS
                $destino = "../../uploads/anuncios/" . $nome_adFile;
                if (!move_uploaded_file($tmp_adFile, $destino)) {
                    $_SESSION["toast"] = "Erro ao enviar a imagem/Não foi possível salvar o arquivo./erro";
                    header("location: ../../admin.php");
                    exit;
                }

                // INSERT NO BANCO DE DADOS
                $sql = "INSERT INTO anuncios (usuario_id, plano_id, titulo, imagem, data_inicio, data_fim, `status`, duracao, observacoes)
                        VALUES
                        ('$id_usuario', $id_plano, '$titulo', '$path_adFile', '$dataInicio', '$dataFim', '$statusInicial', '$duracao', '$observacoes')";
                if ($conn -> query($sql)) {
                    $_SESSION["toast"] = "Anúncio criado/O anúncio foi criado e já está disponível no sistema./sucesso";
                } else {
                    $_SESSION["toast"] = "Erro/Não foi possível cadastrar o anúncio. Tente novamente./erro";
                }

                header("location: ../../admin.php");
                exit;

            } else {
                $_SESSION["toast"] = "Preencha todos os campos/Preencha todos os campos obrigatórios antes de continuar/erro";
                header("location: ../../admin.php");
                exit;
            }

        }
        
    } else {
        // UPDATE ANÚNCIO
    }
?>