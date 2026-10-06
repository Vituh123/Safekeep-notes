<?php

require 'conexao.php';
include 'header.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha_digitada = $_POST['senha'];

    $senha_hash = password_hash($senha_digitada, PASSWORD_DEFAULT);

    try {

        $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senha_hash);

        $stmt->execute();

        echo "<div class='auth-card'>";
        echo "<h2>Conta criada com sucesso</h2>";
        echo "<a href='index.html' class='btn-message'>Clique aqui para fazer o Login</a>";
        echo "</div>";

    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            echo "<div class='auth-card'>";
            echo "<h2>Erro: E-mail já cadastrado</h2>";
            echo "<a href='cadastro.html' class='btn-message'>Tente outro</a>";
            echo "</div>";
        } else {
            echo "<div class='auth-card'>";
            echo "<h2>Erro no servidor</h2>";
            echo "<p>" . $e->getMessage() . "</p>";
            echo "</div>";
        }
    }

} else {
    echo "<div class='auth-card'>";
    echo "<h2>Acesso bloqueado.</h2>";
    echo "</div>";
}
?>