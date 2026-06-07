<?php
// Підключаємо базу даних через абсолютний шлях до папки config
include $_SERVER['DOCUMENT_ROOT'] . '/lapka-nadiyi/config/db.php';

$message = "";

// 1. ПЕРЕВІРКА: Яку саме тваринку ми хочемо редагувати?
// Ми беремо ID з адресного рядка (на przykład: edit_animal.php?id=1)
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Витягуємо поточні дані цієї тваринки з бази
    $result = $db->query("SELECT * FROM animals WHERE id = $id");
    $animal = $result->fetch_assoc();
    
    if (!$animal) {
        die("Тваринку з таким ID не знайдено!");
    }
} else {
    die("Не вказано ID тваринки для редагування!");
}

// 2. ОНОВЛЕННЯ: Якщо користувач відправив змінену форму
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $db->real_escape_string($_POST['name']);
    $age = $db->real_escape_string($_POST['age']);
    $image = $db->real_escape_string($_POST['image']);
    $description = $db->real_escape_string($_POST['description']);

    if (!empty($name) && !empty($age) && !empty($image) && !empty($description)) {
        
        // SQL-запит для оновлення (UPDATE)
        $query = "UPDATE animals SET 
                    name = '$name', 
                    age = '$age', 
                    image = '$image', 
                    description = '$description' 
                  WHERE id = $id";
        
        if ($db->query($query)) {
            $message = "<div class='alert alert-success'>Дані успішно оновлено!</div>";
            // Оновлюємо дані у змінній $animal, щоб вони одразу змінилися у формі на екрані
            $animal['name'] = $name;
            $animal['age'] = $age;
            $animal['image'] = $image;
            $animal['description'] = $description;
        } else {
            $message = "<div class='alert alert-danger'>Помилка оновлення: " . $db->error . "</div>";
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
    <title>Edytuj podopiecznego</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5" style="max-width: 600px;">
    <a href="/LAPKA-NADIYI/index.php" class="btn btn-secondary mb-3">← Powrót do strony głównej</a>
    
    <div class="card shadow">
        <div class="card-header bg-warning text-dark text-center py-3">
            <h3 class="mb-0 fw-bold">Edytuj dane zwierzaka ✏️</h3>
        </div>
        <div class="card-body p-4">
            
            <?php echo $message; ?>

            <form action="edit_animal.php?id=<?php echo $id; ?>" method="POST">
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Imię zwierzaka:</label>
                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($animal['name']); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Wiek:</label>
                    <input type="text" name="age" class="form-control" value="<?php echo htmlspecialchars($animal['age']); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Numer obrazka:</label>
                    <input type="text" name="image" class="form-control" value="<?php echo htmlspecialchars($animal['image']); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Opis zwierzaka:</label>
                    <textarea name="description" class="form-control" rows="4" required><?php echo htmlspecialchars($animal['description']); ?></textarea>
                </div>

                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-warning btn-lg fw-bold text-dark">Zapisz zmiany</button>
                </div>

            </form>

        </div>
    </div>
</div>

</body>
</html>