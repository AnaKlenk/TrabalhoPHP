<?php
use App\Util\Functions as Util;
use App\Controller\tarefaController as TarefaCtrl;

$todasTarefas = TarefaCtrl::listar();
$usuarios = $_SESSION['db_usuarios'] ?? [];


$fStatus = $_GET['status']      ?? '';
$fResp   = $_GET['responsavel'] ?? '';
$fData   = $_GET['data_limite'] ?? '';

$tarefasFiltradas = array_filter($todasTarefas, function($t) use ($fStatus, $fResp, $fData) {
    $matchStatus = ($fStatus == '' || $t['status'] == $fStatus);
    $matchResp   = ($fResp   == '' || (int)$t['responsavel_id'] === (int)$fResp);
    $matchData   = ($fData   == '' || $t['data_limite'] == $fData);
    return $matchStatus && $matchResp && $matchData;
});
?>

<div class="card" style="margin-bottom: 20px;">
    <form method="GET" style="display: flex; gap: 15px; align-items: flex-end;">
        <input type="hidden" name="p" value="tarefas">
        
        <div style="flex: 1;">
            <label style="font-size: 11px; color: var(--text-dim); font-weight: bold; text-transform: uppercase;">Filtrar Status</label>
            <select name="status" style="width:100%; background:#1a1d21; color:white; border:1px solid var(--border); padding:10px; border-radius:6px; margin-top:5px;">
                <option value="">Todos os Status</option>
                <option value="pendente" <?= $fStatus == 'pendente' ? 'selected' : '' ?>>Pendente</option>
                <option value="em_andamento" <?= $fStatus == 'em_andamento' ? 'selected' : '' ?>>Em Andamento</option>
                <option value="concluida" <?= $fStatus == 'concluida' ? 'selected' : '' ?>>Concluída</option>
            </select>
        </div>

        <div style="flex: 1;">
            <label style="font-size: 11px; color: var(--text-dim); font-weight: bold; text-transform: uppercase;">Filtrar Responsável</label>
            <select name="responsavel" style="width:100%; background:#1a1d21; color:white; border:1px solid var(--border); padding:10px; border-radius:6px; margin-top:5px;">
                <option value="">Todos os Responsáveis</option>
                <?php foreach ($usuarios as $u): ?>
                    <option value="<?= $u['id'] ?>" <?= $fResp == $u['id'] ? 'selected' : '' ?>><?= htmlspecialchars($u['nome']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div style="flex: 1;">
            <label style="font-size: 11px; color: var(--text-dim); font-weight: bold; text-transform: uppercase;">Filtrar Data Limite</label>
            <input type="date" name="data_limite" value="<?= htmlspecialchars($fData) ?>"
                   style="width:100%; background:#1a1d21; color:white; border:1px solid var(--border); padding:10px; border-radius:6px; margin-top:5px;">
        </div>

        <button type="submit" class="btn btn--primary" style="padding: 11px 25px;">Filtrar</button>
        <a href="?p=tarefas" class="btn btn--ghost" style="padding: 11px 20px;">Limpar</a>
    </form>
</div>

<div class="card">
    <div class="card__title">Resumo Geral da Equipe</div>
    <table class="tasks-table">
        <thead>
            <tr>
                <th>Tarefa</th>
                <th>Responsável</th>
                <th>Prazo</th>
                <th>Status</th>
                <th>Ação</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tarefasFiltradas as $t): ?>
            <tr>
                <td>
                    <strong><?= htmlspecialchars($t['titulo']) ?></strong>
                    <div style="font-size: 11px; color: var(--text-dim);"><?= htmlspecialchars(mb_substr($t['descricao'], 0, 40)) ?>...</div>
                </td>
                <td>
                    <?php 
                        foreach($usuarios as $u) { if($u['id'] == $t['responsavel_id']) echo htmlspecialchars($u['nome']); }
                    ?>
                </td>
                <td><?= Util::formatarData($t['data_limite']) ?></td>
                <td><span class="badge <?= Util::statusClass($t['status']) ?>"><?= Util::statusLabel($t['status']) ?></span></td>
                <td><a href="?p=detalhes&id=<?= $t['id'] ?>" class="btn btn--ghost btn--sm">Ver</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>