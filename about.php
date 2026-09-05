<?php
session_start();
include "db_conn.php";

// Fetch counts for live metrics
$books_count = 62;
$authors_count = 46;
$categories_count = 9;
try {
    $stmt = $conn->query("SELECT COUNT(*) as c FROM books");
    $books_count = $stmt->fetch(PDO::FETCH_ASSOC)['c'] ?? 62;

    $stmt = $conn->query("SELECT COUNT(*) as c FROM authors");
    $authors_count = $stmt->fetch(PDO::FETCH_ASSOC)['c'] ?? 46;

    $stmt = $conn->query("SELECT COUNT(*) as c FROM categories");
    $categories_count = $stmt->fetch(PDO::FETCH_ASSOC)['c'] ?? 9;
} catch (Exception $e) {
    // fallback defaults
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us – BookVerse 3D Library</title>
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
                
                <button class="navbar-toggler border-0 text-white shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarAbout">
                    <i class="fas fa-bars fa-lg"></i>
                </button>

                <div class="collapse navbar-collapse" id="navbarAbout">
                    <ul class="navbar-nav mx-auto nav-pills-glass gap-1 my-2 my-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php"><i class="fas fa-compass me-1"></i> Library</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="about.php"><i class="fas fa-info-circle me-1"></i> About</a>
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

    <!-- Hero Header -->
    <header class="hero-section container">
        <div class="hero-badge">
            <i class="fas fa-compass text-gradient"></i> Our Mission & Vision
        </div>
        <h1 class="hero-title">
            Redefining the <span class="text-gradient">Digital Reading</span> Experience
        </h1>
        <p class="hero-subtitle">
            BookVerse is an immersive spatial digital library built to preserve world literature, timeless epics, and modern insights in a frictionless 3D glass environment.
        </p>
    </header>

    <!-- Live Statistics Counter Row -->
    <section class="container mb-5" style="position: relative; z-index: 1;">
        <div class="row g-4 justify-content-center">
            <div class="col-6 col-md-3">
                <div class="bento-stat-card">
                    <div class="bento-stat-icon" style="background: var(--grad-primary);">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="bento-stat-num"><?= $books_count ?>+</div>
                    <div class="bento-stat-label">Curated eBooks</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="bento-stat-card">
                    <div class="bento-stat-icon" style="background: var(--grad-sunset);">
                        <i class="fas fa-feather-alt"></i>
                    </div>
                    <div class="bento-stat-num"><?= $authors_count ?>+</div>
                    <div class="bento-stat-label">Distinguished Authors</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="bento-stat-card">
                    <div class="bento-stat-icon" style="background: var(--grad-emerald);">
                        <i class="fas fa-tags"></i>
                    </div>
                    <div class="bento-stat-num"><?= $categories_count ?>+</div>
                    <div class="bento-stat-label">Diverse Categories</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="bento-stat-card">
                    <div class="bento-stat-icon" style="background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%);">
                        <i class="fas fa-infinity"></i>
                    </div>
                    <div class="bento-stat-num">100%</div>
                    <div class="bento-stat-label">Free Open Access</div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3D Bento Grid Showcase -->
    <main class="container py-2" style="position: relative; z-index: 1; max-width: 1200px;">
        <div class="bento-grid-custom">
            <!-- Large Card 1 -->
            <div class="bento-card-glass bento-span-8">
                <div class="bento-icon-3d" style="background: var(--grad-primary);">
                    <i class="fas fa-landmark"></i>
                </div>
                <h2 class="h3 mb-3 font-display text-white">A Timeless Literary Sanctuary</h2>
                <p class="text-secondary mb-3 leading-relaxed">
                    BookVerse is designed from the ground up for readers who value deep focus, elegance, and unhindered access. From foundational ancient epics like the <strong>Mahabharata</strong> and <strong>Ramayana</strong> to world classics by Tolstoy, Dostoevsky, and Mary Shelley, our catalog connects eras of human intellect.
                </p>
                <p class="text-secondary mb-0 leading-relaxed">
                    Every title is paired with custom cover art, verified metadata, and high-fidelity PDF documents that you can read directly in your browser or download for offline reflection.
                </p>
            </div>

            <!-- Small Card 2 -->
            <div class="bento-card-glass bento-span-4">
                <div class="bento-icon-3d" style="background: var(--grad-sunset);">
                    <i class="fas fa-bolt"></i>
                </div>
                <h2 class="h4 mb-3 font-display text-white">Instant & Frictionless</h2>
                <p class="text-secondary mb-0 leading-relaxed">
                    Zero paywalls, zero ads, zero forced sign-ups. Explore books with real-time live search, instant category tab switching, and one-click reading.
                </p>
            </div>

            <!-- Small Card 3 -->
            <div class="bento-card-glass bento-span-4">
                <div class="bento-icon-3d" style="background: var(--grad-emerald);">
                    <i class="fas fa-cubes"></i>
                </div>
                <h2 class="h4 mb-3 font-display text-white">Spatial 3D Glass Aesthetic</h2>
                <p class="text-secondary mb-0 leading-relaxed">
                    Engineered with ultra-deep obsidian velvet backdrop, dynamic aurora lighting shaders, realistic book cover depth, and fluid CSS micro-interactions.
                </p>
            </div>

            <!-- Large Card 4 -->
            <div class="bento-card-glass bento-span-8">
                <div class="bento-icon-3d" style="background: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%);">
                    <i class="fas fa-users-gear"></i>
                </div>
                <h2 class="h3 mb-3 font-display text-white">Reader-Driven Curation</h2>
                <p class="text-secondary mb-4 leading-relaxed">
                    BookVerse thrives on community engagement. Vote on books you love, review reader feedback, or submit your recommendations to expand our catalog.
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="index.php" class="btn-glass-3d">
                        <i class="fas fa-compass"></i> Explore Library
                    </a>
                    <a href="feedback.php" class="btn-glass-outline">
                        <i class="fas fa-comment-dots"></i> Leave Feedback
                    </a>
                </div>
            </div>
        </div>

        <!-- Guiding Principles Section -->
        <div class="mt-5 p-4 p-md-5 bento-card-glass text-center">
            <div class="hero-badge mx-auto mb-3">
                <i class="fas fa-shield-alt text-warning"></i> Our Core Philosophy
            </div>
            <h3 class="font-display h2 text-white mb-3">Preserving Heritage. Embracing Modernity.</h3>
            <p class="text-secondary mx-auto mb-4" style="max-width: 780px;">
                Literature is humanity's shared memory. By combining open-access digital distribution with cutting-edge 3D spatial user interface design, we hope to inspire curiosity and a lifelong love for reading in every visitor.
            </p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="index.php" class="btn-glass-3d">
                    <i class="fas fa-book-open"></i> Start Reading Now
                </a>
            </div>
        </div>
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
