<?php
session_start();

if (!isset($_SESSION['user_id'], $_SESSION['user_email'])) {
    header("Location: login.php");
    exit;
}

include "db_conn.php";

$stmt = $conn->prepare("SELECT * FROM feedback ORDER BY id DESC");
$stmt->execute();
$feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Feedback Moderation – BookVerse 3D</title>
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
                
                <button class="navbar-toggler border-0 text-white shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarFeedList">
                    <i class="fas fa-bars fa-lg"></i>
                </button>

                <div class="collapse navbar-collapse" id="navbarFeedList">
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
                            <a class="nav-link active" href="feedback_list.php"><i class="fas fa-comments me-1"></i> Feedbacks</a>
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

    <!-- Main Container -->
    <main class="admin-dashboard-container">
        <div class="glass-table-wrapper">
            <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
                <div>
                    <h2 class="font-display h4 mb-1 text-white">
                        <i class="fas fa-comments text-gradient me-2"></i> User Feedbacks & Ratings
                    </h2>
                    <p class="text-secondary small mb-0">Total <?= count($feedbacks) ?> reader reviews received</p>
                </div>
                <a href="admin.php" class="btn-glass-outline btn-glass-sm">
                    <i class="fas fa-arrow-left"></i> Dashboard
                </a>
            </div>

            <?php if (empty($feedbacks)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-comment-slash fa-3x text-muted mb-3"></i>
                    <p class="text-secondary">No feedbacks submitted yet.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="glass-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Reader Name</th>
                                <th>Email</th>
                                <th>Rating</th>
                                <th>Feedback Message</th>
                                <th>Submitted At</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 0; foreach ($feedbacks as $fb): $i++; ?>
                                <tr>
                                    <td class="text-secondary font-monospace"><?= $i ?></td>
                                    <td class="font-weight-bold text-white">
                                        <i class="fas fa-user-circle text-primary me-2"></i><?= htmlspecialchars($fb['name']) ?>
                                    </td>
                                    <td class="text-secondary small"><?= htmlspecialchars($fb['email']) ?></td>
                                    <td>
                                        <span class="star-badge-gold">
                                            <?php 
                                                $rating = intval($fb['rating']);
                                                for ($s = 1; $s <= 5; $s++) {
                                                    echo $s <= $rating ? '<i class="fas fa-star"></i>' : '<i class="far fa-star text-muted"></i>';
                                                }
                                            ?>
                                        </span>
                                    </td>
                                    <td style="max-width: 320px;">
                                        <div class="text-light small"><?= nl2br(htmlspecialchars($fb['message'])) ?></div>
                                    </td>
                                    <td class="text-secondary small font-monospace"><?= htmlspecialchars($fb['created_at']) ?></td>
                                    <td class="text-end">
                                        <button type="button" class="btn-table-delete" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteFeedbackModal"
                                                data-feedback-id="<?= $fb['id'] ?>">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteFeedbackModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content glass-modal p-3">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title font-display text-white">
                        <i class="fas fa-trash-alt text-danger me-2"></i> Delete Feedback
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4">
                    <p class="mb-2 text-light fs-5">Are you sure you want to delete this feedback?</p>
                    <p class="text-secondary small mb-0">This operation cannot be reversed.</p>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn-glass-outline btn-glass-sm" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteFeedbackForm" action="php/delete-feedback.php" method="POST" style="display: inline;">
                        <input type="hidden" name="feedback_id" id="feedback-to-delete-id">
                        <button type="submit" class="btn-glass-3d btn-glass-sm" style="background: var(--accent-rose);">
                            <i class="fas fa-trash"></i> Yes, Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/3d-glass.js?v=<?= time() ?>"></script>
    <script>
        const deleteFeedbackModal = document.getElementById('deleteFeedbackModal');
        deleteFeedbackModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const feedbackId = button.getAttribute('data-feedback-id');
            const hiddenInput = this.querySelector('#feedback-to-delete-id');
            hiddenInput.value = feedbackId;
        });

        // Auto-dismiss alert toast
        setTimeout(() => {
            $('.glass-alert').fadeOut(400, function() { $(this).remove(); });
        }, 3500);
    </script>
</body>

</html>