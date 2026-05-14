<?php
use App\Controller\tarefaController as TarefaCtrl;
use App\Util\Functions as Util;

// 1. Obtém o ID da URL e busca a tarefa via Controller
$id = (int)($_GET['id'] ?? 0);
$tarefa = TarefaCtrl::buscarPorId($id);

// 2. Se a tarefa não existir, exibe erro
if (!$tarefa): ?>
    <div class="empty-state" style="margin-top: 60px;">
        <div class="empty-state__icon">🔍</div>
        <h1 class="empty-state__title">Tarefa não encontrada</h1>
        <br>
        <a href="?p=tarefas" class="btn btn--primary">Voltar para a Lista</a>
    </div>
<?php else: ?>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px;">
        <div>
            <h2 style="font-size: 1.5rem; color: var(--text); margin: 0;"><?= htmlspecialchars($tarefa['titulo']) ?></h2>
            <p style="color: var(--text-dim); margin-top: 5px;">ID: #<?= $tarefa['id'] ?></p>
        </div>
        <span class="badge <?= Util::statusClass($tarefa['status']) ?>">
            <?= Util::statusLabel($tarefa['status']) ?>
        </span>
    </div>

    <div class="detail-grid" style="display: flex; gap: 40px; border-bottom: 1px solid var(--border); padding-bottom: 20px; margin-bottom: 20px;">
        <div style="flex: 1;">
            <span style="color: var(--text-dim); font-size: 11px; text-transform: uppercase; font-weight: bold;">Prazo de Entrega</span>
            <div style="font-weight: 600; margin-top: 5px;">
                <?= Util::formatarData($tarefa['data_limite'] ?? '') ?>
            </div>
        </div>
        <div style="flex: 1; border-left: 1px solid var(--border); padding-left: 40px;">
            <span style="color: var(--text-dim); font-size: 11px; text-transform: uppercase; font-weight: bold;">Responsável</span>
            <div style="font-weight: 600; margin-top: 5px;">
                <?php
                $respNome = "Não atribuído";
                foreach ($_SESSION['db_usuarios'] as $u) {
                    if ($u['id'] == $tarefa['responsavel_id']) { $respNome = $u['nome']; break; }
                }
                echo htmlspecialchars($respNome);
                ?>
            </div>
        </div>
    </div>

    <div style="margin-bottom: 30px;">
        <span style="color: var(--text-dim); font-size: 11px; text-transform: uppercase; font-weight: bold;">Descrição</span>
        <p style="line-height: 1.6; color: var(--text); background: rgba(255,255,255,0.03); padding: 15px; border-radius: 8px; margin-top: 8px;">
            <?= nl2br(htmlspecialchars($tarefa['descricao'] ?: 'Sem descrição informada.')) ?>
        </p>
    </div>
</div>

<div class="card" style="margin-top: 20px;">
    <div class="card__title">Histórico e Comentários</div>

    <div class="timeline" style="display: flex; flex-direction: column; gap: 20px;">
        <div>
            <div style="font-size: 12px; font-weight: bold; text-transform: uppercase; color: var(--primary); margin-bottom: 15px;">Comentários</div>
            <?php if (empty($tarefa['comentarios'])): ?>
                <p style="font-size: 13px; color: var(--text-dim);">Nenhum comentário ainda.</p>
            <?php else: ?>
                <?php foreach (array_reverse($tarefa['comentarios']) as $c): ?>
                    <div style="border-left: 3px solid var(--primary); padding: 10px 15px; background: rgba(255,255,255,0.02); border-radius: 4px; margin-bottom: 10px;">
                        <div style="font-size: 12px; color: var(--text-dim);">
                            <strong><?= htmlspecialchars($c['user']) ?></strong> • <?= $c['data'] ?>
                        </div>
                        <p style="margin-top: 5px; font-size: 14px;"><?= htmlspecialchars($c['texto']) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div>
            <div style="font-size: 12px; font-weight: bold; text-transform: uppercase; color: var(--text-dim); margin-bottom: 10px;">Log do Sistema</div>
            <ul style="list-style: none; padding: 0;">
                <?php foreach (array_reverse($tarefa['historico'] ?? []) as $h): ?>
                    <li style="font-size: 11px; color: var(--text-dim); margin-bottom: 5px; padding: 6px 10px; background: rgba(255,255,255,0.02); border-radius: 4px;">
                        📅 <?= $h['data'] ?> - <strong><?= $h['texto'] ?></strong> (por <?= $h['user'] ?>)
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <form action="" method="POST" style="margin-top: 25px; position: relative;">
        <input type="hidden" name="tarefa_id" value="<?= $tarefa['id'] ?>">
        <textarea name="comentario" placeholder="Escreva uma atualização ou comentário..." required 
            style="width: 100%; min-height: 90px; padding: 15px; border-radius: 8px; background: #1a1d21; border: 1px solid var(--border); color: white; resize: none;"></textarea>
        <button type="submit" name="btn_comentar" class="btn btn--primary" style="position: absolute; right: 15px; bottom: 15px;">Enviar</button>
    </form>
</div>

<div style="margin-top: 20px; margin-bottom: 40px;">
    <a href="?p=tarefas" class="btn btn--ghost">← Voltar para a lista</a>
</div>

<?php endif; ?>