<?php
declare(strict_types=1);
namespace App;

// Autoload para carregar os Controllers
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

// No seu index.php, logo após o session_start()
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



// Garante que os arrays existam na sessão
if (!isset($_SESSION['db_usuarios'])) $_SESSION['db_usuarios'] = [];
if (!isset($_SESSION['db_tarefas']))  $_SESSION['db_tarefas'] = [];

// Pega a página da URL (?p=nome_da_pagina)
$page = $_GET['p'] ?? 'login';

// LOGOUT
if ($page === 'logout') {
    session_destroy();
    header("Location: ?p=login");
    exit;
}

// PROTEÇÃO: Se não está logado, só acessa login ou cadastro
$publicas = ['login', 'cadastro'];
if (!isset($_SESSION['usuario_id']) && !in_array($page, $publicas)) {
    header("Location: ?p=login");
    exit;
}

// Configurações de Título
// Configurações de Título
$titulos = [
    'home'           => ['Dashboard', 'Bem-vindo'],
    'tarefas'        => ['Tarefas', 'Lista de atividades'],
    'nova'           => ['Nova Tarefa', 'Criar atividade'],
    'detalhes'       => ['Detalhes da Tarefa', 'Informações da atividade']
];
$info = $titulos[$page] ?? ['404', 'Página não encontrada'];

// --- IMPORTANTE: MAPEAMENTO DE ARQUIVOS ---
// Aqui dizemos ao PHP qual arquivo abrir para cada valor de 'p'
$arquivos = [
    'login'    => 'view/login.php',
    'cadastro' => 'view/cadastro.php',
    'home'     => 'view/home.php',
    'tarefas'  => 'view/tarefas.php',
    'nova'     => 'view/nova_tarefa.php',
    'detalhes' => 'view/detalhes.php' // ADICIONE ESTA LINHA
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