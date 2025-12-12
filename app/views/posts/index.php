<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <h2 class="mb-4">Fil d'actualité</h2>

        <?php foreach ($posts as $post): ?>
            <div class="card mb-4 shadow-sm post" data-id="<?= $post['id'] ?>">
                <div class="card-body">
                    <h5 class="card-title"
                        contenteditable="<?= ($_SESSION['user_id'] == $post['utilisateur_id']) ? 'true' : 'false' ?>"
                        onblur="updatePost(<?= $post['id'] ?>, 'titre', this.innerText)">
                        <?= htmlspecialchars($post['titre']) ?>
                    </h5>
                    <h6 class="card-subtitle mb-2 text-muted fst-italic">
                        <small>Par <?= htmlspecialchars($post['author_name']) ?> le <?= $post['date_publication'] ?></small>
                    </h6>

                    <div class="card-text mt-3 mb-3 p-1 rounded"
                        contenteditable="<?= ($_SESSION['user_id'] == $post['utilisateur_id']) ? 'true' : 'false' ?>"
                        onblur="updatePost(<?= $post['id'] ?>, 'contenu', this.innerText)">
                        <?= nl2br(htmlspecialchars($post['contenu'])) ?>
                    </div>
                </div>

                <div class="card-footer bg-transparent d-flex justify-content-between align-items-center">
                    <!-- LIKE BTN -->
                    <button class="btn btn-sm like-btn <?= $post['has_liked'] ? 'btn-danger' : 'btn-outline-danger' ?>"
                        onclick="toggleLike(<?= $post['id'] ?>, this)">
                        <i class="bi bi-heart-fill"></i> J'aime <span
                            class="badge bg-light text-dark border ms-1 count"><?= $post['like_count'] ?></span>
                    </button>

                    <div>
                        <a href="index.php?controller=post&action=show&id=<?= $post['id'] ?>"
                            class="btn btn-sm btn-primary">
                            <i class="bi bi-chat-left-text"></i> Commentaires
                        </a>

                        <?php if ($_SESSION['user_id'] == $post['utilisateur_id']): ?>
                            <a href="index.php?controller=post&action=delete&id=<?= $post['id'] ?>"
                                class="btn btn-sm btn-outline-danger ms-2" onclick="return confirm('Êtes-vous sûr ?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>