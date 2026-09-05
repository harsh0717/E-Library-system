<?php
session_start();
include "db_conn.php";

// Fetch recent feedbacks from database to display community reviews
$recent_feedbacks = [];
$avg_rating = 5.0;
$total_feedbacks = 0;

try {
    $stmt = $conn->query("SELECT * FROM feedback ORDER BY id DESC LIMIT 6");
    $recent_feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $avg_stmt = $conn->query("SELECT AVG(rating) as avg_r, COUNT(*) as cnt FROM feedback");
    $stats = $avg_stmt->fetch(PDO::FETCH_ASSOC);
    if ($stats && $stats['cnt'] > 0) {
        $avg_rating = round(floatval($stats['avg_r']), 1);
        $total_feedbacks = intval($stats['cnt']);
    }
} catch (Exception $e) {
    // silently failover
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Feedback & Reviews – BookVerse 3D</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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
                
                <button class="navbar-toggler border-0 text-white shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarFeedback">
                    <i class="fas fa-bars fa-lg"></i>
                </button>

                <div class="collapse navbar-collapse" id="navbarFeedback">
                    <ul class="navbar-nav mx-auto nav-pills-glass gap-1 my-2 my-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php"><i class="fas fa-compass me-1"></i> Library</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="about.php"><i class="fas fa-info-circle me-1"></i> About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="feedback.php"><i class="fas fa-comment-dots me-1"></i> Feedback</a>
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

    <!-- Flash Message Toast Notification -->
    <?php if (isset($_SESSION['success']) || isset($_SESSION['error'])): ?>
        <div class="flash-toast-wrap">
            <div class="glass-alert <?= isset($_SESSION['success']) ? 'glass-alert-success' : 'glass-alert-danger' ?>">
                <i class="fas <?= isset($_SESSION['success']) ? 'fa-check-circle' : 'fa-exclamation-triangle' ?> fa-lg"></i>
                <div>
                    <?= htmlspecialchars($_SESSION['success'] ?? $_SESSION['error']);
                    unset($_SESSION['success'], $_SESSION['error']); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Feedback Page Content -->
    <main class="container py-4" style="position: relative; z-index: 1; max-width: 1140px;">
        <div class="text-center mb-5">
            <div class="hero-badge mb-2">
                <i class="fas fa-heart text-danger"></i> Community Voice & Reviews
            </div>
            <h1 class="hero-title">
                Reader Feedback & <span class="text-gradient">Ratings</span>
            </h1>
            <p class="hero-subtitle">
                Help us refine BookVerse. Share your thoughts, suggest missing book titles, or rate your overall experience.
            </p>
        </div>

        <div class="row g-4 align-items-start justify-content-center">
            <!-- Left Column: Interactive Feedback Form -->
            <div class="col-lg-6">
                <div class="feedback-form-card">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="feedback-icon-badge" style="background: var(--grad-sunset);">
                            <i class="fas fa-pen-nib"></i>
                        </div>
                        <div>
                            <h2 class="h4 font-display text-white mb-1">Write a Review</h2>
                            <p class="text-secondary small mb-0">Your voice guides our future book curation</p>
                        </div>
                    </div>

                    <form action="php/feedback-process.php" method="post" id="feedbackForm">
                        <div class="mb-3">
                            <label class="form-label-glass" for="fb_name">
                                <i class="fas fa-user text-primary me-1"></i> Your Name
                            </label>
                            <input type="text" id="fb_name" name="name" class="form-control form-control-glass" placeholder="e.g. Sneh Patel" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label-glass" for="fb_email">
                                <i class="fas fa-envelope text-info me-1"></i> Email Address
                            </label>
                            <input type="email" id="fb_email" name="email" class="form-control form-control-glass" placeholder="e.g. sneh@example.com" required>
                        </div>

                        <!-- 3D Star Rating Widget -->
                        <div class="mb-4 text-center">
                            <label class="form-label-glass d-block mb-2">
                                <i class="fas fa-star text-warning me-1"></i> Rate Your Experience
                            </label>
                            <div class="star-rating-box-interactive" id="starRatingBox">
                                <i class="fas fa-star selected" data-value="1" title="1 Star - Poor"></i>
                                <i class="fas fa-star selected" data-value="2" title="2 Stars - Fair"></i>
                                <i class="fas fa-star selected" data-value="3" title="3 Stars - Good"></i>
                                <i class="fas fa-star selected" data-value="4" title="4 Stars - Very Good"></i>
                                <i class="fas fa-star selected" data-value="5" title="5 Stars - Outstanding"></i>
                            </div>
                            <input type="hidden" name="rating" id="ratingInput" value="5" required>
                            <div class="rating-label-display" id="ratingLabel">5 Stars — Outstanding Experience!</div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label-glass" for="fb_message">
                                <i class="fas fa-comment-alt text-warning me-1"></i> Your Feedback / Suggestions
                            </label>
                            <textarea id="fb_message" name="message" class="form-control form-control-glass" rows="4" placeholder="Tell us what you loved, or recommend books you'd like added next..." required></textarea>
                        </div>

                        <button type="submit" class="btn-glass-3d w-100 py-3">
                            <span>Submit Feedback</span> <i class="fas fa-paper-plane ms-2"></i>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right Column: Community Reviews & Stats -->
            <div class="col-lg-6">
                <!-- Summary Card -->
                <div class="feedback-summary-card mb-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-secondary small font-weight-bold text-uppercase">Community Rating</div>
                            <div class="d-flex align-items-baseline gap-2 mt-1">
                                <span class="display-5 font-weight-bold text-white font-display"><?= $avg_rating ?></span>
                                <span class="text-secondary">/ 5.0</span>
                            </div>
                            <div class="star-display-gold mt-1">
                                <?php
                                    $fullStars = floor($avg_rating);
                                    for ($s = 1; $s <= 5; $s++) {
                                        if ($s <= $fullStars) {
                                            echo '<i class="fas fa-star"></i>';
                                        } else {
                                            echo '<i class="far fa-star text-muted"></i>';
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="stat-bubble-count"><?= $total_feedbacks ?></div>
                            <div class="text-secondary small">Total Reviews</div>
                        </div>
                    </div>
                </div>

                <!-- Recent Reviews Feed -->
                <div class="recent-reviews-header d-flex align-items-center justify-content-between mb-3">
                    <h3 class="h5 text-white font-display mb-0">
                        <i class="fas fa-comments text-gradient me-2"></i> Recent Reader Voices
                    </h3>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 small">
                        Live Feed
                    </span>
                </div>

                <?php if (empty($recent_feedbacks)): ?>
                    <div class="feedback-review-card text-center py-4">
                        <i class="fas fa-comment-dots fa-2x text-muted mb-2"></i>
                        <p class="text-secondary mb-0">Be the first to share your feedback for BookVerse!</p>
                    </div>
                <?php else: ?>
                    <div class="feedback-reviews-list">
                        <?php foreach ($recent_feedbacks as $fb): ?>
                            <div class="feedback-review-card">
                                <div class="d-flex align-items-center justify-content-between mb-2">
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
                                        <div class="reviewer-avatar">
                                            <?= strtoupper(substr($fb['name'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <div class="reviewer-name"><?= htmlspecialchars($fb['name']) ?></div>
                                            <div class="reviewer-time small text-muted">
                                                <?= !empty($fb['created_at']) ? date("M j, Y", strtotime($fb['created_at'])) : 'Recent Review' ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="star-display-gold small">
                                        <?php 
                                            $r = intval($fb['rating']);
                                            for ($s = 1; $s <= 5; $s++) {
                                                echo ($s <= $r) ? '<i class="fas fa-star"></i>' : '<i class="far fa-star text-muted"></i>';
                                            }
                                        ?>
                                    </div>
                                </div>
                                <p class="reviewer-text mb-0">
                                    <?= nl2br(htmlspecialchars($fb['message'])) ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
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
