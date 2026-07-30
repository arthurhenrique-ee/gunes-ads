<?php 
    $conn = new mysqli("localhost", "root", "", "gunesads");
    
    if ($conn -> connect_error) {
        die("Erro na conexão: ". $conn -> connect_error);
    }
?>