<?php
session_start();

if (!isset($_SESSION['user_id'], $_SESSION['user_email'])) {
    header("Location: login.php");
    exit;
}

require "db_conn.php";
require "php/func-category.php";
require "php/func-author.php";

$categories = get_all_categories($conn);
$authors    = get_all_author($conn);

$old = $_SESSION['old'] ?? [];
$title       = $old['title']       ?? '';
$desc        = $old['description'] ?? ($old['desc'] ?? '');
$author_id   = $old['author_id']   ?? 0;
$category_id = $old['category_id'] ?? 0;
$book_cover  = $old['book_cover']  ?? '';
$book_file   = $old['book_file']   ?? '';

unset($_SESSION['old']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add Book – BookVerse 3D Admin</title>
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
                
                <button class="navbar-toggler border-0 text-white shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarAddBook">
                    <i class="fas fa-bars fa-lg"></i>
                </button>

                <div class="collapse navbar-collapse" id="navbarAddBook">
                    <ul class="navbar-nav mx-auto nav-pills-glass gap-1 my-2 my-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="admin.php"><i class="fas fa-chart-pie me-1"></i> Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="add-book.php"><i class="fas fa-plus-circle me-1"></i> Add Book</a>
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

    <!-- Main Container -->
    <main class="auth-page-wrap">
        <div class="glass-card-form" style="max-width: 680px;">
            <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
                <div>
                    <h1 class="font-display h3 text-white mb-1">
                        <i class="fas fa-book-medical text-gradient me-2"></i> Add New Book
                    </h1>
                    <p class="text-secondary small mb-0">Publish a new title, upload PDF edition and cover art</p>
                </div>
                <a href="admin.php" class="btn-glass-outline btn-glass-sm">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>

            <?php if (!empty($_SESSION['error'])): ?>
                <div class="glass-alert glass-alert-danger">
                    <i class="fas fa-exclamation-circle fa-lg"></i>
                    <div><?= htmlspecialchars($_SESSION['error']) ?></div>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION['success'])): ?>
                <div class="glass-alert glass-alert-success">
                    <i class="fas fa-check-circle fa-lg"></i>
                    <div><?= htmlspecialchars($_SESSION['success']) ?></div>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <form action="php/add-book.php" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label-glass">
                        <i class="fas fa-heading text-primary me-1"></i> Book Title
                    </label>
                    <input type="text" class="form-control form-control-glass" name="book_title" value="<?= htmlspecialchars($title) ?>" placeholder="e.g. War and Peace" required>
                </div>

                <div class="mb-3">
                    <label class="form-label-glass">
                        <i class="fas fa-align-left text-info me-1"></i> Synopsis & Description
                    </label>
                    <textarea class="form-control form-control-glass" name="book_description" rows="3" placeholder="Provide a summary of the book..." required><?= htmlspecialchars($desc) ?></textarea>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label-glass">
                            <i class="fas fa-user-edit text-warning me-1"></i> Select Author
                        </label>
                        <select name="book_author" class="form-control form-control-glass glass-select" required>
                            <option value="0">Choose Author...</option>
                            <?php if ($authors): foreach ($authors as $a): ?>
                                <option value="<?= $a['id'] ?>" <?= $a['id'] == $author_id ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($a['name']) ?>
                                </option>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-glass">
                            <i class="fas fa-folder text-success me-1"></i> Select Category
                        </label>
                        <select name="book_category" class="form-control form-control-glass glass-select" required>
                            <option value="0">Choose Category...</option>
                            <?php if ($categories): foreach ($categories as $c): ?>
                                <option value="<?= $c['id'] ?>" <?= $c['id'] == $category_id ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($c['name']) ?>
                                </option>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label-glass">
                        <i class="fas fa-image text-danger me-1"></i> Cover Art (JPG, PNG)
                    </label>
                    <input type="file" class="form-control form-control-glass" name="book_cover" accept="image/*" <?= $book_cover ? 'disabled' : '' ?>>
                    <?php if ($book_cover): ?>
                        <div class="small text-success mt-1">
                            <i class="fas fa-check me-1"></i> Cover already chosen: <strong><?= htmlspecialchars($book_cover) ?></strong>
                        </div>
                        <input type="hidden" name="book_cover_existing" value="<?= htmlspecialchars($book_cover) ?>">
                    <?php endif; ?>
                </div>

                <div class="mb-4">
                    <label class="form-label-glass">
                        <i class="fas fa-file-pdf text-danger me-1"></i> Book Document File (PDF)
                    </label>
                    <input type="file" class="form-control form-control-glass" name="file" accept=".pdf,.epub,.docx" <?= $book_file ? 'disabled' : '' ?>>
                    <?php if ($book_file): ?>
                        <div class="small text-success mt-1">
                            <i class="fas fa-check me-1"></i> Document already uploaded: <strong><?= htmlspecialchars($book_file) ?></strong>
                        </div>
                        <input type="hidden" name="book_file_existing" value="<?= htmlspecialchars($book_file) ?>">
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn-glass-3d w-100 py-3">
                    <i class="fas fa-cloud-upload-alt me-1"></i> Publish Book to Library
                </button>
            </form>
        </div>
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/3d-glass.js?v=<?= time() ?>"></script>
</body>

</html>