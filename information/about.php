<?php 
include '../includes/header.php'; 
include '../includes/sidebar.php'; 
// Підключаємо базу даних (папка config лежить на рівень вище, тому ../)
include '../config/db.php'; 
?>

<main class="col-md-10 p-4">
    <div class="mb-5">
        <h2 class="fw-bold text-success mb-3">O nas — Łapka Nadziei 🐾</h2>
        <p class="fs-5 text-dark">
            Naszą misją jest pomoc bezdomnym i skrzywdzonym zwierzętom. Zapewniamy im bezpieczne schronienie, 
            opiekę weterynaryjną oraz szukamy dla nich nowych, kochających domów. Każdego dnia nasi wolontariusze 
            i pracownicy dbają o to, aby ogonki machały ze szczęścia!
        </p>
    </div>

    <hr class="my-5">

    <h3 class="fw-bold text-dark mb-4">Wskazówki i Aktualności od naszego zespołu 📰</h3>
    
    <div class="row row-cols-1 row-cols-md-2 g-4">
        <?php
        // Наша магія об'єднання таблиць (LEFT JOIN)
        $query = "SELECT posts.*, authors.author_name, authors.author_role 
                  FROM posts 
                  LEFT JOIN authors ON posts.author_id = authors.id 
                  ORDER BY posts.id DESC";
        $result = $db->query($query);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
        ?>
                <div class="col">
                    <div class="card h-100 shadow-sm border-0 bg-white p-3" style="border-radius: 12px;">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div>
                                <h4 class="fw-bold text-success mb-3"><?php echo htmlspecialchars($row['title']); ?></h4>
                                <p class="text-secondary fs-6"><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
                            </div>
                            
                            <div class="mt-4 pt-3 border-top d-flex justify-content-between align-items-center text-muted small">
                                <div>
                                    <i class="bi bi-person-circle text-success"></i> Autor: 
                                    <strong><?php echo htmlspecialchars($row['author_name']); ?></strong>
                                    <br>
                                    <span class="text-success fw-semibold">(<?php echo htmlspecialchars($row['author_role']); ?>)</span>
                                </div>
                                <div>
                                    <i class="bi bi-calendar-event"></i> <?php echo $row['post_date']; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        <?php
            }
        } else {
            echo "<div class='col-12'><p class='text-muted'>Brak artykułów do wyświetlenia. Dodaj coś przez panel admina!</p></div>";
        }
        ?>
    </div>
</main>

<?php 
include '../includes/footer.php'; 
?>