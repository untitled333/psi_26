<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include $_SERVER['DOCUMENT_ROOT'] . '/lapka-nadiyi/config/db.php';

$message = "";


if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    
    $query = "DELETE FROM animals WHERE id = $delete_id";
    
    if ($db->query($query)) {
        header('Location: /lapka-nadiyi/admin/index1.php?success=1');
        exit;
    } else {
        $message = "<div class='alert alert-danger'>Błąd usuwania: " . $db->error . "</div>";
    }
}

if (isset($_GET['success']) && $_GET['success'] == 1) {
    $message = "<div class='alert alert-success'>Zwierzę zostało pomyślnie usunięte z bazy danych. </div>";
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Panel Administratora — Łapka Nadziei</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold text-dark">Panel Administratora</h1>
            <p class="text-muted mb-0">Zarządzanie zwierzakami w schronisku</p>
        </div>
        <div>
            <a href="/lapka-nadiyi/index.php" class="btn btn-outline-secondary me-2">← Strona główna</a>
            <a href="add_animal.php" class="btn btn-success fw-bold"><i class="bi bi-plus-circle"></i> Dodaj nowego zwierzaka</a>
        </div>
    </div>

    <?php echo $message; ?>

    <div class="card shadow">
        <div class="card-body p-0">
            <table class="table table-hover table-striped mb-0 align-middle">
                <thead class="table-dark">
                    <tr>
                        <th class="ps-3" style="width: 8%;">ID</th>
                        <th style="width: 12%;">Foto</th>
                        <th style="width: 15%;">Imię</th>
                        <th style="width: 15%;">Wiek</th>
                        <th style="width: 30%;">Opis</th>
                        <th class="text-center" style="width: 20%;">Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $db->query("SELECT * FROM animals ORDER BY id DESC");
                    
                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $id = $row['id'];
                            $name = $row['name'];
                            $age = $row['age'];
                            $image = $row['image']; 
                            $desc = $row['description'];
                    ?>
                            <tr>
                                <td class="fw-bold ps-3">#<?php echo $id; ?></td>
                                <td>
 
                                    <img src="../img/<?php echo $image; ?>" class="rounded border shadow-sm" style="width: 60px; height: 60px; object-fit: cover;">
                                </td>
                                <td class="fw-bold text-success"><?php echo htmlspecialchars($name); ?></td>
                                <td><?php echo htmlspecialchars($age); ?></td>
                                <td>
                                    <small class="text-muted"><?php echo mb_strimwidth(htmlspecialchars($desc), 0, 70, "..."); ?></small>
                                </td>
                                <td class="text-center">
                                    <a href="edit_animal.php?id=<?php echo $id; ?>" class="btn btn-warning btn-sm fw-semibold text-dark me-1">
                                        <i class="bi bi-pencil-square"></i> Edytuj
                                    </a>
                                    
                                   
                                    <a href="index1.php?delete_id=<?php echo $id; ?>" class="btn btn-danger btn-sm fw-semibold" onclick="return confirm('Czy na pewno chcesz usunąć tego zwierzaka z bazy danych?');">
                                        <i class="bi bi-trash"></i> Usuń
                                    </a>
                                </td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center p-4 text-muted'>Baza danych jest pusta. Dodaj pierwszego zwierzaka!</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>