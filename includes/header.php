<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? SITE_NAME ?> - <?= SITE_NAME ?></title>
    <meta name="description" content="<?= SITE_DESCRIPTION ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="header-content">
                <a href="index.php" class="logo">
                    <span class="logo-icon">🎬</span>
                    <span class="logo-text"><?= SITE_NAME ?></span>
                </a>
                
                
                <div class="header-search">
                    <form action="search.php" method="GET" class="search-form-mini">
                        <input type="text" name="q" placeholder="Search drama..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" required>
                        <button type="submit">🔍</button>
                    </form>
                </div>
            </div>
        </div>
    </header>
    
    <main class="main">
