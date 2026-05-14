<?php
use App\Util\Functions as Util;
use App\Controller\tarefaController as TarefaCtrl;

// Puxamos a lista global de tarefas do JSON através do Controller
$todasTarefas = TarefaCtrl::listar();

// Usuários da sessão para exibir a equipe e traduzir nomes
$usuarios = $_SESSION['db_usuarios'] ?? [];

// 1. RECALCULAR CONTADORES GERAIS (Garante que os cards batam com a lista)
$contadores = ['pendente' => 0, 'em_andamento' => 0, 'concluida' => 0];
foreach ($todasTarefas as $t) {
    $st = $t['status'] ?? 'pendente';
    // Normalização para garantir que conte corretamente mesmo com diferenças de caixa alta/baixa
    $stLower = strtolower($st);
    if ($stLower === 'em andamento') $stLower = 'em_andamento';
    if ($stLower === 'concluída') $stLower = 'concluida';

    if (isset($contadores[$stLower])) {
        $contadores[$stLower]++;
    }
}
$total = count($todasTarefas);
?>

<div class="stats-row">
    <div class="stat-card">
        <div class="stat-card__icon" style="background:rgba(108,99,255,.15);">📋</div>
        <div>
            <div class="stat-card__num"><?= $total ?></div>
            <div class="stat-card__label">Total de tarefas</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card__icon" style="background:var(--yellow-bg);">⏳</div>
        <div>
            <div class="stat-card__num" style="color:var(--yellow)"><?= $contadores['pendente'] ?></div>
            <div class="stat-card__label">Pendentes</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card__icon" style="background:var(--blue-bg);">🔄</div>
        <div>
            <div class="stat-card__num" style="color:var(--blue)"><?= $contadores['em_andamento'] ?></div>
            <div class="stat-card__label">Em andamento</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card__icon" style="background:var(--green-bg);">✅</div>
        <div>
            <div class="stat-card__num" style="color:var(--green)"><?= $contadores['concluida'] ?></div>
            <div class="stat-card__label">Concluídas</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card__title">RESUMO GERAL DA EQUIPE (TODAS AS TAREFAS)</div>
    <?php if (empty($todasTarefas)): ?>
    <div class="empty-state">
        <div class="empty-state__icon">📂</div>
        <div class="empty-state__title">Nenhuma tarefa cadastrada no sistema</div>
        <div class="empty-state__sub">
            <a href="?p=nova" style="color:var(--accent);font-weight:600;">Criar uma agora →</a>
        </div>
    </div>
    <?php else: ?>
    <table class="tasks-table">
        <thead>
            <tr>
                <th>TAREFA</th>
                <th>RESPONSÁVEL</th>
                <th>PRAZO</th>
                <th>STATUS</th>
                <th>AÇÃO</th>
            </tr>
        </thead>
        <tbody>
        <?php 
        // Inverte a ordem para as mais recentes aparecerem primeiro e limita a 10 no dashboard
        $exibicao = array_slice(array_reverse($todasTarefas), 0, 10);
        foreach ($exibicao as $t): 
        ?>
            <tr>
                <td>
                    <div class="task-title" style="font-weight:600;"><?= htmlspecialchars($t['titulo']) ?></div>
                    <div class="task-desc" style="font-size:12px; color:var(--text-dim);">
                        <?= htmlspecialchars(mb_substr($t['descricao'], 0, 50)) ?><?= strlen($t['descricao']) > 50 ? '...' : '' ?>
                    </div>
                </td>
                <td style="font-size: 13px;">
                    <?php
                    $nomeResp = "—";
                    foreach ($usuarios as $u) {
                        if ((int)$u['id'] === (int)$t['responsavel_id']) {
                            $nomeResp = $u['nome'];
                            break;
                        }
                    }
                    echo htmlspecialchars($nomeResp);
                    ?>
                </td>
                <td>
                    <?php if ($t['data_limite']): ?>
                        <span class="<?= Util::dataVencida($t['data_limite']) && $t['status'] !== 'concluida' ? 'date-expired' : 'date-ok' ?>">
                            <?= Util::formatarData($t['data_limite']) ?>
                        </span>
                    <?php else: ?>
                        <span style="color:var(--text-dim)">—</span>
                    <?php endif; ?>
                </td>
                <td><span class="badge <?= Util::statusClass($t['status']) ?>"><?= Util::statusLabel($t['status']) ?></span></td>
                <td>
                    <a href="?p=detalhes&id=<?= $t['id'] ?>" class="btn btn--ghost btn--sm">Ver</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <div style="margin-top:12px;">
        <a href="?p=tarefas" class="btn btn--ghost btn--sm">Gerenciar lista completa →</a>
    </div>
    <?php endif; ?>
</div>

<div class="card">
    <div class="card__title">MEMBROS DA EQUIPE</div>
    <?php foreach ($usuarios as $u): 
        $inicial = mb_strtoupper(mb_substr($u['nome'], 0, 1));
    ?>
    <div style="display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid var(--border);">
        <div class="sidebar__user-avatar" style="background:var(--primary); color:white; border-radius:50%; width:32px; height:32px; display:flex; align-items:center; justify-content:center;">
            <?= $inicial ?>
        </div>
        <div>
            <div style="font-size:14px;font-weight:600; color:var(--text);"><?= htmlspecialchars($u['nome']) ?></div>
            <div style="font-size:12px;color:var(--text-dim);"><?= htmlspecialchars($u['email']) ?></div>
        </div>
    </div>
    <?php endforeach; ?>
</div>