<?php
use App\Controller\usuarioController;

// Chama a lógica de cadastro
usuarioController::cadastrar();
?>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1>Cadastro</h1>
            <p>Crie sua conta na equipe</p>
        </div>

        <?php if (usuarioController::$msg): ?>
            <div class="alert alert--<?= usuarioController::$msgTipo ?>">
                <?= usuarioController::$msg ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="nome">Nome Completo</label>
                <input type="text" name="nome" id="nome" required>
            </div>
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" name="email" id="email" required>
            </div>
            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" name="senha" id="senha" required minlength="6">
            </div>
            <button type="submit" class="btn btn--primary btn--full">Criar Conta</button>
        </form>

        <div class="auth-footer">
            Já tem conta? <a href="?p=login">Fazer Login</a>
        </div>
    </div>
</div>