<?php
use App\Controller\usuarioController;

usuarioController::login();

$emailLembrado = $_COOKIE['ultimo_email'] ?? '';
?>

<div class="auth-wrap">
    <div class="auth-box">

        <div class="auth-box__logo">
            <div class="auth-box__logo-icon">✓</div>
            Task<span style="color: var(--accent);">Flow</span>
        </div>

        <h1 class="auth-box__title">Bem-vindo de volta</h1>
        <p class="auth-box__sub">Acesse o painel da sua equipe</p>

        <?php if (usuarioController::$msg): ?>
            <div style="margin-bottom: 20px; padding: 12px 16px; border-radius: var(--radius-sm); font-size: 14px;
                background: <?= usuarioController::$msgTipo === 'erro' ? 'var(--red-bg)' : 'var(--green-bg)' ?>;
                color: <?= usuarioController::$msgTipo === 'erro' ? 'var(--red)' : 'var(--green)' ?>;
                border: 1px solid <?= usuarioController::$msgTipo === 'erro' ? 'rgba(239,68,68,0.25)' : 'rgba(34,197,94,0.25)' ?>;">
                <?= htmlspecialchars(usuarioController::$msg) ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" novalidate>

            <div class="form-group">
                <label for="email">E-mail</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    required
                    autofocus
                    autocomplete="email"
                    placeholder="seu@email.com"
                    value="<?= htmlspecialchars($emailLembrado) ?>"
                    style="<?= $emailLembrado ? 'border-color: var(--accent);' : '' ?>">
                <?php if ($emailLembrado): ?>
                    <small style="display: block; margin-top: 5px; color: var(--text-muted); font-size: 12px;">
                        🍪 E-mail lembrado pelo cookie.
                        <a href="?p=logout" style="color: var(--accent); text-decoration: none;">Não é você?</a>
                    </small>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="senha">Senha</label>
                <div style="position: relative;">
                    <input
                        type="password"
                        name="senha"
                        id="senha"
                        required
                        autocomplete="current-password"
                        placeholder="••••••••"
                        style="padding-right: 44px;">
                    <button type="button"
                        onclick="var i=document.getElementById('senha');if(i.type==='password'){i.type='text';this.textContent='🙈';}else{i.type='password';this.textContent='👁';}"
                        style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;font-size:16px;color:var(--text-muted);padding:4px;"
                        title="Mostrar/ocultar senha">👁</button>
                </div>
            </div>

            <div style="margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                <input type="checkbox" name="lembrar" id="lembrar"
                       style="width: auto; accent-color: var(--accent);"
                       <?= $emailLembrado ? 'checked' : '' ?>>
                <label for="lembrar" style="font-size:13px;color:var(--text-muted);cursor:pointer;margin:0;text-transform:none;letter-spacing:0;font-weight:400;">
                    Lembrar meu e-mail neste dispositivo
                </label>
            </div>

            <button type="submit" class="btn btn--primary btn--full" style="height:46px;font-size:15px;font-weight:600;">
                Entrar
            </button>

        </form>

        <div class="auth-box__footer">
            Não tem uma conta? <a href="?p=cadastro">Cadastre-se grátis</a>
        </div>

    </div>
</div>
