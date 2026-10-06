<?php
session_start();
require 'conexao.php';
require 'config.php';
include 'header.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.html");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST['titulo'];
    $conteudo_puro = $_POST['conteudo'];
    $id_usuario = $_SESSION['id_usuario'];

    $metodo = "AES-256-CBC";
    
    $tamanho_iv = openssl_cipher_iv_length($metodo);
    $iv = openssl_random_pseudo_bytes($tamanho_iv);
    
    $conteudo_cifrado = openssl_encrypt($conteudo_puro, $metodo, CHAVE_AES, 0, $iv);
    
    $pacote_seguro = base64_encode($iv . "::" . $conteudo_cifrado);

    try {
        $sql = "INSERT INTO notas (id_usuario, titulo, conteudo_criptografado) VALUES (:id_usuario, :titulo, :conteudo)";
        $stmt = $pdo->prepare($sql);
        
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':conteudo', $pacote_seguro);
        
        $stmt->execute();

        header("Location: dashboard.php");
        exit;

    } catch (PDOException $e) {
        echo "Erro ao salvar a nota: " . $e->getMessage();
    }
} else {
    echo "Acesso inválido.";
}
?>