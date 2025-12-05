<?php
require_once 'config.php';

$pageTitle = 'Browse Drama';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$lang = isset($_GET['lang']) ? $_GET['lang'] : 'in';

// Get drama list from API
$response = getBrowseDrama($page, $lang);
?>

<?php include 'includes/header.php'; ?>

<div class="container">
    
    
    <!-- Drama List -->
    <div class="section">
        <div class="section-header">
            <h2 class="section-title">Latest Dramas</h2>
            <div>
                Page <?= $page ?> of <?= $response['total_page'] ?? 1 ?>
            </div>
        </div>
        
        <?php if (isset($response['status']) && $response['status']): ?>
            <?php if (!empty($response['data'])): ?>
                <div class="drama-grid">
                    <?php foreach ($response['data'] as $drama): ?>
                        <a href="detail.php?id=<?= htmlspecialchars($drama['id']) ?>" class="drama-card">
                            <img 
                                src="<?= htmlspecialchars($drama['thumbnail']) ?>" 
                                alt="<?= htmlspecialchars($drama['title']) ?>"
                                class="drama-card-image"
                                loading="lazy"
                            >
                            <div class="drama-card-content">
                                <h3 class="drama-card-title"><?= htmlspecialchars($drama['title']) ?></h3>
                                <div class="drama-card-meta">
                                    <span class="drama-card-episode"><?= htmlspecialchars($drama['episode']) ?></span>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
                
                <!-- Pagination -->
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?= $page - 1 ?>&lang=<?= $lang ?>">← Previous</a>
                    <?php endif; ?>
                    
                    <?php
                    $totalPages = $response['total_page'] ?? 1;
                    $startPage = max(1, $page - 2);
                    $endPage = min($totalPages, $page + 2);
                    
                    for ($i = $startPage; $i <= $endPage; $i++):
                    ?>
                        <?php if ($i == $page): ?>
                            <span class="active"><?= $i ?></span>
                        <?php else: ?>
                            <a href="?page=<?= $i ?>&lang=<?= $lang ?>"><?= $i ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>
                    
                    <?php if ($page < $totalPages): ?>
                        <a href="?page=<?= $page + 1 ?>&lang=<?= $lang ?>">Next →</a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="empty">
                    <h3>No dramas found</h3>
                    <p>Try searching for something else</p>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="error">
                <h3>Error loading dramas</h3>
                <p><?= htmlspecialchars($response['error'] ?? 'Unknown error') ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
