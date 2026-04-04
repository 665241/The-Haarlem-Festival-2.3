<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

function isActive($url) {
    return strpos($_SERVER['REQUEST_URI'], $url) === 0 ? 'active' : '';
}
?>

<?php if (!empty($_SESSION['flash_success'])): ?>
    <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-4 shadow-lg border-0"
         role="alert"
         style="z-index: 9999; min-width: 300px;">
        <div class="d-flex align-items-center">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div><?= htmlspecialchars($_SESSION['flash_success']) ?></div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-4 shadow-lg border-0"
         role="alert"
         style="z-index: 9999; min-width: 300px;">
        <div class="d-flex align-items-center">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
            <div><?= htmlspecialchars($_SESSION['error']) ?></div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Haarlem Festival</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/assets/css/main.css"> 

    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js"></script>
    <script src="/js/wysiwyg.js"></script>

</head>

<body class="admin-page"> 
    <div id="admin-wrapper">
        <nav id="sidebar">
            <div class="px-4 mb-4">
                <h5 class="text-white">CMS</h5>
            </div>
            
            <div class="sidebar-heading">Management</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="/admin/dashboard" class="nav-link <?= isActive('/admin/dashboard') ?>">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/admin/users" class="nav-link <?= isActive('/admin/users') ?>">
                        <i class="bi bi-people me-2"></i> Manage Users
                    </a>
                </li>
            </ul>

            <div class="sidebar-heading mt-4">Events</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="/admin/jazz" class="nav-link <?= isActive('/admin/jazz') ?>">
                        <i class="bi bi-music-note-beamed me-2"></i> Jazz
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/admin/dance" class="nav-link <?= isActive('/admin/dance') ?>">
                        <i class="bi bi-activity me-2"></i> Dance
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/admin/yummy" class="nav-link <?= isActive('/admin/yummy') ?>">
                        <i class="bi bi-cup-hot me-2"></i> Yummy
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/admin/history/venues" class="nav-link <?= isActive('/admin/history/venues') ?>">
                        <i class="bi bi-geo-alt-fill me-2"></i> History
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/admin/kidsPage" class="nav-link <?= isActive('/admin/kidsPage') ?>">
                        <i class="bi bi-balloon-heart me-2"></i> Kids Page
                    </a>
                </li>
                 <li class="nav-item">
                    <a href="/admin/home/index" class="nav-link <?= isActive('/admin/home/index') ?>">
                        <i class="bi bi-balloon-heart me-2"></i> Home Page
                    </a>
                </li>
            </ul>

            <div class="mt-auto p-3">
                <a href="/" class="btn btn-outline-light btn-sm w-100 mb-2">View Website</a>
                <a href="/logout" class="btn btn-danger btn-sm w-100">Logout</a>
            </div>
        </nav>
    <main id="main-content">