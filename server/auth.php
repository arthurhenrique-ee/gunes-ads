<?php 
    session_start();
    include "server/conexao.php";

    function firstLastName($nome) {
        $partes = preg_split('/\s+/', trim($nome));

        if (count($partes) >= 2) {
            return $partes[0] . " " . $partes[1];
        }

        return $partes[0];
    }

    function iniciais($nome) {
        $partes = preg_split('/\s+/', trim($nome));

        $iniciais = $partes[0][0];

        if (isset($partes[1])) {
            $iniciais .= $partes[1][0];
        }

        return strtoupper($iniciais);
    }

    function formatTel($telefone) {
        $telefone = preg_replace('/\D/', '', $telefone);

        if (strlen($telefone) == 11) {
            return preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $telefone);
        }

        if (strlen($telefone) == 10) {
            return preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $telefone);
        }

        return $telefone;
    }

    function formatData($data) {
        return date("d/m/Y", strtotime($data));
    }


    function formatDataHora($data) {
        return date("d/m/Y H:i", strtotime($data));
    }

    function diasRestantes($dataFim) {
        $hoje = new DateTime();
        $fim = new DateTime($dataFim);

        if ($fim < $hoje) {
            return 0;
        }

        return $hoje->diff($fim)->days;
    }

    function progress($dataInicio, $dataFim) {
        $inicio = strtotime($dataInicio);
        $fim = strtotime($dataFim);
        $hoje = time();

        // Ainda não começou
        if ($hoje <= $inicio) {
            return 0;
        }

        // Já terminou
        if ($hoje >= $fim) {
            return 100;
        }

        $total = $fim - $inicio;
        $decorrido = $hoje - $inicio;

        return round(($decorrido / $total) * 100);
    }

    if ($_SESSION["id"]) {

        // INFORMAÇÕES DO USUÁRIO
        $id = $_SESSION["id"];
        $usuario = $conn -> query("SELECT * FROM usuarios WHERE id = '$id'") -> fetch_assoc();

        if ($usuario["status"] == "inativo") {
            $_SESSION["erroLogin"] = "Seu acesso ao painel foi desativado.";
            header("location: index.php");
            exit;
        }

        $fullName = $usuario["nome"];
        $firstName = explode(" ", $fullName)[0];
        $lastName = explode(" ", $fullName)[1];
        $iniciais = $firstName[0].$lastName[0];
        $telefone = $usuario["telefone"];
        $email = $usuario["email"];
        $senha = $usuario["senha"];
        $fotoPerfil = $usuario["foto_perfil"];
        $nivel = $usuario["nivel"];
        $status = $usuario["status"];
        $criadoEm = $usuario["criado_em"];


        // USUÁRIOS
        $sql = "SELECT * FROM usuarios";
        $result = $conn -> query($sql);
        $usuarios = [];
        while ($row = $result -> fetch_assoc()) {
            $usuarios[] = $row;
        }


        // ANÚNCIOS
        $sql = "SELECT
                    anuncios.*,
                    usuarios.nome AS usuario_nome,
                    planos.nome AS plano_nome
                FROM anuncios
                INNER JOIN usuarios
                    ON anuncios.usuario_id = usuarios.id
                LEFT JOIN planos
                    ON anuncios.plano_id = planos.id";
        $result = $conn -> query($sql);
        $anuncios = [];
        while ($row = $result -> fetch_assoc()) {
            $anuncios[] = $row;
        }


        // PLANOS
        $sql = "SELECT * FROM planos ORDER BY duracao, tempo_anuncio";
        $result = $conn -> query($sql);
        $planos = [];

        while ($plano = $result -> fetch_assoc()) {
            $planos[$plano['nivel']][] = $plano;
        }


    } else {
        header("location: index.php");
        exit;
    }
?>