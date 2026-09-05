<?php
session_start();
if (!isset($_SESSION['user_id'], $_SESSION['user_email'])) {
    header("Location: login.php");
    exit;
}

require "db_conn.php";
require "php/func-book.php";
require "php/func-author.php";
require "php/func-category.php";

$books      = get_all_books($conn);
$authors    = get_all_author($conn);
$categories = get_all_categories($conn);

// Total feedbacks count
$stmt = $conn->query("SELECT COUNT(*) as total FROM feedback");
$feedbackCount = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard – BookVerse 3D</title>
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
                
                <button class="navbar-toggler border-0 text-white shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarAdmin">
                    <i class="fas fa-bars fa-lg"></i>
                </button>

                <div class="collapse navbar-collapse" id="navbarAdmin">
                    <ul class="navbar-nav mx-auto nav-pills-glass gap-1 my-2 my-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" href="admin.php"><i class="fas fa-chart-pie me-1"></i> Dashboard</a>
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
                        <a href="index.php" target="_blank" class="btn-glass-outline btn-glass-sm" title="View Public Website">
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

    <!-- Main Dashboard Container -->
    <main class="admin-dashboard-container">
        <!-- 3D KPI Metrics Cards -->
        <div class="admin-stats-grid">
            <div class="stat-card-3d">
                <div class="stat-icon-box" style="background: var(--grad-primary); color: #fff;">
                    <i class="fas fa-book"></i>
                </div>
                <div class="stat-info">
                    <h4>Total Books</h4>
                    <div class="stat-number"><?= is_array($books) ? count($books) : 0 ?></div>
                </div>
            </div>

            <div class="stat-card-3d">
                <div class="stat-icon-box" style="background: var(--grad-sunset); color: #fff;">
                    <i class="fas fa-feather-alt"></i>
                </div>
                <div class="stat-info">
                    <h4>Total Authors</h4>
                    <div class="stat-number"><?= is_array($authors) ? count($authors) : 0 ?></div>
                </div>
            </div>

            <div class="stat-card-3d">
                <div class="stat-icon-box" style="background: var(--grad-emerald); color: #fff;">
                    <i class="fas fa-folder"></i>
                </div>
                <div class="stat-info">
                    <h4>Categories</h4>
                    <div class="stat-number"><?= is_array($categories) ? count($categories) : 0 ?></div>
                </div>
            </div>

            <div class="stat-card-3d">
                <div class="stat-icon-box" style="background: linear-gradient(135deg, #ec4899 0%, #8b5cf6 100%); color: #fff;">
                    <i class="fas fa-comments"></i>
                </div>
                <div class="stat-info">
                    <h4>User Feedbacks</h4>
                    <div class="stat-number"><?= $feedbackCount ?></div>
                </div>
            </div>
        </div>

        <!-- Section 1: All Books Table -->
        <div class="glass-table-wrapper mb-5">
            <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
                <div>
                    <h2 class="font-display h4 mb-1 text-white">
                        <i class="fas fa-books text-gradient me-2"></i> Books Management
                    </h2>
                    <p class="text-secondary small mb-0">Manage digital catalog titles, download files, and view votes</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="add-book.php" class="btn-glass-3d btn-glass-sm">
                        <i class="fas fa-plus"></i> Add New Book
                    </a>
                </div>
            </div>

            <?php if (empty($books)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                    <p class="text-secondary">No books added to the catalog yet.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="glass-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Book & Title</th>
                                <th>Author</th>
                                <th>Category</th>
                                <th>Likes</th>
                                <th>Dislikes</th>
                                <th>Description</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 0; foreach ($books as $b): $i++; ?>
                                <tr>
                                    <td class="text-secondary font-monospace"><?= $i ?></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="uploads/cover/<?= htmlspecialchars($b['cover']) ?>" 
                                                 alt="Cover" 
                                                 class="table-cover-thumb"
                                                 onerror="this.src='https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=100&q=80'">
                                            <div>
                                                <a href="uploads/files/<?= htmlspecialchars($b['file']) ?>" target="_blank" class="text-white font-weight-bold text-decoration-none" title="View PDF">
                                                    <?= htmlspecialchars($b['title']) ?>
                                                </a>
                                                <div class="small text-secondary">
                                                    <i class="fas fa-file-pdf text-danger me-1"></i><?= htmlspecialchars($b['file']) ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?php 
                                            foreach ($authors as $a) {
                                                if ($a['id'] == $b['author_id']) {
                                                    echo '<span class="badge" style="background: rgba(99,102,241,0.2); color: #a5b4fc; border: 1px solid rgba(99,102,241,0.3);">' . htmlspecialchars($a['name']) . '</span>';
                                                    break;
                                                }
                                            }
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                            foreach ($categories as $c) {
                                                if ($c['id'] == $b['category_id']) {
                                                    echo '<span class="badge" style="background: rgba(6,182,212,0.2); color: #67e8f9; border: 1px solid rgba(6,182,212,0.3);">' . htmlspecialchars($c['name']) . '</span>';
                                                    break;
                                                }
                                            }
                                        ?>
                                    </td>
                                    <td>
                                        <span class="text-info font-weight-bold"><i class="fas fa-thumbs-up me-1"></i><?= intval($b['likes'] ?? 0) ?></span>
                                    </td>
                                    <td>
                                        <span class="text-danger font-weight-bold"><i class="fas fa-thumbs-down me-1"></i><?= intval($b['dislikes'] ?? 0) ?></span>
                                    </td>
                                    <td style="min-width: 200px; max-width: 320px;">
                                        <?php 
                                            $rawAdminDesc = $b['description'] ?? 'No description available.';
                                            $cleanAdminDesc = htmlspecialchars($rawAdminDesc);
                                            $shortAdminDesc = htmlspecialchars(mb_strimwidth($rawAdminDesc, 0, 85, '...'));
                                            $hasMoreAdmin = mb_strlen($rawAdminDesc) > 85;
                                        ?>
                                        <div class="admin-desc-box" id="admin-desc-<?= $b['id'] ?>">
                                            <div class="admin-desc-short small">
                                                <?= $shortAdminDesc ?>
                                            </div>
                                            <?php if ($hasMoreAdmin): ?>
                                                <div class="admin-desc-full small" style="display: none;">
                                                    <?= nl2br($cleanAdminDesc) ?>
                                                </div>
                                                <button type="button" class="admin-desc-toggle-btn" data-target="admin-desc-<?= $b['id'] ?>">
                                                    <i class="fas fa-chevron-down me-1"></i> More
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-inline-flex gap-1">
                                            <a href="edit-book.php?id=<?= $b['id'] ?>" class="btn-table-action btn-table-edit" title="Edit Book">
                                                <i class="fas fa-pen"></i> Edit
                                            </a>
                                            <button class="btn-table-action btn-table-delete" 
                                                    title="Delete Book"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#confirmDeleteModal"
                                                    data-delete-url="php/delete-book.php?id=<?= $b['id'] ?>"
                                                    data-type="book"
                                                    data-name="<?= htmlspecialchars($b['title']) ?>">
                                                <i class="fas fa-trash-alt"></i> Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- Section 2: Categories & Authors Side by Side -->
        <div class="row g-4">
            <!-- Categories Table -->
            <div class="col-lg-6">
                <div class="glass-table-wrapper h-100">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div>
                            <h3 class="font-display h5 mb-1 text-white">
                                <i class="fas fa-folder-open text-gradient-amber me-2"></i> Categories
                            </h3>
                            <p class="text-secondary small mb-0">Total <?= count($categories) ?> categories</p>
                        </div>
                        <a href="add-category.php" class="btn-glass-3d btn-glass-sm btn-glass-amber">
                            <i class="fas fa-plus"></i> Add Category
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="glass-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Category Name</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($categories)): ?>
                                    <tr><td colspan="3" class="text-center p-3 text-secondary">No categories found.</td></tr>
                                <?php else: $ci = 0; foreach ($categories as $cat): $ci++; ?>
                                    <tr>
                                        <td class="text-secondary font-monospace"><?= $ci ?></td>
                                        <td class="font-weight-bold text-white"><?= htmlspecialchars($cat['name']) ?></td>
                                        <td class="text-end">
                                            <div class="d-inline-flex gap-1">
                                                <a href="edit-category.php?id=<?= $cat['id'] ?>" class="btn-table-action btn-table-edit">
                                                    <i class="fas fa-pen"></i> Edit
                                                </a>
                                                <button class="btn-table-action btn-table-delete"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#confirmDeleteModal"
                                                        data-delete-url="php/delete-category.php?id=<?= $cat['id'] ?>"
                                                        data-type="category"
                                                        data-name="<?= htmlspecialchars($cat['name']) ?>">
                                                    <i class="fas fa-trash-alt"></i> Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Authors Table -->
            <div class="col-lg-6">
                <div class="glass-table-wrapper h-100">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div>
                            <h3 class="font-display h5 mb-1 text-white">
                                <i class="fas fa-user-edit text-gradient-cyan me-2"></i> Authors
                            </h3>
                            <p class="text-secondary small mb-0">Total <?= count($authors) ?> authors</p>
                        </div>
                        <a href="add-author.php" class="btn-glass-3d btn-glass-sm btn-glass-emerald">
                            <i class="fas fa-plus"></i> Add Author
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="glass-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Author Name</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($authors)): ?>
                                    <tr><td colspan="3" class="text-center p-3 text-secondary">No authors found.</td></tr>
                                <?php else: $ai = 0; foreach ($authors as $au): $ai++; ?>
                                    <tr>
                                        <td class="text-secondary font-monospace"><?= $ai ?></td>
                                        <td class="font-weight-bold text-white"><?= htmlspecialchars($au['name']) ?></td>
                                        <td class="text-end">
                                            <div class="d-inline-flex gap-1">
                                                <a href="edit-author.php?id=<?= $au['id'] ?>" class="btn-table-action btn-table-edit">
                                                    <i class="fas fa-pen"></i> Edit
                                                </a>
                                                <button class="btn-table-action btn-table-delete"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#confirmDeleteModal"
                                                        data-delete-url="php/delete-author.php?id=<?= $au['id'] ?>"
                                                        data-type="author"
                                                        data-name="<?= htmlspecialchars($au['name']) ?>">
                                                    <i class="fas fa-trash-alt"></i> Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Floating Back to Top Button -->
    <button id="backToTop" title="Back to Top"><i class="fas fa-arrow-up"></i></button>

    <!-- 3D Glass Delete Confirmation Modal -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content glass-modal p-3">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title font-display text-white">
                        <i class="fas fa-exclamation-triangle text-danger me-2"></i> Confirm Deletion
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4">
                    <p id="deleteModalText" class="mb-2 text-light fs-5">Are you sure you want to delete this item?</p>
                    <p class="text-secondary small mb-0">This operation cannot be reversed.</p>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn-glass-outline btn-glass-sm" data-bs-dismiss="modal">Cancel</button>
                    <a href="#" class="btn-glass-3d btn-glass-sm" id="confirmDeleteBtn" style="background: var(--accent-rose);">
                        <i class="fas fa-trash"></i> Yes, Delete
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/3d-glass.js?v=<?= time() ?>"></script>
    <script>
        // Modal data binder
        const confirmDeleteModal = document.getElementById('confirmDeleteModal');
        confirmDeleteModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const deleteUrl = button.getAttribute('data-delete-url');
            const name = button.getAttribute('data-name');
            const type = button.getAttribute('data-type');

            const modalText = this.querySelector('#deleteModalText');
            const confirmBtn = this.querySelector('#confirmDeleteBtn');

            modalText.textContent = `Are you sure you want to delete ${type} "${name}"?`;
            confirmBtn.href = deleteUrl;
        });

        // Auto-dismiss alert toast
        setTimeout(() => {
            $('.glass-alert').fadeOut(400, function() { $(this).remove(); });
        }, 3500);
    </script>
</body>

</html>