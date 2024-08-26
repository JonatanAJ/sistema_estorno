<?php
session_start();
require 'config.php';  // Ajuste o caminho se necessário

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../public/solicitacoes.php');
    exit;
}

// Verifica se o ID da solicitação e a ação foram enviados
if (isset($_POST['id_solicitacao']) && isset($_POST['acao'])) {
    $id_solicitacao = $_POST['id_solicitacao'];
    $acao = $_POST['acao'];
    $usuario_id = $_SESSION['usuario_id']; // ID do administrador que está fazendo a ação

    // Define o novo status e a mensagem com base na ação
    if ($acao === 'autorizar') {
        $novo_status = 'Autorizado';
        $mensagem = 'Sua solicitação foi autorizada!';
    } elseif ($acao === 'cancelar') {
        $novo_status = 'Negado';
        $mensagem = 'Sua autorização foi negada!';
    } else {
        $_SESSION['mensagem'] = 'Ação inválida!';
        header('Location: ../public/solicitacoes.php');
        exit;
    }

    // Atualiza a solicitação de estorno no banco de dados
    $stmt = $pdo->prepare("UPDATE solicitacoes_estorno 
                            SET status = :status, autorizado_por = :autorizado_por, data_autorizacao = NOW() 
                            WHERE id = :id");
    $stmt->execute([
        ':status' => $novo_status,
        ':autorizado_por' => $usuario_id,
        ':id' => $id_solicitacao
    ]);

    // Define a mensagem de sucesso
    $_SESSION['mensagem'] = $mensagem;
} else {
    $_SESSION['mensagem'] = 'Ação inválida!';
}

// Redireciona de volta para a página de solicitações
header('Location: ../public/solicitacoes.php');
exit;
