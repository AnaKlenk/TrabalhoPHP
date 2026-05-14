<?php
use App\Controller\usuarioController;

// Chama a lógica de login do controlador
usuarioController::login();
?>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1>Login</h1>
            <p>Acesse o TaskFlow</p>
        </div>

        <?php if (usuarioController::$msg): ?>
            <div class="alert alert--<?= usuarioController::$msgTipo ?>">
                <?= usuarioController::$msg ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" name="email" id="email" required>
            </div>
            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" name="senha" id="senha" required>
            </div>
            <button type="submit" class="btn btn--primary btn--full">Entrar</button>
        </form>

        <div class="auth-footer">
            Não tem uma conta? <a href="?p=cadastro">Cadastre-se</a>
        </div>
    </div>
</div>