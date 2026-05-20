<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spotify Artists Explorer</title>
    
    <!-- Vložení layout komponenty pro CSS -->
    <?= $this->include('layouts/css') ?>
    
    <!-- Prostor pro případné styly specifické pro konkrétní view -->
    <?= $this->renderSection('styles') ?>
</head>
<body class="bg-dark text-light">
    
    <!-- Vložení navigační lišty -->
    <?= $this->include('layouts/navbar') ?>

    <main class="container">
        <!-- Zde se vykreslí hlavní obsah daného view -->
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Vložení layout komponenty pro JS skripty (Bootstrap Bundle) -->
    <?= $this->include('layouts/scripts') ?>
    
    <!-- Prostor pro případné JS specifické pro konkrétní view -->
    <?= $this->renderSection('scripts') ?>
</body>
</html>
