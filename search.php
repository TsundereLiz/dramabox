<?php
require_once 'config.php';

$pageTitle = 'Search Drama';
$query = isset($_GET['q']) ? trim($_GET['q']) : '';
$lang = isset($_GET['lang']) ? $_GET['lang'] : 'in';
$page = isset($_GET['p']) ? (int)$_GET['p'] : 1;

$response = null;
if (!empty($query)) {
    // Search drama from API
    $response = searchDrama($query, $lang, $page);
}
?>

<?php include 'includes/header.php'; ?>

<div class="container">
    
    <!-- Search Results -->
    <?php if (!empty($query)): ?>
        <div class="section">
            <div class="section-header">
                <h2 class="section-title">
                    Search Results for "<?= htmlspecialchars($query) ?>"
                </h2>
                <?php if (isset($response['total_result'])): ?>
                    <div>
                        <?= number_format($response['total_result']) ?> results found
                    </div>
                <?php endif; ?>
            </div>
            
            <?php if (isset($response['status']) && $response['status']): ?>
                <?php if (!empty($response['data'])): ?>
                    <div class="drama-grid">
                        <?php foreach ($response['data'] as $drama): ?>
                            <?php
                            // Extract book ID from URL
                            $bookId = '';
                            if (isset($drama['url'])) {
                                parse_str(parse_url($drama['url'], PHP_URL_QUERY), $params);
                                $bookId = $params['q'] ?? '';
                            }
                            ?>
                            
                            <?php if ($bookId): ?>
                                <a href="detail.php?id=<?= htmlspecialchars($bookId) ?>" class="drama-card">
                                    <img 
                                        src="<?= htmlspecialchars($drama['thumbnail']) ?>" 
                                        alt="<?= htmlspecialchars($drama['title']) ?>"
                                        class="drama-card-image"
                                        loading="lazy"
                                    >
                                    <div class="drama-card-content">
                                        <h3 class="drama-card-title"><?= htmlspecialchars($drama['title']) ?></h3>
                                        
                                        <?php if (!empty($drama['description'])): ?>
                                            <p style="font-size: 0.85rem; color: #666; margin: 0.5rem 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                                <?= htmlspecialchars($drama['description']) ?>
                                            </p>
                                        <?php endif; ?>
                                        
                                        <div class="drama-card-meta">
                                            <span class="drama-card-episode"><?= htmlspecialchars($drama['episode']) ?></span>
                                        </div>
                                    </div>
                                </a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if (isset($response['total_page']) && $response['total_page'] > 1): ?>
                        <div class="pagination">
                            <?php if ($page > 1): ?>
                                <a href="?q=<?= urlencode($query) ?>&lang=<?= $lang ?>&p=<?= $page - 1 ?>">← Previous</a>
                            <?php endif; ?>
                            
                            <?php
                            $totalPages = $response['total_page'];
                            $startPage = max(1, $page - 2);
                            $endPage = min($totalPages, $page + 2);
                            
                            for ($i = $startPage; $i <= $endPage; $i++):
                            ?>
                                <?php if ($i == $page): ?>
                                    <span class="active"><?= $i ?></span>
                                <?php else: ?>
                                    <a href="?q=<?= urlencode($query) ?>&lang=<?= $lang ?>&p=<?= $i ?>"><?= $i ?></a>
                                <?php endif; ?>
                            <?php endfor; ?>
                            
                            <?php if ($page < $totalPages): ?>
                                <a href="?q=<?= urlencode($query) ?>&lang=<?= $lang ?>&p=<?= $page + 1 ?>">Next →</a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="empty">
                        <h3>No results found</h3>
                        <p>Try searching with different keywords</p>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="error">
                    <h3>Error searching dramas</h3>
                    <p><?= htmlspecialchars($response['error'] ?? 'Unknown error') ?></p>
                </div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="section">
            <div class="empty">
                <h3>Start searching</h3>
                <p>Enter a keyword above to find dramas</p>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>

