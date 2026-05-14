<?php
use App\Controller\usuarioController;

usuarioController::cadastrar();
?>

<div class="auth-wrap">
    <div class="auth-box">

        <div class="auth-box__logo">
            <div class="auth-box__logo-icon">✓</div>
            Task<span style="color: var(--accent);">Flow</span>
        </div>

        <h1 class="auth-box__title">Criar conta</h1>
        <p class="auth-box__sub">Junte-se à sua equipe no TaskFlow</p>

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
                <label for="nome">Nome Completo</label>
                <input type="text" name="nome" id="nome" required autofocus
                       autocomplete="name" placeholder="Seu nome completo">
            </div>

            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" name="email" id="email" required
                       autocomplete="email" placeholder="seu@email.com">
            </div>

            <div class="form-group">
                <label for="senha">Senha</label>
                <div style="position: relative;">
                    <input type="password" name="senha" id="senha" required
                           autocomplete="new-password" placeholder="Mínimo 6 caracteres"
                           minlength="6" style="padding-right: 44px;"
                           oninput="atualizarForca(this.value)">
                    <button type="button"
                        onclick="var i=document.getElementById('senha');if(i.type==='password'){i.type='text';this.textContent='🙈';}else{i.type='password';this.textContent='👁';}"
                        style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;font-size:16px;color:var(--text-muted);padding:4px;"
                        title="Mostrar/ocultar senha">👁</button>
                </div>
                <div id="forca-wrap" style="margin-top: 8px; display: none;">
                    <div style="height: 4px; background: var(--border); border-radius: 4px; overflow: hidden;">
                        <div id="forca-barra" style="height: 100%; width: 0%; border-radius: 4px; transition: all 0.3s;"></div>
                    </div>
                    <small id="forca-label" style="font-size: 12px; margin-top: 4px; display: block; color: var(--text-muted);"></small>
                </div>
            </div>

            <button type="submit" class="btn btn--primary btn--full" style="height:46px;font-size:15px;font-weight:600;">
                Criar Conta
            </button>

        </form>

        <div class="auth-box__footer">
            Já tem conta? <a href="?p=login">Fazer Login</a>
        </div>

    </div>
</div>

<script>
function atualizarForca(senha) {
    var wrap = document.getElementById('forca-wrap');
    var barra = document.getElementById('forca-barra');
    var label = document.getElementById('forca-label');

    if (senha.length === 0) { wrap.style.display = 'none'; return; }
    wrap.style.display = 'block';

    var pontos = 0;
    if (senha.length >= 6)  pontos++;
    if (senha.length >= 10) pontos++;
    if (/[A-Z]/.test(senha)) pontos++;
    if (/[0-9]/.test(senha)) pontos++;
    if (/[^A-Za-z0-9]/.test(senha)) pontos++;

    var niveis = [
        { pct: '20%', cor: '#ef4444', txt: 'Muito fraca' },
        { pct: '40%', cor: '#f97316', txt: 'Fraca' },
        { pct: '60%', cor: '#f59e0b', txt: 'Razoável' },
        { pct: '80%', cor: '#22c55e', txt: 'Forte' },
        { pct: '100%', cor: '#6c63ff', txt: 'Muito forte' }
    ];
    var n = niveis[Math.min(pontos, 4)];
    barra.style.width = n.pct;
    barra.style.background = n.cor;
    label.textContent = n.txt;
    label.style.color = n.cor;
}
</script>
