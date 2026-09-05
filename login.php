<?php
session_start();
if (isset($_SESSION['user_id'], $_SESSION['user_email'])) {
    header("Location: admin.php");
    exit;
}
$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
$old_email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
unset($_SESSION['error'], $_SESSION['email']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Login – BookVerse 3D</title>
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
                
                <button class="navbar-toggler border-0 text-white shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarLogin">
                    <i class="fas fa-bars fa-lg"></i>
                </button>

                <div class="collapse navbar-collapse" id="navbarLogin">
                    <ul class="navbar-nav ms-auto nav-pills-glass gap-1 my-2 my-lg-0">
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
                </div>
            </div>
        </nav>
    </div>

    <!-- Main Auth Card -->
    <main class="auth-page-wrap">
        <div class="glass-card-form text-center">
            <div class="login-lock-badge">
                <i class="fas fa-shield-halved"></i>
            </div>
            
            <h1 class="font-display h3 text-white mb-2">Admin Portal</h1>
            <p class="text-secondary small mb-4">
                Secure access for library management and moderation.
            </p>

            <?php if (!empty($error)): ?>
                <div class="glass-alert glass-alert-danger text-start">
                    <i class="fas fa-exclamation-circle fa-lg"></i>
                    <div><?= htmlspecialchars($error) ?></div>
                </div>
            <?php endif; ?>

            <form action="php/auth.php" method="post" class="text-start">
                <div class="mb-3">
                    <label class="form-label-glass">
                        <i class="fas fa-envelope text-primary me-1"></i> Email Address
                    </label>
                    <input type="email" 
                           class="form-control form-control-glass" 
                           id="email" 
                           name="email" 
                           placeholder="admin@example.com" 
                           value="<?= htmlspecialchars($old_email) ?>" 
                           required 
                           autofocus>
                </div>

                <div class="mb-4">
                    <label class="form-label-glass">
                        <i class="fas fa-lock text-info me-1"></i> Password
                    </label>
                    <input type="password" 
                           class="form-control form-control-glass" 
                           id="password" 
                           name="password" 
                           placeholder="Enter your security password" 
                           required>
                </div>

                <button type="submit" class="btn-glass-3d w-100 py-3 mb-3">
                    <span>Sign In to Dashboard</span> <i class="fas fa-arrow-right ms-1"></i>
                </button>

                <div class="text-center pt-2">
                    <a href="index.php" class="text-secondary small text-decoration-none">
                        <i class="fas fa-arrow-left me-1"></i> Back to Library
                    </a>
                </div>
            </form>
        </div>
    </main>

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