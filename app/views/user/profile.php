<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm mb-4">
            <div class="card-body text-center">
                <i class="bi bi-person-circle display-1 text-primary mb-3"></i>
                <h2 class="card-title"><?= htmlspecialchars($user['nom']) ?></h2>
                <p class="text-muted"><?= htmlspecialchars($user['email']) ?></p>
                <p class="small text-secondary">Inscrit le <?= $user['date_inscription'] ?></p>
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
                            <small><?= $post['date_publication'] ?></small>
                        </h6>
                        <p class="card-text"><?= nl2br(substr(htmlspecialchars($post['contenu']), 0, 150)) ?>...</p>
                        <a href="index.php?controller=post&action=show&id=<?= $post['id'] ?>"
                            class="btn btn-sm btn-outline-primary">Lire la suite</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>