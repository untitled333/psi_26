<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


include 'config/db.php';


header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Wymagane logowanie']);
    exit;
}

if (!isset($_POST['animal_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Brak ID zwierzaka']);
    exit;
}

$user_id = intval($_SESSION['user_id']);
$animal_id = intval($_POST['animal_id']);

try {
    $check = $db->query("SELECT id FROM likes WHERE user_id = $user_id AND animal_id = $animal_id");

    if ($check && $check->num_rows > 0) {
        
        $db->query("DELETE FROM likes WHERE user_id = $user_id AND animal_id = $animal_id");
        $action = 'unliked';
    } else {
        
        $db->query("INSERT INTO likes (user_id, animal_id) VALUES ($user_id, $animal_id)");
        $action = 'liked';
    }

    
    $count_res = $db->query("SELECT COUNT(*) as total FROM likes WHERE animal_id = $animal_id");
    $count_row = $count_res->fetch_assoc();
    $total_likes = $count_row['total'];

    
    echo json_encode([
        'status' => 'success',
        'action' => $action,
        'likes_count' => $total_likes
    ]);

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Błąd bazy danych']);
}
exit;
?>