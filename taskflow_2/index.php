<?php
/*
 * Gerenciador de Tarefas Colaborativo — TaskFlow
 * Disciplina: Desenvolvimento Web com PHP
 * Professor: João Paulo Nunes da Silva
 *
 * Integrantes do Grupo:
 *   Ana Júlia Bernardino Klenk: 43455301
 *   André Felipe Lima de Almeida RGM: 42970253
 *   João Pedro Galdino e Silva RGM: 42661315
 *   Kauane Aparecida Machado Alves RGM: 42917646
 *   Nicolly dos Santos Pereira RGM: 42634229
 *   Thiago Kauan Cardozo da Silva RGM: 43725694
 */
declare(strict_types=1);
namespace App;

spl_autoload_register(function (string $class): void {
    $base = __DIR__ . '/';
    $map  = [
        'App\\Controller\\' => 'controller/',
        'App\\Util\\'       => 'util/',
        'App\\Model\\'      => 'model/'
    ];
    foreach ($map as $prefix => $dir) {
        if (str_starts_with($class, $prefix)) {
            $rel  = substr($class, strlen($prefix));
            $file = $base . $dir . str_replace('\\', '/', $rel) . '.php';
            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
    }
});

session_start();

if (file_exists(__DIR__ . '/db_usuarios.json')) {
    $_SESSION['db_usuarios'] = json_decode(file_get_contents(__DIR__ . '/db_usuarios.json'), true);
}

if (isset($_GET['atualizar_status'])) {
    \App\Controller\tarefaController::atualizarStatus();
}

if (isset($_GET['p']) && $_GET['p'] === 'deletar_tarefa') {
    \App\Controller\tarefaController::deletar();
}

if (isset($_POST['btn_comentar'])) {
    \App\Controller\tarefaController::adicionarComentario();
}


if (!isset($_SESSION['db_usuarios'])) $_SESSION['db_usuarios'] = [];
if (!isset($_SESSION['db_tarefas']))  $_SESSION['db_tarefas'] = [];

$page = $_GET['p'] ?? 'login';

// LOGOUT
if ($page === 'logout') {
    session_destroy();
    setcookie('ultimo_email', '', time() - 3600, '/');
    header("Location: ?p=login");
    exit;
}

$publicas = ['login', 'cadastro'];
if (!isset($_SESSION['usuario_id']) && !in_array($page, $publicas)) {
    header("Location: ?p=login");
    exit;
}

$titulos = [
    'home'           => ['Dashboard', 'Bem-vindo'],
    'tarefas'        => ['Tarefas', 'Lista de atividades'],
    'nova'           => ['Nova Tarefa', 'Criar atividade'],
    'detalhes'       => ['Detalhes da Tarefa', 'Informações da atividade']
];
$info = $titulos[$page] ?? ['404', 'Página não encontrada'];

$arquivos = [
    'login'    => 'view/login.php',
    'cadastro' => 'view/cadastro.php',
    'home'     => 'view/home.php',
    'tarefas'  => 'view/tarefas.php',
    'nova'     => 'view/nova_tarefa.php',
    'detalhes' => 'view/detalhes.php'
];
$viewFile = $arquivos[$page] ?? null;

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>TaskFlow - <?= $info[0] ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<?php if (in_array($page, $publicas)): ?>
    <?php if ($viewFile && file_exists(__DIR__ . '/' . $viewFile)) include __DIR__ . '/' . $viewFile; else echo "Arquivo $viewFile não encontrado."; ?>
<?php else: ?>
    <div class="layout">
        <aside class="sidebar" style="display: flex; flex-direction: column;">
    <div class="sidebar__header">
        <div class="logo">Task<span>Flow</span></div>
    </div>
    
    <div style="flex: 1;">
        <?php include 'menu.php'; ?>
    </div>

    <div class="sidebar__footer" style="padding: 20px; border-top: 1px solid var(--border);">
        <a href="?p=logout" class="nav-link" style="color: var(--danger); display: flex; align-items: center; gap: 12px; text-decoration: none;">
            <i class="icon-logout"></i> <span>Sair</span>
        </a>
    </div>
</aside>
        <div class="main">
            <header class="topbar">
                <h1><?= $info[0] ?></h1>
            </header>
            <main class="page-content">
                <?php 
                if ($viewFile && file_exists(__DIR__ . '/' . $viewFile)) {
                    include __DIR__ . '/' . $viewFile;
                } else {
                    echo "<h2>Erro 404</h2><p>O arquivo <b>$viewFile</b> não foi encontrado na pasta view.</p>";
                }
                ?>
            </main>
        </div>
    </div>
<?php endif; ?>

</body>
</html>