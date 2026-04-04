<?php require __DIR__ . '/../../partials/adminHeader.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="d-flex align-items-center mb-4">
            <a href="/admin/users" class="btn btn-outline-secondary me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h2 class="mb-0">Edit User</h2>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Updating: <?= htmlspecialchars($user->getFullName()) ?></h5>
            </div>
            <div class="card-body p-4">
                <form action="/admin/users/edit?id=<?= $user->getId() ?>" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $user->getId() ?>">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Full Name</label>
                            <input type="text" name="fullName" class="form-control" 
                                   value="<?= htmlspecialchars($user->getFullName()) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Username</label>
                            <input type="text" name="userName" class="form-control" 
                                   value="<?= htmlspecialchars($user->getUserName()) ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Email Address</label>
                            <input type="email" name="email" class="form-control" 
                                   value="<?= htmlspecialchars($user->getEmail()) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Phone Number</label>
                            <input type="text" name="phoneNumber" class="form-control" 
                                   value="<?= htmlspecialchars($user->getPhoneNumber()) ?>">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Role</label>
                            <select name="role" class="form-select">
                                <option value="User" <?= $user->getRole() === 'User' ? 'selected' : '' ?>>User</option>
                                <option value="Admin" <?= $user->getRole() === 'Admin' ? 'selected' : '' ?>>Admin</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Current Profile Picture</label>
                            <div class="mb-2">
                                <?php if ($user->getProfilePicture()): ?>
                                    <img src="<?= $user->getProfilePicture() ?>" alt="Profile" class="img-thumbnail" style="height: 50px;">
                                <?php else: ?>
                                    <span class="text-muted small">No image set</span>
                                <?php endif; ?>
                            </div>
                            <input type="file" name="profilePicture" class="form-control" accept="image/*">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="/admin/users" class="btn btn-light px-4">Cancel</a>
                        <button type="submit" class="btn btn-warning px-4">Update User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../../partials/adminFooter.php'; ?>