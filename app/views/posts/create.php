<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Créer un nouveau post</h4>
            </div>
            <div class="card-body">
                <form action="index.php?controller=post&action=create" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Titre</label>
                        <input type="text" name="titre" class="form-control" placeholder="Titre de votre publication"
                            required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contenu</label>
                        <textarea name="contenu" class="form-control" rows="5" placeholder="Qu'avez-vous en tête ?"
                            required></textarea>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Publier</button>
                        <a href="index.php" class="btn btn-outline-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>