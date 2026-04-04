<?php require __DIR__ . '/../../partials/adminHeader.php'; ?>

<div class="container py-4">
    <h2 class="mb-4">Edit Dance Artist</h2>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="/admin/dance/edit/<?= $artist->getId() ?>" method="POST" enctype="multipart/form-data">

                <div class="mb-3">
                    <label for="name" class="form-label">Artist Name</label>
                    <input
                        type="text"
                        name="name"
                        id="name"
                        class="form-control"
                        value="<?= htmlspecialchars($artist->getName()) ?>"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label for="short_description" class="form-label">Short Description</label>
                    <textarea name="short_description" id="short_description" class="form-control" rows="3"><?= htmlspecialchars($artist->getShortDescription() ?? '') ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Full Description</label>
                    <textarea name="description" id="description" class="form-control" rows="6"><?= htmlspecialchars($artist->getDescription() ?? '') ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Current Image</label><br>
                    <?php if ($artist->getImageUrl()): ?>
                        <img src="<?= htmlspecialchars($artist->getImageUrl()) ?>" alt="Artist Image" class="img-thumbnail mb-2" style="max-height: 120px;">
                    <?php else: ?>
                        <p class="text-muted">No image uploaded.</p>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="image_file" class="form-label">Upload New Image</label>
                    <input type="file" name="image_file" id="image_file" class="form-control" accept="image/*">
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Update Artist</button>
                    <a href="/admin/dance" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../../partials/adminFooter.php'; ?>