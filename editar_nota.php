<?php
session_start();
require 'conexao.php';
require 'config.php';
include 'header.php';

if (!isset($_SESSION['id_usuario']) || !isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit;
}

$id_nota = $_GET['id'];
$id_usuario = $_SESSION['id_usuario'];


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $novo_titulo = $_POST['titulo'];
    $novo_conteudo_puro = $_POST['conteudo'];

    $metodo = "AES-256-CBC";
    $tamanho_iv = openssl_cipher_iv_length($metodo);
    $iv = openssl_random_pseudo_bytes($tamanho_iv);
    $conteudo_cifrado = openssl_encrypt($novo_conteudo_puro, $metodo, CHAVE_AES, 0, $iv);
    $pacote_seguro = base64_encode($iv . "::" . $conteudo_cifrado);

    $sql_update = "UPDATE notas SET titulo = :titulo, conteudo_criptografado = :conteudo WHERE id = :id AND id_usuario = :id_usuario";
    $stmt_up = $pdo->prepare($sql_update);
    $stmt_up->bindParam(':titulo', $novo_titulo);
    $stmt_up->bindParam(':conteudo', $pacote_seguro);
    $stmt_up->bindParam(':id', $id_nota);
    $stmt_up->bindParam(':id_usuario', $id_usuario);
    $stmt_up->execute();

    header("Location: dashboard.php");
    exit;
}

$sql = "SELECT titulo, conteudo_criptografado FROM notas WHERE id = :id AND id_usuario = :id_usuario";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id_nota);
$stmt->bindParam(':id_usuario', $id_usuario);
$stmt->execute();
$nota = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$nota) {
    echo "Erro: Nota não encontrada ou você não tem permissão para acessá-la.";
    exit;
}

$pacote = base64_decode($nota['conteudo_criptografado']);
list($iv, $conteudo_cifrado) = explode('::', $pacote, 2);
$conteudo_puro = openssl_decrypt($conteudo_cifrado, "AES-256-CBC", CHAVE_AES, 0, $iv);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Editar Nota</title>
</head>
<body style="background: #f4f6f9; padding: 20px; font-family: sans-serif;">

    <div style="max-width: 600px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <h2>Editar Nota Segura</h2>
        
        <form action="editar_nota.php?id=<?php echo $id_nota; ?>" method="POST">
            <div style="margin-bottom: 15px;">
                <label>Título:</label><br>
                <input type="text" name="titulo" value="<?php echo htmlspecialchars($nota['titulo']); ?>" required style="width: 100%; padding: 8px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label>Conteúdo:</label><br>
                <textarea name="conteudo" rows="6" required style="width: 100%; padding: 8px;"><?php echo htmlspecialchars($conteudo_puro); ?></textarea>
            </div>

            <button type="submit" style="background: #27ae60; color: white; border: none; padding: 10px 15px; cursor: pointer;">Atualizar Cofre</button>
            <a href="dashboard.php" style="margin-left: 10px; color: #7f8c8d; text-decoration: none;">Cancelar</a>
        </form>
    </div>

</body>
</html>