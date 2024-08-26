<?php
session_start();
require '../app/config.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    if (!empty($email) && !empty($senha)) {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ? AND senha = ?");
        $stmt->execute([$email, $senha]);
        $usuario = $stmt->fetch();

        if ($usuario) {
            $_SESSION['usuario_id'] = $usuario['id'];
            header("Location: solicitacoes.php");
            exit;
        } else {
            $erro = "Email ou senha incorretos";
        }
    } else {
        $erro = "Preencha todos os campos";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - Sistema de Estorno</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" 
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" media="screen" href="../Assets/style.css">
</head>

<body>

  <header>
    <div class="container" id="nav-container">
      <nav class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand" href="#">
          <img id="logo" style="align-items: center;" src="..." alt="HF">
        </a>
      </nav>
    </div>
  </header>

  <section class="container mt-5">
    <form method="post" action="login.php" class="p-4 border rounded">
      <div class="mb-3">
        <h2 class="text-center">Login</h2>
      </div>
      <div class="mb-3">
        <label for="usuario" class="form-label">Email</label>
        <input type="email" class="form-control" id="usuario" name="email" placeholder="Email" required>
      </div>
      <div class="mb-3">
        <label for="senha" class="form-label">Senha</label>
        <input type="password" class="form-control" id="senha" name="senha" placeholder="Senha" required>
      </div>
      <div class="d-grid gap-2">
        <input type="submit" value="Entrar" class="btn btn-primary">
        <a href="#" class="text-center">Esqueceu a senha?</a>
      </div>

    
      <?php if (isset($erro)) : ?>
        <div class="alert alert-danger mt-3" role="alert">
            <?= htmlspecialchars($erro) ?>
        </div>
      <?php endif; ?>
    </form>
  </section>


  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
    integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>

</body>

</html>
