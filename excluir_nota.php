<?php
session_start();
require 'conexao.php';
include 'header.php';

if (!isset($_SESSION['id_usuario']) || !isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit;
}

$sql = "DELETE FROM notas WHERE id = :id AND id_usuario = :id_usuario";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $_GET['id']);
$stmt->bindParam(':id_usuario', $_SESSION['id_usuario']);

$stmt->execute();

header("Location: dashboard.php");
exit;
?>