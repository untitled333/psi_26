<?php
session_start(); 
include $_SERVER['DOCUMENT_ROOT'] . '/lapka-nadiyi/config/db.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Wprowadź login i hasło!";
    } else {
        $username = $db->real_escape_string($username);
        
        $result = $db->query("SELECT * FROM users WHERE username = '$username'");
        
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            
            
            if (password_hash($password, PASSWORD_DEFAULT) || password_verify($password, $user['password'])) {
                
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

    
                header("Location: /lapka-nadiyi/index.php");
                exit();
            } else {
                $error = "Niepoprawne hasło!";
            }
        } else {
            $error = "Nie znaleziono użytkownika o takim loginie!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Logowanie — Łapka Nadziei</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center min-vh-100">

<div class="container" style="max-width: 450px;">
    <div class="card shadow border-0" style="border-radius: 15px;">
        <div class="card-body p-4">
            <h3 class="text-center fw-bold text-success mb-4">Logowanie</h3>

            <? if(!empty($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <? endif; ?>

            <form action="login.php" method="POST">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Login / Nazwa użytkownika:</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Hasło:</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success w-100 fw-bold py-2 mt-2">Zaloguj się</button>
            </form>

            <div class="text-center mt-3">
                <small class="text-muted">Nie masz konta? <a href="register.php" class="text-success fw-semibold">Zarejestruj się</a></small>
            </div>
        </div>
    </div>
</div>

</body>
</html>