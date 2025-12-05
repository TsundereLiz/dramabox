<?php
require_once 'config.php';

$bookId = isset($_GET['id']) ? $_GET['id'] : null;

if (!$bookId) {
    header('Location: index.php');
    exit;
}

$language = isset($_GET['lang']) ? $_GET['lang'] : 'id';

// Get drama detail from API
$response = getDramaDetail($bookId, $language);

if (!isset($response['success']) || !$response['success']) {
    header('Location: index.php');
    exit;
}

$drama = $response['data']['dramaInfo'];
$chapters = $response['data']['chapters'];

$pageTitle = $drama['bookName'];
?>

<?php include 'includes/header.php'; ?>

<div class="container">
    <div class="drama-detail">
        <!-- Drama Header -->
        <div class="drama-detail-header">
            <img 
                src="<?= htmlspecialchars($drama['cover']) ?>" 
                alt="<?= htmlspecialchars($drama['bookName']) ?>"
                class="drama-detail-cover"
            >
            
            <div class="drama-detail-info">
                <h1><?= htmlspecialchars($drama['bookName']) ?></h1>
                
                <div class="drama-detail-meta">
                    <div class="meta-item">
                        <span class="meta-label">Episodes</span>
                        <span class="meta-value"><?= $drama['chapterCount'] ?></span>
                    </div>
                    
                    <div class="meta-item">
                        <span class="meta-label">Followers</span>
                        <span class="meta-value"><?= number_format($drama['followCount']) ?></span>
                    </div>
                    
                    <div class="meta-item">
                        <span class="meta-label">Language</span>
                        <span class="meta-value"><?= strtoupper($drama['language']) ?></span>
                    </div>
                    
                    <?php if (!empty($drama['shelfTime'])): ?>
                    <div class="meta-item">
                        <span class="meta-label">Release Date</span>
                        <span class="meta-value"><?= date('M d, Y', strtotime($drama['shelfTime'])) ?></span>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="drama-detail-description">
                    <p><?= nl2br(htmlspecialchars($drama['introduction'])) ?></p>
                </div>
                
                <?php if (!empty($drama['labels']) || !empty($drama['tags'])): ?>
                <div class="drama-tags">
                    <?php 
                    $allTags = array_merge($drama['labels'] ?? [], $drama['tags'] ?? []);
                    foreach ($allTags as $tag): 
                    ?>
                        <span class="tag"><?= htmlspecialchars($tag) ?></span>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Episodes List -->
        <div class="episodes-section">
            <h2 class="episodes-title">Episodes (<?= count($chapters) ?>)</h2>
            
            <div class="episodes-grid">
                <?php foreach ($chapters as $arrayIndex => $chapter): ?>
                    <?php 
                    // Always use sequential numbering starting from 1
                    $episodeNumber = $arrayIndex + 1;
                    ?>
                    <div class="episode-card">
                        <div class="episode-card-header">
                            <span class="episode-number">Episode <?= $episodeNumber ?></span>
                        </div>
                        
                        <?php if (!empty($chapter['mp4'])): ?>
                            <button 
                                class="episode-play"
                                onclick="playVideo(<?= $arrayIndex ?>, '<?= htmlspecialchars($chapter['name'], ENT_QUOTES) ?>', '<?= htmlspecialchars($chapter['mp4'], ENT_QUOTES) ?>', <?= $episodeNumber ?>)"
                            >
                                ▶ Play Episode
                            </button>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- Video Player Modal -->
<div id="videoModal" class="video-modal">
    <div class="video-modal-content">
        <div class="video-modal-header">
            <h3 id="videoTitle">Episode Title</h3>
            <button class="video-modal-close" onclick="closeVideoModal()">&times;</button>
        </div>
        
        <div class="video-player-wrapper">
            <video 
                id="videoPlayer" 
                controls 
                autoplay
                controlsList="nodownload"
            >
                <source id="videoSource" src="" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
        
        <div class="video-modal-controls">
            <button id="prevEpisode" class="video-nav-btn" onclick="playPreviousEpisode()">
                ← Previous Episode
            </button>
            <div class="auto-next-toggle">
                <label>
                    <input type="checkbox" id="autoNext" checked>
                    Auto Next Episode
                </label>
            </div>
            <button id="nextEpisode" class="video-nav-btn" onclick="playNextEpisode()">
                Next Episode →
            </button>
        </div>
    </div>
</div>

<script>
// Store episodes data
const episodes = <?= json_encode($chapters) ?>;
let currentEpisodeIndex = 0;

function playVideo(index, title, videoUrl, episodeNumber) {
    currentEpisodeIndex = index;
    
    // Calculate episode number from array index (always starts from 1)
    const displayEpisodeNumber = index + 1;
    
    // Update modal
    document.getElementById('videoTitle').textContent = `Episode ${displayEpisodeNumber}`;
    document.getElementById('videoSource').src = videoUrl;
    
    // Load and play video
    const video = document.getElementById('videoPlayer');
    video.load();
    video.play();
    
    // Show modal
    document.getElementById('videoModal').classList.add('show');
    
    // Update navigation buttons
    updateNavigationButtons();
    
    // Add event listener for video end
    video.onended = function() {
        if (document.getElementById('autoNext').checked) {
            playNextEpisode();
        }
    };
}

function closeVideoModal() {
    const video = document.getElementById('videoPlayer');
    video.pause();
    document.getElementById('videoModal').classList.remove('show');
}

function playNextEpisode() {
    if (currentEpisodeIndex < episodes.length - 1) {
        currentEpisodeIndex++;
        const episode = episodes[currentEpisodeIndex];
        
        if (episode.mp4) {
            playVideo(
                currentEpisodeIndex,
                episode.name,
                episode.mp4,
                currentEpisodeIndex + 1  // Sequential episode number
            );
        }
    }
}

function playPreviousEpisode() {
    if (currentEpisodeIndex > 0) {
        currentEpisodeIndex--;
        const episode = episodes[currentEpisodeIndex];
        
        if (episode.mp4) {
            playVideo(
                currentEpisodeIndex,
                episode.name,
                episode.mp4,
                currentEpisodeIndex + 1  // Sequential episode number
            );
        }
    }
}

function updateNavigationButtons() {
    const prevBtn = document.getElementById('prevEpisode');
    const nextBtn = document.getElementById('nextEpisode');
    
    // Disable previous button if first episode
    prevBtn.disabled = currentEpisodeIndex === 0;
    
    // Disable next button if last episode
    nextBtn.disabled = currentEpisodeIndex === episodes.length - 1;
}

// Close modal when clicking outside
document.getElementById('videoModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeVideoModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeVideoModal();
    }
});
</script>

<style>
/* Video Modal Styles */
.video-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.95);
    z-index: 10000;
    align-items: center;
    justify-content: center;
}

.video-modal.show {
    display: flex;
}

.video-modal-content {
    background: #292f36;
    border-radius: 16px;
    width: 90%;
    max-width: 1200px;
    max-height: 90vh;
    overflow: hidden;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
    border: 1px solid #3d4451;
}

.video-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem 2rem;
    background: #1f242a;
    color: white;
    border-bottom: 1px solid #3d4451;
}

.video-modal-header h3 {
    margin: 0;
    font-size: 1.3rem;
    font-weight: 600;
    color: #4a9eff;
}

.video-modal-close {
    background: none;
    border: none;
    color: white;
    font-size: 2rem;
    cursor: pointer;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: background 0.3s ease;
}

.video-modal-close:hover {
    background: rgba(255, 255, 255, 0.1);
}

.video-player-wrapper {
    position: relative;
    background: #000;
}

.video-player-wrapper video {
    width: 100%;
    height: auto;
    max-height: 70vh;
    display: block;
}

.video-modal-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem 2rem;
    background: #1f242a;
    gap: 1rem;
    border-top: 1px solid #3d4451;
}

.video-nav-btn {
    padding: 0.8rem 1.5rem;
    background: #4a9eff;
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.95rem;
}

.video-nav-btn:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(74, 158, 255, 0.4);
}

.video-nav-btn:disabled {
    opacity: 0.4;
    cursor: not-allowed;
    background: #3d4451;
}

.auto-next-toggle {
    display: flex;
    align-items: center;
    color: white;
}

.auto-next-toggle label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    font-size: 0.95rem;
}

.auto-next-toggle input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

/* Responsive */
@media (max-width: 768px) {
    .video-modal-content {
        width: 95%;
        max-height: 95vh;
    }
    
    .video-modal-header {
        padding: 1rem;
    }
    
    .video-modal-header h3 {
        font-size: 1rem;
    }
    
    .video-modal-controls {
        flex-direction: column;
        padding: 1rem;
    }
    
    .video-nav-btn {
        width: 100%;
    }
}
</style>

<?php include 'includes/footer.php'; ?>
