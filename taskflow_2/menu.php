<?php
$p = $_GET['p'] ?? 'home';
?>
<nav class="sidebar__nav">
    <div class="sidebar__nav-label">Menu</div>
    <a href="./?p=home"     class="nav-item <?= $p === 'home'    ? 'active' : '' ?>">
        <span class="icon">⬡</span> Início
    </a>
    <a href="./?p=tarefas"  class="nav-item <?= $p === 'tarefas' ? 'active' : '' ?>">
        <span class="icon">☰</span> Tarefas
    </a>
    <a href="./?p=nova"     class="nav-item <?= $p === 'nova'    ? 'active' : '' ?>">
        <span class="icon">＋</span> Nova Tarefa
    </a>
</nav>
