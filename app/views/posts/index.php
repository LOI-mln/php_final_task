<div class="row justify-content-center">
    <div class="col-md-8">
        <h2 class="mb-4">Fil d'actualité</h2>

        <?php if (empty($posts)): ?>
            <div class="alert alert-info text-center">
                <i class="bi bi-info-circle-fill display-4 d-block mb-3"></i>
                <p class="lead mb-0">Aucune publication trouvée.</p>
                <?php if (isset($_GET['q'])): ?>
                    <p class="mt-2"><a href="index.php?controller=post&action=index" class="btn btn-sm btn-outline-primary">Voir
                            tout</a></p>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <div class="card mb-4 shadow-sm post" data-id="<?= $post['id'] ?>">
                    <div class="card-body">
                        <h5 class="card-title"
                            contenteditable="<?= (isset($_SESSION['user_id']) && (int) $_SESSION['user_id'] === (int) $post['utilisateur_id']) ? 'true' : 'false' ?>"
                            onblur="updatePost(<?= $post['id'] ?>, 'titre', this.innerText)">
                            <?= htmlspecialchars($post['titre']) ?>
                        </h5>
                        <h6 class="card-subtitle mb-2 text-muted fst-italic">
                            <small>Par <?= htmlspecialchars($post['author_name']) ?> le <?= $post['date_publication'] ?></small>
                        </h6>

                        <div class="card-text mt-3 mb-3 p-1 rounded"
                            contenteditable="<?= (isset($_SESSION['user_id']) && (int) $_SESSION['user_id'] === (int) $post['utilisateur_id']) ? 'true' : 'false' ?>"
                            onblur="updatePost(<?= $post['id'] ?>, 'contenu', this.innerText)">
                            <?= nl2br(htmlspecialchars($post['contenu'])) ?>
                        </div>
                    </div>

                    <div class="card-footer bg-transparent d-flex justify-content-between align-items-center">
                        <button class="btn btn-sm btn-like <?= $post['has_liked'] ? 'liked' : '' ?>"
                            onclick="toggleLike(<?= $post['id'] ?>, this)">
                            <i class="bi <?= $post['has_liked'] ? 'bi-heart-fill' : 'bi-heart' ?>"></i>
                            <span class="count ms-1 fw-bold"><?= $post['like_count'] ?></span>
                        </button>

                        <div>
                            <a href="index.php?controller=post&action=show&id=<?= $post['id'] ?>"
                                class="btn btn-sm btn-primary">
                                <i class="bi bi-chat-left-text-fill"></i> Commentaires
                            </a>

                            <?php if ($_SESSION['user_id'] == $post['utilisateur_id']): ?>
                                <a href="index.php?controller=post&action=delete&id=<?= $post['id'] ?>"
                                    class="btn btn-sm btn-outline-danger ms-2" onclick="return confirm('Êtes-vous sûr ?')">
                                    <i class="bi bi-trash-fill"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>