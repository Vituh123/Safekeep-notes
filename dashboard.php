<?php
session_start();

require 'conexao.php';
require 'config.php';
include 'header.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.html");
    exit;
}
$sql = "SELECT id, titulo, conteudo_criptografado, data_criacao FROM notas WHERE id_usuario = :id_usuario ORDER BY data_criacao DESC";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id_usuario', $_SESSION['id_usuario']);
$stmt->execute();
$notas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>SafeKeep Notes - Meu Cofre</title>
</head>

<body>

    <div class="dashboard-container">
        
        <div class="dashboard-header">
            <h2>SafeKeep Notes</h2>
            <div class="user-info">
                <p>Bem-vindo ao cofre, <strong><?php echo htmlspecialchars($_SESSION['nome_usuario']); ?></strong></p>
                <a href="logout.php" style="color: #e74c3c; background: white; padding: 6px 12px; border-radius: 5px; text-decoration: none; font-weight: bold;">Sair (Logout)</a>
            </div>
        </div>

        <div class="dashboard-content">
            
            <div class="notes-section">
                <h2 style="text-align: left;">Minhas Anotações Descriptografadas</h2>
                
                <div class="notes-grid">
                    <?php if (count($notas) > 0): ?>
                        <?php foreach ($notas as $nota): ?>
                            
                            <?php
                            $pacote = base64_decode($nota['conteudo_criptografado']);
                            list($iv, $conteudo_cifrado) = explode('::', $pacote, 2);
                            $conteudo_puro = openssl_decrypt($conteudo_cifrado, "AES-256-CBC", CHAVE_AES, 0, $iv);
                            ?>

                            <div class="note-card">
                                <h3 style="margin-top: 0; color: #60a5fa;"><?php echo htmlspecialchars($nota['titulo']); ?></h3>
                                
                                <div style="background: #0f172a; padding: 12px; font-family: monospace; color: #e2e8f0; border-radius: 4px; margin: 10px 0; border: 1px solid #475569;">
                                    <?php echo nl2br(htmlspecialchars($conteudo_puro)); ?>
                                </div>
                                
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 15px;">
                                    <small style="color: #94a3b8;">Salvo em: <?php echo date('d/m/Y H:i', strtotime($nota['data_criacao'])); ?></small>
                                    
                                    <div>
                                        <a href="editar_nota.php?id=<?php echo $nota['id']; ?>" style="color: white; background: #f39c12; padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size: 14px; margin-right: 5px;">Editar</a>
                                        <a href="excluir_nota.php?id=<?php echo $nota['id']; ?>" onclick="return confirm('Tem certeza que deseja destruir esta nota?');" style="color: white; background: #e74c3c; padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size: 14px;">Excluir</a>
                                    </div>
                                </div>
                            </div>

                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="color: #94a3b8; font-style: italic;">Seu cofre está vazio. Crie sua primeira nota ao lado!</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-section">
                <div class="note-form-box">
                    <h3>Criar Nota Segura (AES-256)</h3>
                    <form action="salvar_nota.php" method="POST">
                        <div class="form-group">
                            <label for="titulo">Título da Nota:</label>
                            <input type="text" id="titulo" name="titulo" required placeholder="Ex: Senhas de Servidores">
                        </div>

                        <div class="form-group">
                            <label for="conteudo">Conteúdo Secreto:</label>
                            <textarea id="conteudo" name="conteudo" rows="4" required placeholder="Escreva seu segredo aqui."></textarea>
                        </div>

                        <button type="submit">Trancar no Cofre</button>
                    </form>
                </div>
            </div>

        </div>
    </div>

</body>
</html>