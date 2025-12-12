<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm mb-4">
            <div class="card-body text-center">
                <i class="bi bi-person-circle display-1 text-primary mb-3"></i>
                <h2 class="card-title"><?= htmlspecialchars($user['nom']) ?></h2>
                <p class="text-muted"><?= htmlspecialchars($user['email']) ?></p>
                <p class="small text-secondary">Inscrit le <?= date('d/m/Y', strtotime($user['date_inscription'])) ?>
                </p>

                <div class="mt-3">
                    <span class="badge bg-primary rounded-pill"><?= count($posts) ?> Publications</span>
                </div>

                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $user['id']): ?>
                    <div class="mt-4">
                        <a href="index.php?controller=profile&action=edit" class="btn btn-outline-secondary btn-sm">Modifier
                            le profil</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <h3 class="mb-3">Publications de <?= htmlspecialchars($user['nom']) ?></h3>

        <?php if (empty($posts)): ?>
            <div class="alert alert-info">Aucune publication pour le moment.</div>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="index.php?controller=post&action=show&id=<?= $post['id'] ?>"
                                class="text-decoration-none text-dark">
                                <?= htmlspecialchars($post['titre']) ?>
                            </a>
                        </h5>
                        <h6 class="card-subtitle mb-2 text-muted">
                            <small><?= date('d/m/Y H:i', strtotime($post['date_publication'])) ?></small>
                        </h6>
                        <p class="card-text"><?= nl2br(substr(htmlspecialchars($post['contenu']), 0, 150)) ?>...</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="index.php?controller=post&action=show&id=<?= $post['id'] ?>"
                                class="btn btn-sm btn-outline-primary">Lire la suite</a>
                            <span class="text-muted small">
                                <i class="bi bi-heart-fill text-danger"></i> <?= $post['like_count'] ?? 0 ?>
                            </span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>