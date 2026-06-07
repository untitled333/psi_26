<?php
// Запускаємо сесію, щоб хедер знав, чи залогінений користувач
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Łapka Nadziei</title>
    <link rel="icon" type="image/png" href="/LAPKA-NADIYI/logos/ln.png">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/LAPKA-NADIYI/css/style.css">
</head>
<body>

<nav class="navbar navbar-light bg-light border-bottom p-3">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold text-success" href="/LAPKA-NADIYI/index.php">Łapka Nadziei <3</a>
        
        <div class="ms-auto d-flex align-items-center">
            <?php if (isset($_SESSION['username'])): ?>
                <span class="me-3 text-muted">
                    <i class="bi bi-person-check-fill text-success"></i> Witaj, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>!
                </span>
                <a href="/LAPKA-NADIYI/registration/logout.php" class="btn btn-sm btn-danger">
                    <i class="bi bi-box-arrow-right"></i> Wyloguj
                </a>
            <?php else: ?>
                <a href="/LAPKA-NADIYI/registration/login.php" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-person-circle"></i> Logowanie / Rejestracja
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">