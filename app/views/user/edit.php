<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h4 class="mb-0">Modifier mon profil</h4>
            </div>
            <div class="card-body">
                <form action="index.php?controller=profile&action=update" method="POST">
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="nom" name="nom" 
                               value="<?= htmlspecialchars($user['nom']) ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?= htmlspecialchars($user['email']) ?>" required>
                    </div>

                    <hr class="my-4">
                    <h5 class="mb-3">Changer le mot de passe <small class="text-muted fs-6 fw-normal">(Laisser vide pour ne pas changer)</small></h5>

                    <div class="mb-3">
                        <label for="password" class="form-label">Nouveau mot de passe</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>

                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirmer le mot de passe</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="index.php?controller=profile&action=show" class="btn btn-outline-secondary me-md-2">Annuler</a>
                        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
