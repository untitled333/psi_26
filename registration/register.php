<?php
include $_SERVER['DOCUMENT_ROOT'] . '/lapka-nadiyi/config/db.php';

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    
    if (empty($username) || empty($email) || empty($password)) {
        $error = "Wszystkie pola są wymagane!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Niepoprawny format adresu e-mail!";
    } elseif (strlen($password) < 6) {
        $error = "Hasło musi mieć co najmniej 6 znaków!";
    } else {
        $username = $db->real_escape_string($username);
        $email = $db->real_escape_string($email);

        $check_user = $db->query("SELECT id FROM users WHERE username='$username' OR email='$email'");
        if ($check_user->num_rows > 0) {
            $error = "Użytkownik o takiej nazwie lub e-mailu już istnieje!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
            
            if ($db->query($query)) {
                $success = "Rejestracja udana! Możesz się teraz zalogować.";
            } else {
                $error = "Błąd rejestracji: " . $db->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Rejestracja — Łapka Nadziei</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center min-vh-100">

<div class="container" style="max-width: 450px;">
    <div class="card shadow border-0" style="border-radius: 15px;">
        <div class="card-body p-4">
            <h3 class="text-center fw-bold text-success mb-4">Stwórz konto </h3>

            <?php if(!empty($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if(!empty($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <div id="js-alert" class="alert alert-danger d-none"></div>

            <form action="register.php" method="POST" id="registerForm">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nazwa użytkownika (Login):</label>
                    <input type="text" name="username" id="username" class="form-control" placeholder="np. khrystyna">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Adres E-mail:</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="np. user@mail.com">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Hasło:</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Min. 6 znaków">
                </div>
                <button type="submit" class="btn btn-success w-100 fw-bold py-2 mt-2">Zarejestruj się</button>
            </form>

            <div class="text-center mt-3">
                <small class="text-muted">Masz już konto? <a href="login.php" class="text-success fw-semibold">Zaloguj się</a></small>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('registerForm').addEventListener('submit', function(event) {
    
    const usernameInput = document.getElementById('username');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const jsAlert = document.getElementById('js-alert');

    const username = usernameInput.value.trim();
    const email = emailInput.value.trim();
    const password = passwordInput.value;

    let errorMessage = "";

    
    if (username === "" || email === "" || password === "") {
        errorMessage = "JavaScript: Wszystkie pola muszą być wypełnione!";
    } else if (username.length < 3) {
        errorMessage = "JavaScript: Login musi mieć co najmniej 3 znaki!";
    } else if (password.length < 6) {
        errorMessage = "JavaScript: Hasło musi mieć co najmniej 6 znaków!";
    }

    
    if (errorMessage !== "") {
        event.preventDefault(); 
        
        jsAlert.innerText = errorMessage;
        jsAlert.classList.remove(' d-none');
        
        
        if (password.length < 6) {
            passwordInput.classList.add('is-invalid');
        } else if (username.length < 3) {
            usernameInput.classList.add('is-invalid');
        }
    } else {
        
        jsAlert.classList.add('d-none');
        usernameInput.classList.remove('is-invalid');
        passwordInput.classList.remove('is-invalid');
    }
});

document.getElementById('password').addEventListener('input', function() {
    this.classList.remove('is-invalid');
});
document.getElementById('username').addEventListener('input', function() {
    this.classList.remove('is-invalid');
});
</script>

</body>
</html>