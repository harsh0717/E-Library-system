<?php
session_start();
include "db_conn.php";
include "php/func-book.php";
$books = get_all_books($conn);

include "php/func-author.php";
$authors = get_all_author($conn);

include "php/func-category.php";
$categories = get_all_categories($conn);

// Count books per category
$catCounts = [];
if (!empty($books)) {
    foreach ($books as $b) {
        $cId = $b['category_id'];
        $catCounts[$cId] = ($catCounts[$cId] ?? 0) + 1;
    }
}

// Category Icon Mapping
$catIcons = [
    'Novel' => 'fa-book-bookmark',
    'Poetry' => 'fa-feather',
    'Romance' => 'fa-heart',
    'Mystery' => 'fa-mask',
    'Horror' => 'fa-ghost',
    'Classic' => 'fa-landmark',
    'Fiction' => 'fa-rocket',
    'Hindu Itihasa (History)' => 'fa-om'
];

// Spotlight featured picks (Find IDs or Titles)
$spotlightTitles = ['The Bhagavad Gita', 'The Hound of the Baskervilles', 'War and Peace'];
$spotlightBooks = [];
if (!empty($books)) {
    foreach ($books as $b) {
        if (in_array($b['title'], $spotlightTitles)) {
            $spotlightBooks[] = $b;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>BookVerse – Spatial 3D Digital Library</title>
    <!-- Bootstrap 5 & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" />
    <!-- 3D Glassmorphic Theme -->
    <link href="css/style.css?v=<?= time() ?>" rel="stylesheet" />
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
                
                <button class="navbar-toggler border-0 text-white shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                    <i class="fas fa-bars fa-lg"></i>
                </button>

                <div class="collapse navbar-collapse" id="navbarMain">
                    <ul class="navbar-nav mx-auto nav-pills-glass gap-1 my-2 my-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" href="index.php"><i class="fas fa-compass me-1"></i> Library</a>
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
                                <i class="fas fa-user-lock"></i> Admin Portal
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>
    </div>

    <!-- Hero Section with Search Command Capsule -->
    <header class="hero-section container">
        <div class="hero-badge">
            <span class="pulse-dot"></span>
            <span>Spatial 3D Digital Archive • <?= is_array($books) ? count($books) : 0 ?> Masterpieces</span>
        </div>
        
        <h1 class="hero-title">
            Step Into The Infinite World of <br>
            <span class="text-gradient">Timeless Knowledge</span>
        </h1>
        
        <p class="hero-subtitle">
            Immerse yourself in world classics, ancient epics, thrilling mysteries, and timeless poetry rendered in an ultra-modern 3D glass sanctuary.
        </p>

        <!-- 3D Glass Search & Filter Form -->
        <form action="search.php" method="get" class="search-capsule" id="searchForm">
            <div class="search-input-group">
                <i class="fas fa-search"></i>
                <input type="text" 
                       id="liveSearchInput" 
                       name="key" 
                       placeholder="Instant search by title, author, or keyword..." 
                       aria-label="Search" 
                       autocomplete="off">
            </div>

            <div class="filters-row">
                <!-- Category Select -->
                <select class="glass-select" id="headerCategorySelect" onchange="if(this.value) filterByCategory(this.value);">
                    <option value="all">📂 All Categories (<?= count($books) ?>)</option>
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?> (<?= $catCounts[$cat['id']] ?? 0 ?>)</option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>

                <!-- Author Select -->
                <select class="glass-select" onchange="if(this.value) location=this.value;">
                    <option value="">✍️ Browse Authors</option>
                    <?php if (!empty($authors)): ?>
                        <?php foreach ($authors as $auth): ?>
                            <option value="author.php?id=<?= $auth['id'] ?>"><?= htmlspecialchars($auth['name']) ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>

                <button type="submit" class="btn-glass-3d">
                    <span>Search</span> <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </form>
    </header>

    <!-- Main Container -->
    <main class="books-container">

        <!-- Spotlight Shelf (Editor's Picks) -->
        <?php if (!empty($spotlightBooks)): ?>
            <section class="spotlight-shelf">
                <div class="spotlight-header">
                    <div class="spotlight-title">
                        <i class="fas fa-award text-warning"></i>
                        <span>Featured Masterpieces & Editor's Picks</span>
                    </div>
                    <span class="text-secondary small">Curated highlights from the collection</span>
                </div>

                <div class="spotlight-grid">
                    <?php foreach ($spotlightBooks as $sBook): 
                        $sAuthor = 'Classic Author';
                        foreach ($authors as $a) {
                            if ($a['id'] == $sBook['author_id']) {
                                $sAuthor = $a['name'];
                                break;
                            }
                        }
                    ?>
                        <div class="spotlight-card">
                            <img src="uploads/cover/<?= htmlspecialchars($sBook['cover']) ?>" 
                                 alt="<?= htmlspecialchars($sBook['title']) ?>" 
                                 class="spotlight-cover-thumb"
                                 onerror="this.src='https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=300&q=80'">
                            <div class="spotlight-details">
                                <span class="spotlight-badge text-warning" style="background: rgba(245, 158, 11, 0.18);">
                                    <i class="fas fa-star me-1"></i> TOP RATED
                                </span>
                                <h3 class="spotlight-book-title" title="<?= htmlspecialchars($sBook['title']) ?>">
                                    <?= htmlspecialchars($sBook['title']) ?>
                                </h3>
                                <div class="spotlight-author">
                                    <i class="fas fa-feather-alt text-warning me-1"></i><?= htmlspecialchars($sAuthor) ?>
                                </div>
                                <div class="d-flex gap-2 mt-2">
                                    <a href="uploads/files/<?= htmlspecialchars($sBook['file']) ?>" target="_blank" rel="noopener noreferrer" class="btn-glass-sm btn-open-glass">
                                        <i class="fas fa-eye"></i> Read Now
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>

        <!-- Interactive Category Pill Filter Bar -->
        <div class="category-pill-bar-wrap">
            <div class="category-pill-bar" id="categoryPillBar">
                <button type="button" class="cat-filter-pill active" data-category-id="all" onclick="filterByCategory('all')">
                    <i class="fas fa-sparkles text-warning"></i>
                    <span>All Masterpieces</span>
                    <span class="pill-count"><?= is_array($books) ? count($books) : 0 ?></span>
                </button>
                <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $cat): 
                        $iconClass = $catIcons[$cat['name']] ?? 'fa-book';
                        $cCount = $catCounts[$cat['id']] ?? 0;
                    ?>
                        <button type="button" class="cat-filter-pill" data-category-id="<?= $cat['id'] ?>" onclick="filterByCategory('<?= $cat['id'] ?>')">
                            <i class="fas <?= $iconClass ?> text-info"></i>
                            <span><?= htmlspecialchars($cat['name']) ?></span>
                            <span class="pill-count"><?= $cCount ?></span>
                        </button>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Section Header Row -->
        <div class="section-header-row">
            <h2 class="section-title-3d">
                <i class="fas fa-layer-group text-gradient"></i>
                <span id="sectionTitleText">Complete Library Catalog</span>
            </h2>
            <div class="live-book-counter" id="liveBookCounter">
                Displaying <strong><?= is_array($books) ? count($books) : 0 ?></strong> books
            </div>
        </div>

        <?php if (empty($books)): ?>
            <div class="glass-panel p-5 text-center my-4">
                <i class="fas fa-book-reader fa-3x mb-3 text-muted"></i>
                <h3>No Books Found</h3>
                <p class="text-secondary">Our digital shelves are currently being updated. Please check back soon!</p>
            </div>
        <?php else: ?>
            <div class="book-grid-3d" id="booksGrid">
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
                    <div class="card-3d-wrap" 
                         id="book-card-<?= $book['id'] ?>"
                         data-book-id="<?= $book['id'] ?>"
                         data-category-id="<?= $book['category_id'] ?>"
                         data-title="<?= strtolower(htmlspecialchars($book['title'])) ?>"
                         data-author="<?= strtolower(htmlspecialchars($authorName)) ?>"
                         data-desc="<?= strtolower(htmlspecialchars($description)) ?>">
                        <div class="card-3d">
                            <!-- Category Badge -->
                            <span class="book-category-badge">
                                <?= htmlspecialchars($categoryName) ?>
                            </span>

                            <!-- 3D Book Cover Box with Spine Depth -->
                            <div class="book-cover-3d-box">
                                <img src="uploads/cover/<?= htmlspecialchars($book['cover']) ?>" 
                                     alt="<?= htmlspecialchars($book['title']) ?> Cover"
                                     loading="lazy"
                                     onerror="this.src='https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=600&q=80'">
                            </div>

                            <!-- Card Details -->
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

                                <!-- Action Buttons & Voting Capsule -->
                                <div class="card-bottom-bar">
                                    <div class="action-buttons-group">
                                        <a href="uploads/files/<?= htmlspecialchars($book['file']) ?>" target="_blank" rel="noopener noreferrer" class="btn-glass-sm btn-open-glass" title="Read <?= htmlspecialchars($book['title']) ?> Online">
                                            <i class="fas fa-eye"></i> Open
                                        </a>
                                        <a href="uploads/files/<?= htmlspecialchars($book['file']) ?>" download="<?= htmlspecialchars($book['title']) ?>.pdf" class="btn-glass-sm btn-download-glass" title="Download <?= htmlspecialchars($book['title']) ?> PDF">
                                            <i class="fas fa-arrow-down"></i> PDF
                                        </a>
                                    </div>

                                    <!-- Vote Capsule -->
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

            <!-- No Search Results Found Alert -->
            <div id="noResultsMsg" class="glass-panel p-5 text-center my-4" style="display: none;">
                <i class="fas fa-search-minus fa-3x mb-3 text-warning"></i>
                <h3 class="text-white">No Matching Books Found</h3>
                <p class="text-secondary">Try searching with a different title, author name, or keyword.</p>
                <button type="button" class="btn-glass-3d mt-2" onclick="resetFilters()">Reset All Filters</button>
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

    <!-- Bootstrap Bundle & 3D Glass Engine -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/3d-glass.js?v=<?= time() ?>"></script>
</body>

</html>
