<?php require __DIR__ . '/../../partials/adminHeader.php'; ?>

<div class="container py-4">
    <h2 class="mb-4">Create Dance Artist</h2>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="/admin/dance/create" method="POST" enctype="multipart/form-data">

                <div class="mb-3">
                    <label for="name" class="form-label">Artist Name</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="short_description" class="form-label">Short Description</label>
                    <textarea name="short_description" id="short_description" class="form-control" rows="3"></textarea>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Full Description</label>
                    <textarea name="description" id="description" class="form-control" rows="6"></textarea>
                </div>

                <div class="mb-3">
                    <label for="image_file" class="form-label">Artist Image</label>
                    <input type="file" name="image_file" id="image_file" class="form-control" accept="image/*">
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Save Artist</button>
                    <a href="/admin/dance" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../../partials/adminFooter.php'; ?>