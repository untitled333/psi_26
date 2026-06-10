<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'includes/header.php'; 
include 'includes/sidebar.php'; 
include 'config/db.php'; 

$search = isset($_GET['search']) ? $db->real_escape_string($_GET['search']) : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'latest';
$current_user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;

$query = "SELECT animals.*, 
          COUNT(likes.id) AS total_likes,
          SUM(CASE WHEN likes.user_id = $current_user_id THEN 1 ELSE 0 END) AS user_liked
          FROM animals 
          LEFT JOIN likes ON animals.id = likes.animal_id";

if (!empty($search)) {
    $query .= " WHERE animals.name LIKE '%$search%'";
}

$query .= " GROUP BY animals.id";

if ($sort == 'popular') {
    $query .= " ORDER BY total_likes DESC, animals.id DESC";
} else {
    $query .= " ORDER BY animals.id DESC";
}

$result = $db->query($query);
?>

<main class="col-md-10 p-4">
    <div class="row g-3 mb-4 align-items-center">
        <div class="col-md-4">
            <h2 class="fw-bold mb-0">Nasi podopieczni</h2>
        </div>
        <div class="col-md-8">
            <form action="index.php" method="GET" class="row g-2 justify-content-md-end">
                <div class="col-sm-5">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Szukaj imienia..." value="<?php echo htmlspecialchars($search); ?>">
                        <button class="btn btn-success" type="submit"><i class="bi bi-search"></i></button>
                    </div>
                </div>
                <div class="col-sm-4">
                    <select name="sort" class="form-select" onchange="this.form.submit()">
                        <option value="latest" <?php echo $sort == 'latest' ? 'selected' : ''; ?>>Najnowsze</option>
                        <option value="popular" <?php echo $sort == 'popular' ? 'selected' : ''; ?>>Najpopularniejsze</option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <div class="row row-cols-1 row-cols-md-5 g-3">
        <?php
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $id = $row['id'];
                $name = $row['name'];
                $age = $row['age'];
                $image = $row['image'];
                $desc = $row['description'];
                $total_likes = $row['total_likes'];
                $user_liked = $row['user_liked'];
        ?>
                <div class="col">
                    <div class="card h-100 shadow-sm border-0 position-relative" style="border-radius: 12px; overflow: hidden;">
                        
                        <div class="position-absolute top-0 end-0 p-2" style="z-index: 10;">
                            <?php if ($current_user_id > 0): ?>
                                <button class="btn btn-light btn-sm rounded-circle shadow-sm like-btn" data-id="<?php echo $id; ?>">
                                    <i class="bi <?php echo $user_liked ? 'bi-heart-fill text-danger' : 'bi-heart'; ?> fs-5"></i>
                                </button>
                            <?php else: ?>
                                <span class="badge bg-light text-muted shadow-sm p-2" title="Zaloguj się, aby polubić">
                                    <i class="bi bi-heart text-secondary"></i>
                                </span>
                            <?php endif; ?>
                        </div>

                        <img src="img/<?php echo $image; ?>" class="card-img-top" alt="Zwierzak" style="height: 220px; object-fit: cover; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#animalModal<?php echo $id; ?>">
                        
                        <div class="card-body text-center d-flex flex-column justify-content-between">
                            <div>
                                <h5 class="card-title fw-bold mb-1"><?php echo htmlspecialchars($name); ?></h5>
                                <p class="card-text text-muted small mb-2">Wiek: <?php echo htmlspecialchars($age); ?></p>
                            </div>
                            <div class="text-muted small border-top pt-2 mt-2">
                                <i class="bi bi-heart-fill text-danger"></i> Polubienia: <span id="like-count-<?php echo $id; ?>"><?php echo $total_likes; ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="animalModal<?php echo $id; ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title fw-bold"><?php echo htmlspecialchars($name); ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row align-items-center">
                                    <div class="col-md-5 text-center mb-3 mb-md-0">
                                        <img src="img/<?php echo $image; ?>" class="img-fluid rounded shadow-sm" style="max-height: 300px; object-fit: cover;">
                                    </div>
                                    <div class="col-md-7">
                                        <h4 class="fw-bold text-success mb-2"><?php echo htmlspecialchars($name); ?></h4>
                                        <p class="text-muted fw-semibold mb-3">Wiek: <?php echo htmlspecialchars($age); ?></p>
                                        <hr>
                                        <p class="text-dark fs-5"><?php echo nl2br(htmlspecialchars($desc)); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        <?php 
            } 
        } else {
            echo "<div class='col-12 text-center'><p class='text-muted fs-5 my-5'>Nie znaleziono zwierzaków.</p></div>";
        }
        ?>
    </div>
</main>

<script>
document.querySelectorAll('.like-btn').forEach(button => {
    button.addEventListener('click', function() {
        const animalId = this.getAttribute('data-id');
        const icon = this.querySelector('i');
        const countSpan = document.getElementById(`like-count-${animalId}`);

        const formData = new FormData();
        formData.append('animal_id', animalId);

        fetch('/lapka-nadiyi/like_action.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                countSpan.innerText = data.likes_count;

               
                if (data.action === 'liked') {
                    icon.classList.remove('bi-heart');
                    icon.classList.add('bi-heart-fill', 'text-danger');
                } else {
                    icon.classList.remove('bi-heart-fill', 'text-danger');
                    icon.classList.add('bi-heart');
                }
            } else {
                alert('Błąd: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Wystąpił błąd podczas wysyłania zapytania.');
        });
    });
});
</script>

<?php include 'includes/footer.php'; ?>