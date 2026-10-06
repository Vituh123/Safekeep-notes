<?php
session_start();
require 'conexao.php';
include 'header.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha_digitada = $_POST['senha'];

    $sql = "SELECT id, nome, senha FROM usuarios WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($senha_digitada, $usuario['senha'])) {
        
        $_SESSION['id_usuario'] = $usuario['id'];
        $_SESSION['nome_usuario'] = $usuario['nome'];
        
        header("Location: dashboard.php"); 
        exit;
    } else {

        echo "<div class='auth-card'>";
        echo "<h2>Credenciais inválidas.</h2>";
        echo "<a href='index.html' class='btn-message'>Tentar novamente</a>";
        echo "</div>";
        
    }
} else {
    echo "<div class='auth-card'><h2>Acesso bloqueado.</h2></div>";
}
?>