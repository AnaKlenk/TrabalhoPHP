<?php
namespace App\Controller;

use App\Util\Functions as Util;

class tarefaController {
    public static ?string $msg = null;
    public static ?string $msgTipo = null;
    
    private static string $arqTarefas = __DIR__ . '/../db_tarefas.json';

    private static function salvar(array $tarefas): void {
        file_put_contents(self::$arqTarefas, json_encode(array_values($tarefas), JSON_PRETTY_PRINT));
    }

    public static function listar(): array {
        if (!file_exists(self::$arqTarefas)) return [];
        return json_decode(file_get_contents(self::$arqTarefas), true) ?? [];
    }

    public static function buscarPorId(int $id): ?array {
        $tarefas = self::listar();
        foreach ($tarefas as $t) {
            if ((int)$t['id'] === $id) return $t;
        }
        return null;
    }

    public static function deletar(): void {
        $id = (int)($_GET['id'] ?? 0);
        $tarefas = self::listar();
        $novaLista = [];
        $podeApagar = false;

        foreach ($tarefas as $t) {
            if ((int)$t['id'] === $id) {
                if ((int)$t['responsavel_id'] === (int)$_SESSION['usuario_id']) {
                    $podeApagar = true;
                    continue; 
                }
            }
            $novaLista[] = $t;
        }

        if ($podeApagar) {
            self::salvar($novaLista);
            header("Location: ?p=tarefas");
        } else {
            header("Location: ?p=detalhes&id=$id&erro=sem_permissao");
        }
        exit;
    }

    public static function cadastrar(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['titulo'])) {
            $tarefas = self::listar();
            $ultimoId = empty($tarefas) ? 0 : max(array_column($tarefas, 'id'));
            
            $tarefas[] = [
                'id' => $ultimoId + 1,
                'titulo' => Util::preparaTexto($_POST['titulo']),
                'descricao' => Util::preparaTexto($_POST['descricao']),
                'data_limite' => $_POST['data_limite'],
                'status' => 'pendente',
                'criado_por' => $_SESSION['usuario_id'],
                'responsavel_id' => (int)$_POST['responsavel_id'],
                'comentarios' => [],
                'historico' => [['texto' => "Tarefa criada", 'user' => $_SESSION['usuario_nome'], 'data' => date('d/m/Y H:i')]]
            ];

            self::salvar($tarefas);
            header("Location: ?p=tarefas");
            exit;
        }
    }

    public static function atualizarStatus(): void {
        $id = (int)($_GET['id'] ?? 0);
        $novoStatus = $_GET['novo_status'] ?? '';
        $tarefas = self::listar();
        $sucesso = false;

        foreach ($tarefas as &$t) {
            if ((int)$t['id'] === $id) {
                if ($t['criado_por'] == $_SESSION['usuario_id'] || $t['responsavel_id'] == $_SESSION['usuario_id']) {
                    $t['historico'][] = ['texto' => "Status: " . Util::statusLabel($novoStatus), 'user' => $_SESSION['usuario_nome'], 'data' => date('d/m/Y H:i')];
                    $t['status'] = $novoStatus;
                    $sucesso = true;
                }
                break;
            }
        }
        if ($sucesso) self::salvar($tarefas);
        header("Location: ?p=detalhes&id=$id");
        exit;
    }

    public static function adicionarComentario(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comentario'])) {
            $id = (int)$_POST['tarefa_id'];
            $tarefas = self::listar();
            foreach ($tarefas as &$t) {
                if ((int)$t['id'] === $id) {
                    $t['comentarios'][] = ['texto' => Util::preparaTexto($_POST['comentario']), 'user' => $_SESSION['usuario_nome'], 'data' => date('d/m/Y H:i')];
                    break;
                }
            }
            self::salvar($tarefas);
            header("Location: ?p=detalhes&id=$id");
            exit;
        }
    }
}