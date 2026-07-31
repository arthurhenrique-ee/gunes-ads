<?php 
    session_start();
    include "../conexao.php";
    $_SESSION["backScreen"] = "usuarios";

    if (!empty($_POST["id"])) {

        // UPDATE USUÁRIO

        $id = $_POST["id"];
        $nome = $_POST["nome"];
        $telefone = $_POST["telefone"];
        $email = $_POST["email"];
        $obs = $_POST["observacoes"];
        
        $firstName = explode(" ", $nome)[0];

        $sql = "UPDATE usuarios
                SET nome = '$nome',
                telefone = '$telefone',
                email = '$email',
                observacoes = '$obs'
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
        $email = $_POST["email"];
        $telefone = $_POST["telefone"];
        $nivel = $_POST["nivel"];
        $obs = $_POST["observacoes"];

        $contrato = uniqid() . "_" . str_replace(" ", "_", $_FILES["contrato_pdf"]["name"]);
        $caminho = "uploads/contracts/" . $contrato;
        move_uploaded_file($_FILES["contrato_pdf"]["tmp_name"], "../../" . $caminho);
        
        $firstName = explode(" ", $nome)[0];

        $result = $conn -> query("SELECT * FROM usuarios WHERE email = '$email'");

        if ($result -> num_rows == 1) {
            $_SESSION["toast"] = "Usuário já existente/Já existe um usuário utilizando este endereço de e-mail./erro";
            header("location: ../../admin.php");
            exit;

        } else {

            $sql = "INSERT INTO usuarios (nome, telefone, email, contrato_pdf, observacoes)
            VALUES
            ('$nome', '$telefone', '$email', '$caminho', '$obs')";

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