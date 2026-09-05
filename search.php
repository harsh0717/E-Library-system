<?php
session_start();

if (!isset($_GET['key']) || trim($_GET['key']) === '') {
    header("Location: index.php");
    exit;
}
$key = trim($_GET['key']);

include "db_conn.php";
include "php/func-book.php";
include "php/func-author.php";
include "php/func-category.php";

$books      = search_books($conn, $key);
$authors    = get_all_author($conn);
$categories = get_all_categories($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Search: "<?= htmlspecialchars($key) ?>" – BookVerse 3D</title>
    <!-- Bootstrap 5 & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" />
    <!-- 3D Glassmorphic Theme -->
    <link href="css/style.css?v=<?= time() ?>" rel="stylesheet" />
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<body>
    <!-- 3D Ambient Scene -->
    <div class="ambient-scene">
        <div class="ambient-orb ambient-orb-1"></div>
        <div class="ambient-orb ambient-orb-2"></div>
        <div class="ambient-orb ambient-orb-3"></div>
    </div>

    <!-- 3D Floating Glass Navbar -->
    <div class="glass-navbar-wrapper">
        <nav class="navbar navbar-expand-lg glass-navbar">
            <div class="container-fluid px-0">
                <a class="navbar-brand" href="index.php">
                    <span class="brand-icon-3d">
                        <i class="fas fa-book-open"></i>
                    </span>
                    <span>Book<span class="text-gradient">Verse</span></span>
                </a>
                
                <button class="navbar-toggler border-0 text-white shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSearch">
                    <i class="fas fa-bars fa-lg"></i>
                </button>

                <div class="collapse navbar-collapse" id="navbarSearch">
                    <ul class="navbar-nav mx-auto nav-pills-glass gap-1 my-2 my-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php"><i class="fas fa-compass me-1"></i> Library</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="about.php"><i class="fas fa-info-circle me-1"></i> About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="feedback.php"><i class="fas fa-comment-dots me-1"></i> Feedback</a>
                        </li>
                    </ul>

                    <div class="d-flex align-items-center gap-2">
                    <!-- Live Theme Switcher -->
                    <div class="dropdown me-2">
                        <button class="btn-glass-outline btn-glass-sm dropdown-toggle" type="button" id="themeSelectBtn" data-bs-toggle="dropdown" aria-expanded="false" title="Change Theme Palette">
                            <i class="fas fa-palette text-gradient"></i> Theme
                        </button>
                        <ul class="dropdown-menu dropdown-menu-dark glass-dropdown-menu dropdown-menu-end" aria-labelledby="themeSelectBtn">
                            <li><button type="button" class="dropdown-item theme-option active" data-theme-name="amethyst" onclick="setTheme('amethyst')"><i class="fas fa-gem text-warning me-2"></i> Royal Amethyst & Gold</button></li>
                            <li><button type="button" class="dropdown-item theme-option" data-theme-name="emerald" onclick="setTheme('emerald')"><i class="fas fa-leaf text-success me-2"></i> Emerald & Jade</button></li>
                            <li><button type="button" class="dropdown-item theme-option" data-theme-name="cyber" onclick="setTheme('cyber')"><i class="fas fa-bolt text-info me-2"></i> Cyberpunk Neon</button></li>
                            <li><button type="button" class="dropdown-item theme-option" data-theme-name="sunset" onclick="setTheme('sunset')"><i class="fas fa-fire text-danger me-2"></i> Sunset Flame</button></li>
                        </ul>
                    </div>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="admin.php" class="nav-auth-btn">
                                <i class="fas fa-shield-alt text-warning"></i> Dashboard
                            </a>
                            <a href="logout.php" class="btn-glass-outline btn-glass-sm">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        <?php else: ?>
                            <a href="login.php" class="nav-auth-btn">
                                <i class="fas fa-user-lock"></i> Admin Login
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>
    </div>

    <!-- Search Hero Section -->
    <header class="hero-section container">
        <a href="index.php" class="btn-glass-outline btn-glass-sm mb-3">
            <i class="fas fa-arrow-left"></i> Back to Library
        </a>
        <div class="hero-badge">
            <i class="fas fa-search text-gradient"></i> Search Results
        </div>
        <h1 class="hero-title">
            Results for <span class="text-gradient">"<?= htmlspecialchars($key) ?>"</span>
        </h1>

        <!-- Re-search bar -->
        <form action="search.php" method="get" class="search-capsule" style="max-width: 650px;">
            <div class="search-input-group">
                <i class="fas fa-search"></i>
                <input type="text" name="key" value="<?= htmlspecialchars($key) ?>" placeholder="Search another book, author, or keyword..." aria-label="Search">
            </div>
            <button type="submit" class="btn-glass-3d">
                <span>Search</span>
            </button>
        </form>
    </header>

    <!-- Books Grid -->
    <main class="books-container">
        <div class="section-header-row">
            <h2 class="section-title-3d">
                <i class="fas fa-book-open text-gradient"></i> Matched Titles
            </h2>
            <div class="text-secondary small">
                <?= is_array($books) ? count($books) : 0 ?> results found
            </div>
        </div>

        <?php if (empty($books)): ?>
            <div class="glass-panel p-5 text-center my-4">
                <i class="fas fa-search-minus fa-3x mb-3 text-muted"></i>
                <h3>No Matching Books Found</h3>
                <p class="text-secondary">We couldn't find any books matching "<strong><?= htmlspecialchars($key) ?></strong>". Try checking for spelling errors or searching for broader terms.</p>
                <a href="index.php" class="btn-glass-3d mt-3">Browse Full Catalog</a>
            </div>
        <?php else: ?>
            <div class="book-grid-3d">
                <?php foreach ($books as $book): 
                    $authorName = 'Unknown Author';
                    foreach ($authors as $author) {
                        if ($author['id'] == $book['author_id']) {
                            $authorName = $author['name'];
                            break;
                        }
                    }

                    $categoryName = 'General';
                    foreach ($categories as $cat) {
                        if ($cat['id'] == $book['category_id']) {
                            $categoryName = $cat['name'];
                            break;
                        }
                    }

                    $rawDescription = $book['description'] ?? 'No description available for this book.';
                    $description = htmlspecialchars($rawDescription);
                    $shortDescription = substr($description, 0, 130);
                    $hasMore = strlen($description) > 130;
                    if ($hasMore) {
                        $shortDescription .= '...';
                    }
                ?>
                    <div class="card-3d-wrap">
                        <div class="card-3d">
                            <span class="book-category-badge">
                                <?= htmlspecialchars($categoryName) ?>
                            </span>

                            <div class="book-cover-3d-box">
                                <img src="uploads/cover/<?= htmlspecialchars($book['cover']) ?>" 
                                     alt="<?= htmlspecialchars($book['title']) ?> Cover"
                                     loading="lazy"
                                     onerror="this.src='https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=600&q=80'">
                            </div>

                            <div class="card-details">
                                <h3 class="book-title"><?= htmlspecialchars($book['title']) ?></h3>
                                <div class="book-author">
                                    <i class="fas fa-feather-alt"></i>
                                    <span><?= htmlspecialchars($authorName) ?></span>
                                </div>

                                <div class="book-desc-wrap">
                                    <div class="book-desc-text" id="desc-<?= $book['id'] ?>">
                                        <span class="truncated-content"><?= $shortDescription ?></span>
                                        <span class="full-content" style="display: none;"><?= $description ?></span>
                                    </div>
                                    <?php if ($hasMore): ?>
                                        <button class="show-more-btn" data-book-id="<?= $book['id'] ?>">
                                            <i class="fas fa-chevron-down"></i> Show More
                                        </button>
                                    <?php endif; ?>
                                </div>

                                <div class="card-bottom-bar">
                                    <div class="action-buttons-group">
                                        <a href="uploads/files/<?= htmlspecialchars($book['file']) ?>" target="_blank" rel="noopener noreferrer" class="btn-glass-sm btn-open-glass" title="Read <?= htmlspecialchars($book['title']) ?> Online">
                                            <i class="fas fa-eye"></i> Open
                                        </a>
                                        <a href="uploads/files/<?= htmlspecialchars($book['file']) ?>" download="<?= htmlspecialchars($book['title']) ?>.pdf" class="btn-glass-sm btn-download-glass" title="Download <?= htmlspecialchars($book['title']) ?> PDF">
                                            <i class="fas fa-arrow-down"></i> PDF
                                        </a>
                                    </div>

                                    <?php $userVoted = $_SESSION["voted_" . $book['id']] ?? null; ?>
                                    <div class="vote-capsule">
                                        <button type="button" class="vote-btn-3d like <?= ($userVoted === 'like') ? 'voted' : '' ?>" id="btn-like-<?= $book['id'] ?>" onclick="vote(<?= $book['id'] ?>, 'like')" title="Like this book">
                                            <i class="fas fa-thumbs-up"></i>
                                            <span id="likes-<?= $book['id'] ?>"><?= intval($book['likes'] ?? 0) ?></span>
                                        </button>
                                        <button type="button" class="vote-btn-3d dislike <?= ($userVoted === 'dislike') ? 'voted' : '' ?>" id="btn-dislike-<?= $book['id'] ?>" onclick="vote(<?= $book['id'] ?>, 'dislike')" title="Dislike this book">
                                            <i class="fas fa-thumbs-down"></i>
                                            <span id="dislikes-<?= $book['id'] ?>"><?= intval($book['dislikes'] ?? 0) ?></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <!-- Floating Back to Top Button -->
    <button id="backToTop" title="Back to Top"><i class="fas fa-arrow-up"></i></button>

    <!-- Glass Footer -->
    <footer class="glass-footer">
        <div class="container">
            <p class="mb-1 font-display font-weight-bold text-white">📚 BookVerse – Spatial 3D Digital Library</p>
            <p class="mb-0 small">&copy; <?= date("Y") ?> All rights reserved. Crafted with 3D Glassmorphism & PHP.</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/3d-glass.js?v=<?= time() ?>"></script>
</body>

</html>
