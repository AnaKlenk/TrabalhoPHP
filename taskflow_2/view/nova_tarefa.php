<?php
use App\Controller\tarefaController as TarefaCtrl;

// Executa a lógica de cadastro
TarefaCtrl::cadastrar();

// Puxa os usuários da sessão para o select de responsáveis
$usuarios = $_SESSION['db_usuarios'] ?? [];
?>

<div class="card" style="max-width: 680px;">
    <div class="card__title">Nova Tarefa</div>

    <?php if (TarefaCtrl::$msg): ?>
    <div class="alert <?= TarefaCtrl::$msgTipo ?>">
        <?= TarefaCtrl::$msg ?>
        <span class="close" onclick="this.parentElement.style.display='none'">&times;</span>
    </div>
    <?php endif; ?>

    <form action="?p=nova" method="post" novalidate>
        <input type="hidden" name="acao" value="nova_tarefa">

        <div class="form-group">
            <label for="titulo">Título <span style="color:var(--red)">*</span></label>
            <input type="text" id="titulo" name="titulo" placeholder="O que precisa ser feito?" required autofocus>
        </div>

        <div class="form-group">
            <label for="descricao">Descrição</label>
            <textarea id="descricao" name="descricao" placeholder="Detalhes sobre a tarefa..."></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="data_limite">Data limite</label>
                <input type="date" id="data_limite" name="data_limite" min="<?= date('Y-m-d') ?>">
            </div>
            <div class="form-group">
                <label for="responsavel_id">Responsável <span style="color:var(--red)">*</span></label>
                <select id="responsavel_id" name="responsavel_id" required>
                    <option value="">— Selecione —</option>
                    <?php foreach ($usuarios as $u): ?>
                    <option value="<?= $u['id'] ?>"
                        <?= $u['id'] == $_SESSION['usuario_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($u['nome']) ?>
                        <?= $u['id'] == $_SESSION['usuario_id'] ? '(você)' : '' ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div style="display:flex;gap:10px;margin-top:8px;">
            <button type="submit" class="btn btn--primary">Criar Tarefa</button>
            <a href="?p=tarefas" class="btn btn--ghost">Cancelar</a>
        </div>
    </form>
</div>