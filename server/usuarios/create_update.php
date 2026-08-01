<?php 
    session_start();
    include "../conexao.php";
    $_SESSION["backScreen"] = "usuarios";

    if (!empty($_POST["id"])) {

        // UPDATE USUÁRIO

        $id = $_POST["id"];
        $nome = $_POST["nome"];
        $firstName = explode(" ", $nome)[0];
        $telefone = $_POST["telefone"];
        $email = $_POST["email"];
        $nivel = $_POST["nivel"];
        $observacoes = $_POST["observacoes"];
        $caminho_contrato = $_POST["contrato_atual"];
        
        if (isset($_FILES["contrato_pdf"]) && $_FILES["contrato_pdf"]["error"] === UPLOAD_ERR_OK) {
            $nome_contrato = uniqid() . "_" . str_replace(" ", "_", $_FILES["contrato_pdf"]["name"]);
            $novo_caminho = "uploads/contracts/" . $nome_contrato;
            $extensao_contrato = strtolower(pathinfo($nome_contrato, PATHINFO_EXTENSION));

            if ($extensao_contrato !== "pdf") {
                $_SESSION["toast"] = "Apenas arquivos PDF são permitidos/Verifique e tente novamente./erro";
                header("location: ../../admin.php");
                exit;
            }

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $_FILES["contrato_pdf"]["tmp_name"]);
            finfo_close($finfo);

            if ($mime !== "application/pdf") {
                $_SESSION["toast"] = "Arquivo inválido/O contrato deve ser um PDF válido./erro";
                header("location: ../../admin.php");
                exit;
            }

            $caminho_antigo = $caminho_contrato;
            $caminho_contrato = $novo_caminho;

            if (!move_uploaded_file($_FILES["contrato_pdf"]["tmp_name"], "../../" . $caminho_contrato)) {
                $_SESSION["toast"] = "Erro ao enviar o contrato/Não foi possível salvar o arquivo./erro";
                header("location: ../../admin.php");
                exit;
            }

            if (!empty($caminho_antigo) && file_exists("../../" . $caminho_antigo)) {
                unlink("../../" . $caminho_antigo);
            }
        }

        $sql = "UPDATE usuarios
                    SET nome = '$nome',
                        telefone = '$telefone',
                        email = '$email',
                        nivel = '$nivel',
                        observacoes = '$observacoes',
                        contrato_pdf = '$caminho_contrato'
                    WHERE id = '$id'";
        if ($conn -> query($sql)) {
            $_SESSION["toast"] = "Usuário $firstName atualizado/As informações do usuário foram atualizadas no sistema./sucesso";
            header("location: ../../admin.php");
            exit;
        } else {
            $_SESSION["toast"] = "Erro ao atualizar usuário/Ocorreu um erro ao atualizar os dados do usuário no sistema./erro";
            header("location: ../../admin.php");
            exit;
        }

    } else {
        
        // CREATE USUÁRIO

        $nome = $_POST["nome"];
        $firstName = explode(" ", $nome)[0];
        $email = $_POST["email"];
        $telefone = $_POST["telefone"];
        $nivel = $_POST["nivel"];
        $observacoes = $_POST["observacoes"];
        $caminho_contrato = null;

        if (isset($_FILES["contrato_pdf"]) && $_FILES["contrato_pdf"]["error"] === UPLOAD_ERR_OK) {
            $nome_contrato = uniqid() . "_" . str_replace(" ", "_", $_FILES["contrato_pdf"]["name"]);
            $caminho_contrato = "uploads/contracts/" . $nome_contrato;
            $extensao_contrato = strtolower(pathinfo($nome_contrato, PATHINFO_EXTENSION));

            if ($extensao_contrato !== "pdf") {
                $_SESSION["toast"] = "Apenas arquivos PDF são permitidos/Verifique e tente novamente./erro";
                header("location: ../../admin.php");
                exit;
            }

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $_FILES["contrato_pdf"]["tmp_name"]);
            finfo_close($finfo);

            if ($mime !== "application/pdf") {
                $_SESSION["toast"] = "Arquivo inválido/O contrato deve ser um PDF válido./erro";
                header("location: ../../admin.php");
                exit;
            }

            if (!move_uploaded_file($_FILES["contrato_pdf"]["tmp_name"], "../../" . $caminho_contrato)) {
                $_SESSION["toast"] = "Erro ao enviar o contrato/Não foi possível salvar o arquivo./erro";
                header("location: ../../admin.php");
                exit;
            }
        }

        

        $result = $conn -> query("SELECT * FROM usuarios WHERE email = '$email'");

        if ($result -> num_rows > 0) {
            $_SESSION["toast"] = "Usuário já existente/Já existe um usuário utilizando este endereço de e-mail./erro";
            header("location: ../../admin.php");
            exit;
        } else {
            $sql = "INSERT INTO usuarios (nome, telefone, email, nivel, contrato_pdf, observacoes)
            VALUES
            ('$nome', '$telefone', '$email', '$nivel', '$caminho_contrato', '$observacoes')";

            if ($conn -> query($sql)) {
                $_SESSION["toast"] = "Usuário cadastrado/O usuário $firstName foi criado com sucesso e já pode acessar o painel./sucesso";
                header("location: ../../admin.php");
                exit;
            } else {
                $_SESSION["toast"] = "Erro ao cadastrar usuário/O usuário não foi adicionado ao sistema devido a um erro interno./erro";
                header("location: ../../admin.php");
                exit;
            }
        }     
    }
?>