<?php
session_start();

if (!isset($_SESSION['user_id'], $_SESSION['user_email'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id']) && !isset($_SESSION['edit_id'])) {
    header("Location: admin.php");
    exit;
}

$id = $_GET['id'] ?? $_SESSION['edit_id'] ?? null;

if (empty($id)) {
    header("Location: admin.php");
    exit;
}

unset($_SESSION['edit_id']);

include "db_conn.php";
include "php/func-book.php";
$book = get_book($conn, $id);

if ($book == 0) {
    header("Location: admin.php");
    exit;
}

include "php/func-category.php";
$categories = get_all_categories($conn);
include "php/func-author.php";
$authors = get_all_author($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Book – BookVerse 3D Admin</title>
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
                <a class="navbar-brand" href="admin.php">
                    <span class="brand-icon-3d" style="background: var(--grad-sunset);">
                        <i class="fas fa-shield-alt"></i>
                    </span>
                    <span>Admin<span class="text-gradient-amber">Console</span></span>
                </a>
                
                <button class="navbar-toggler border-0 text-white shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarEditBook">
                    <i class="fas fa-bars fa-lg"></i>
                </button>

                <div class="collapse navbar-collapse" id="navbarEditBook">
                    <ul class="navbar-nav mx-auto nav-pills-glass gap-1 my-2 my-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="admin.php"><i class="fas fa-chart-pie me-1"></i> Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="add-book.php"><i class="fas fa-plus-circle me-1"></i> Add Book</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="add-category.php"><i class="fas fa-folder-plus me-1"></i> Add Category</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="add-author.php"><i class="fas fa-user-plus me-1"></i> Add Author</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="feedback_list.php"><i class="fas fa-comments me-1"></i> Feedbacks</a>
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
                        <a href="index.php" target="_blank" class="btn-glass-outline btn-glass-sm">
                            <i class="fas fa-external-link-alt"></i> Public Library
                        </a>
                        <a href="logout.php" class="btn-glass-sm" style="background: rgba(244, 63, 94, 0.2); border: 1px solid rgba(244,63,94,0.4); color: #fda4af;">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
        </nav>
    </div>

    <!-- Flash Message Toast -->
    <?php if (isset($_SESSION['success']) || isset($_SESSION['error'])): ?>
        <div class="container mb-3" style="position: relative; z-index: 1;">
            <div class="glass-alert <?= isset($_SESSION['success']) ? 'glass-alert-success' : 'glass-alert-danger' ?>">
                <i class="fas <?= isset($_SESSION['success']) ? 'fa-check-circle' : 'fa-exclamation-triangle' ?> fa-lg"></i>
                <div>
                    <?= htmlspecialchars($_SESSION['success'] ?? $_SESSION['error']);
                    unset($_SESSION['success'], $_SESSION['error']); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Main Edit Form -->
    <main class="auth-page-wrap">
        <div class="glass-card-form" style="max-width: 680px;">
            <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
                <div>
                    <h1 class="font-display h3 text-white mb-1">
                        <i class="fas fa-pen-to-square text-gradient me-2"></i> Edit Book
                    </h1>
                    <p class="text-secondary small mb-0">Update metadata, author, category, cover art or document</p>
                </div>
                <a href="admin.php" class="btn-glass-outline btn-glass-sm">
                    <i class="fas fa-arrow-left"></i> Dashboard
                </a>
            </div>

            <form action="php/edit-book.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="book_id" value="<?= $book['id'] ?>">

                <div class="mb-3">
                    <label class="form-label-glass">
                        <i class="fas fa-heading text-primary me-1"></i> Book Title
                    </label>
                    <input type="text" class="form-control form-control-glass" name="book_title" value="<?= htmlspecialchars($book['title']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label-glass">
                        <i class="fas fa-align-left text-info me-1"></i> Synopsis & Description
                    </label>
                    <textarea class="form-control form-control-glass" name="book_description" rows="4" required><?= htmlspecialchars($book['description']) ?></textarea>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label-glass">
                            <i class="fas fa-user-edit text-warning me-1"></i> Author
                        </label>
                        <select name="book_author" class="form-control form-control-glass glass-select" required>
                            <option value="0">Choose Author...</option>
                            <?php foreach ($authors as $a): ?>
                                <option value="<?= $a['id'] ?>" <?= $a['id'] == $book['author_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($a['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-glass">
                            <i class="fas fa-folder text-success me-1"></i> Category
                        </label>
                        <select name="book_category" class="form-control form-control-glass glass-select" required>
                            <option value="0">Choose Category...</option>
                            <?php foreach ($categories as $c): ?>
                                <option value="<?= $c['id'] ?>" <?= $c['id'] == $book['category_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($c['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label-glass">
                        <i class="fas fa-image text-danger me-1"></i> Change Cover Art
                    </label>
                    <input type="file" class="form-control form-control-glass" name="book_cover" accept="image/*">
                    <input type="hidden" name="current_cover" value="<?= htmlspecialchars($book['cover']) ?>">
                    <?php if (!empty($book['cover'])): ?>
                        <div class="d-flex align-items-center gap-2 mt-2">
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
                            <img src="uploads/cover/<?= htmlspecialchars($book['cover']) ?>" class="table-cover-thumb" style="width: 40px; height: 50px;">
                            <a href="uploads/cover/<?= htmlspecialchars($book['cover']) ?>" target="_blank" class="small text-warning text-decoration-none">
                                <i class="fas fa-external-link-alt me-1"></i> Current Cover: <?= htmlspecialchars($book['cover']) ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mb-4">
                    <label class="form-label-glass">
                        <i class="fas fa-file-pdf text-danger me-1"></i> Change Document File
                    </label>
                    <input type="file" class="form-control form-control-glass" name="file" accept=".pdf,.doc,.docx,.epub">
                    <input type="hidden" name="current_file" value="<?= htmlspecialchars($book['file']) ?>">
                    <?php if (!empty($book['file'])): ?>
                        <div class="mt-2">
                            <a href="uploads/files/<?= htmlspecialchars($book['file']) ?>" target="_blank" class="small text-info text-decoration-none">
                                <i class="fas fa-file-pdf text-danger me-1"></i> Current File: <?= htmlspecialchars($book['file']) ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn-glass-3d w-100 py-3">
                    <i class="fas fa-save me-1"></i> Save Changes
                </button>
            </form>
        </div>
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/3d-glass.js?v=<?= time() ?>"></script>
</body>

</html>