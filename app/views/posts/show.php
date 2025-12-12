<div class="row justify-content-center">
    <div class="col-md-8">
        <!-- Post -->
        <div class="card mb-4 shadow-sm" data-id="<?= $post['id'] ?>">
            <div class="card-body">
                <h3 class="card-title"><?= htmlspecialchars($post['titre']) ?></h3>
                <h6 class="card-subtitle mb-3 text-muted">
                    Par <?= htmlspecialchars($post['author_name']) ?> le <?= $post['date_publication'] ?>
                </h6>
                <div class="card-text p-2 rounded bg-light border">
                    <?= nl2br(htmlspecialchars($post['contenu'])) ?>
                </div>
            </div>
        </div>

        <!-- Comments Section -->
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h4 class="mb-0">Commentaires</h4>
            </div>
            <div class="card-body">
                <div id="comments-list" class="mb-4">
                    <?php if (empty($comments)): ?>
                        <p class="text-muted text-center">Soyez le premier à commenter !</p>
                    <?php else: ?>
                        <?php foreach ($comments as $comment): ?>
                            <div class="d-flex mb-3 border-bottom pb-3 comment" data-id="<?= $comment['id'] ?>">
                                <div class="vote-controls d-flex flex-column align-items-center me-3 border-end pe-3">
                                    <button class="btn btn-sm text-secondary p-0 vote-btn"
                                        onclick="vote(<?= $comment['id'] ?>, 'up', this)">
                                        <i class="bi bi-caret-up-fill fs-4"></i>
                                    </button>
                                    <span class="vote-score fw-bold"><?= $comment['score'] ?></span>
                                    <button class="btn btn-sm text-secondary p-0 vote-btn"
                                        onclick="vote(<?= $comment['id'] ?>, 'down', this)">
                                        <i class="bi bi-caret-down-fill fs-4"></i>
                                    </button>
                                </div>

                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between">
                                        <strong><?= htmlspecialchars($comment['author_name']) ?></strong>
                                        <small class="text-muted"><?= $comment['date_commentaire'] ?></small>
                                    </div>
                                    <p class="mt-1 mb-0"
                                        contenteditable="<?= ($_SESSION['user_id'] == $comment['utilisateur_id']) ? 'true' : 'false' ?>"
                                        onblur="updateComment(<?= $comment['id'] ?>, this.innerText)">
                                        <?= nl2br(htmlspecialchars($comment['contenu'])) ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Add Comment -->
                <div class="input-group">
                    <textarea id="new-comment-content" class="form-control" placeholder="Ajouter un commentaire..."
                        rows="2"></textarea>
                    <button class="btn btn-primary" onclick="addComment(<?= $post['id'] ?>)">Envoyer</button>
                </div>
            </div>
        </div>
    </div>
</div>