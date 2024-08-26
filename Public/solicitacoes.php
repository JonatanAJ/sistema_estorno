<?php
session_start();
require '../app/config.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

// Obtém as solicitações de estorno com status pendente ou negado
$stmt = $pdo->query("SELECT s.id, u.nome, s.data_solicitacao, s.status, s.valor, s.parcelas
                     FROM solicitacoes_estorno s 
                     JOIN transacoes t ON s.id_transacao = t.id 
                     JOIN usuarios u ON t.id_usuario = u.id
                     WHERE s.status IN ('Pendente', 'Negado')");
$solicitacoes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Solicitação de Estorno</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>

  <header class="bg-dark text-white py-3">
    <div class="container">
      <h1 class="h3 text-center">Solicitação de Estorno</h1>
    </div>
  </header>

  <main class="container my-4">
    <div class="table-responsive rounded shadow-sm bg-light p-3">
      <table class="table table-striped table-hover">
        <thead class="table-dark">
          <tr>
            <th scope="col">ID</th>
            <th scope="col">Cliente</th>
            <th scope="col">Data</th>
            <th scope="col" class="text-center">Ação</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($solicitacoes as $solicitacao): ?>
          <tr>
            <th scope="row"><?= htmlspecialchars($solicitacao['id']) ?></th>
            <td><?= htmlspecialchars($solicitacao['nome']) ?></td>
            <td><?= htmlspecialchars(date('d/m/Y', strtotime($solicitacao['data_solicitacao']))) ?></td>
            <td class="text-center">
              <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalEstorno<?= $solicitacao['id'] ?>">
                Solicitar
              </button>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Modals -->
    <?php foreach ($solicitacoes as $solicitacao): ?>
    <div class="modal fade" id="modalEstorno<?= $solicitacao['id'] ?>" tabindex="-1" aria-labelledby="modalEstornoLabel<?= $solicitacao['id'] ?>" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalEstornoLabel<?= $solicitacao['id'] ?>">Detalhes do Estorno</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <table class="table">
              <thead>
                <tr>
                  <th scope="col">Cliente</th>
                  <th scope="col">Valor</th>
                  <th scope="col">Parcelas</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><?= htmlspecialchars($solicitacao['nome']) ?></td>
                  <td><?= htmlspecialchars('R$' . number_format($solicitacao['valor'], 2, ',', '.')) ?></td>
                  <td><?= htmlspecialchars($solicitacao['parcelas']) ?></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="modal-footer">
            <form action="../app/processar_solicitacoes.php" method="post">
              <input type="hidden" name="id_solicitacao" value="<?= htmlspecialchars($solicitacao['id']) ?>">
              <input type="hidden" name="acao" id="acao<?= htmlspecialchars($solicitacao['id']) ?>">
              <button type="button" class="btn btn-success" onclick="document.getElementById('acao<?= htmlspecialchars($solicitacao['id']) ?>').value = 'autorizar'; this.closest('form').submit()">Autorizar</button>
              <button type="button" class="btn btn-danger" onclick="document.getElementById('acao<?= htmlspecialchars($solicitacao['id']) ?>').value = 'cancelar'; this.closest('form').submit()">Cancelar</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>

    <?php if (isset($_SESSION['mensagem'])): ?>
    <div class="alert alert-info mt-3" role="alert">
        <?= $_SESSION['mensagem'] ?>
        <?php unset($_SESSION['mensagem']); ?>
    </div>
    <?php endif; ?>

  </main>

  <!-- Bootstrap JavaScript -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
          integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
          integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>

</body>
</html>
