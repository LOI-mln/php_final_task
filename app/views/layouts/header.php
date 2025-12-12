<!DOCTYPE html>
<html lang="fr" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reseau Social</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="d-flex flex-column min-vh-100">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">Reseau Social</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=post&action=index"><i
                                    class="bi bi-house-door-fill"></i> Fil d'actualité</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=post&action=create"><i
                                    class="bi bi-plus-square-fill"></i> Publier</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link position-relative" href="index.php?controller=notification&action=index">
                                <i class="bi bi-bell-fill"></i> Notifications
                                <span id="notif-badge"
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                    style="display:none;">
                                    0
                                </span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <form class="d-flex position-relative me-3" role="search" action="index.php" method="GET">
                        <input type="hidden" name="controller" value="post">
                        <input type="hidden" name="action" value="index">
                        <input class="form-control me-2" type="search" name="q" id="live-search" placeholder="Rechercher..."
                            aria-label="Search" autocomplete="off"
                            value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>">
                        <div id="search-results" class="list-group position-absolute w-100 mt-5 shadow"
                            style="display:none; z-index: 1000; top: 0;"></div>
                    </form>

                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> Mon Compte
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item"
                                        href="index.php?controller=profile&action=show&id=<?= $_SESSION['user_id'] ?>">Profil</a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item text-danger"
                                        href="index.php?controller=auth&action=logout">Déconnexion</a></li>
                            </ul>
                        </li>
                    </ul>
                <?php else: ?>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" href="index.php?controller=auth&action=login">Connexion</a>
                        </li>
                        <li class="nav-item"><a class="nav-link"
                                href="index.php?controller=auth&action=register">Inscription</a></li>
                    </ul>
                <?php endif; ?>

                <button id="dark-mode-toggle" class="btn btn-outline-light ms-2"><i
                        class="bi bi-moon-stars-fill"></i></button>
            </div>
        </div>
    </nav>
    <main class="container flex-grow-1">