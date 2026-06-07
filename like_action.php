<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Підключаємо базу даних. Оскільки файл в коріні, шлях прямый
include 'config/db.php';

// Готуємо заздалегідь формат відповіді (JSON)
header('Content-Type: application/json');

// Обробка помилки: якщо користувач не залогінений
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Wymagane logowanie']);
    exit;
}

// Обробка помилки: якщо не прийшов ID тваринки
if (!isset($_POST['animal_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Brak ID zwierzaka']);
    exit;
}

$user_id = intval($_SESSION['user_id']);
$animal_id = intval($_POST['animal_id']);

try {
    // Перевіряємо, чи вже є лайк від цього користувача
    $check = $db->query("SELECT id FROM likes WHERE user_id = $user_id AND animal_id = $animal_id");

    if ($check && $check->num_rows > 0) {
        // Якщо лайк є — прибираємо його
        $db->query("DELETE FROM likes WHERE user_id = $user_id AND animal_id = $animal_id");
        $action = 'unliked';
    } else {
        // Якщо немає — додаємо
        $db->query("INSERT INTO likes (user_id, animal_id) VALUES ($user_id, $animal_id)");
        $action = 'liked';
    }

    // Рахуємо актуальну кількість лайків для цієї тваринки
    $count_res = $db->query("SELECT COUNT(*) as total FROM likes WHERE animal_id = $animal_id");
    $count_row = $count_res->fetch_assoc();
    $total_likes = $count_row['total'];

    // Повертаємо успішну відповідь на фронтенд
    echo json_encode([
        'status' => 'success',
        'action' => $action,
        'likes_count' => $total_likes
    ]);

} catch (Exception $e) {
    // Обробка системних помилок бази даних
    echo json_encode(['status' => 'error', 'message' => 'Błąd bazy danych']);
}
exit;
?>