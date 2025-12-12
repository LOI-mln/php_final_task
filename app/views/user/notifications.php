<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header">
                <h4 class="mb-0">Vos Notifications</h4>
            </div>
            <ul class="list-group list-group-flush">
                <?php if (empty($notifications)): ?>
                    <li class="list-group-item text-muted">Aucune nouvelle notification.</li>
                <?php else: ?>
                    <?php foreach ($notifications as $notif): ?>
                        <li
                            class="list-group-item d-flex justify-content-between align-items-center <?= $notif['is_read'] ? 'bg-light' : '' ?>">
                            <div>
                                <p class="mb-1">
                                    <?= htmlspecialchars($notif['message']) ?>
                                    <?php if ($notif['link']): ?>
                                        <a href="<?= $notif['link'] ?>" class="text-decoration-none ms-1">Voir</a>
                                    <?php endif; ?>
                                </p>
                                <small class="text-muted"><?= $notif['created_at'] ?></small>
                            </div>
                            <?php if (!$notif['is_read']): ?>
                                <button onclick="markRead(<?= $notif['id'] ?>, this)" class="btn btn-sm btn-outline-secondary">
                                    Marquer comme lu
                                </button>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>