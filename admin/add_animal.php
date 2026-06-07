<?php
// Підключаємо базу даних. Нам потрібно вийти з папки admin, тому пишемо ../
include $_SERVER['DOCUMENT_ROOT'] . '/lapka-nadiyi/config/db.php';

// Змінна для виведення повідомлень (успіх або помилка)
$message = "";

// Перевіряємо, чи була відправлена форма (чи натиснута кнопка submit)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Забираємо дані з полів форми і захищаємо від злісних хакерів
    $name = $db->real_escape_string($_POST['name']);
    $age = $db->real_escape_string($_POST['age']);
    $image = $db->real_escape_string($_POST['image']);
    $description = $db->real_escape_string($_POST['description']);

    // Перевіряємо, чи поля не пусті
    if (!empty($name) && !empty($age) && !empty($image) && !empty($description)) {
        
        // Пишемо SQL-запит для додавання (CREATE)
        $query = "INSERT INTO animals (name, age, image, description) 
                  VALUES ('$name', '$age', '$image', '$description')";
        
        // Виконуємо запит
        if ($db->query($query)) {
            $message = "<div class='alert alert-success'>Ура! Тваринку успішно додано!</div>";
        } else {
            $message = "<div class='alert alert-danger'>Помилка бази даних: " . $db->error . "</div>";
        }
    } else {
        $message = "<div class='alert alert-warning'>Будь ласка, заповніть усі поля!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Dodaj nowego podopiecznego</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5" style="max-width: 600px;">
    <a href="/LAPKA-NADIYI/index.php" class="btn btn-secondary mb-3">← Powrót do strony głównej</a>
    
    <div class="card shadow">
        <div class="card-header bg-success text-white text-center py-3">
            <h3 class="mb-0 fw-bold">Dodaj nowego zwierzaka 🐾</h3>
        </div>
        <div class="card-body p-4">
            
            <?php echo $message; ?>

            <form action="add_animal.php" method="POST">
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Imię zwierzaka:</label>
                    <input type="text" name="name" class="form-content form-control" placeholder="np. Reks" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Wiek:</label>
                    <input type="text" name="age" class="form-control" placeholder="np. 2 lata lub 5 miesięcy" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Numer obrazka (np. 12.png):</label>
                    <input type="text" name="image" class="form-control" placeholder="np. 12.png" required>
                    <small class="text-muted">Переконайся, що файл з такою назвою є в папці img!</small>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Opis zwierzaka (po polsku):</label>
                    <textarea name="description" class="form-control" rows="4" placeholder="Napisz coś o zwierzaku..." required></textarea>
                </div>

                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-success btn-lg fw-bold">Zapisz zwierzaka</button>
                </div>

            </form>

        </div>
    </div>
</div>

</body>
</html>