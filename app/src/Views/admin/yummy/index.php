<?php 
use App\ViewModels\PageElementViewModel;
use App\Models\TextModel;
use App\Models\ImageModel;
use App\Models\ButtonModel;
/** @var PageElementViewModel $vm */
?>


<?php require __DIR__ . '/../../partials/adminHeader.php'; ?>


<?php foreach ($vm->getSections() as $section => $elements): ?>

<div class="d-flex justify-content-between align-items-center mb-3 mt-4">
    <h2 class="h4 mb-0">
        <i class="bi bi-layers-half me-2"></i>Section: <?= htmlspecialchars($section) ?>
    </h2>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th class="ps-3" style="width: 15%;">Element ID</th>
                    <th style="width: 50%;">Preview</th>
                    <th class="text-center" style="width: 35%;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($elements as $element): ?>
                <tr>
                    <td class="ps-3 text-muted small">#<?= htmlspecialchars($element->getId()) ?></td>
                    <td>
                        <div class="admin-element-preview border rounded bg-light" style="max-height: 120px; overflow: hidden; max-width: 300px;">
                            <?= $element->render(); ?>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex justify-content-center gap-2">
                            <?php if($element instanceof TextModel): ?>
                                <a href="/admin/elements/edit/<?= $element->getId() ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                            <?php elseif($element instanceof ImageModel): ?>
                                <a href="/admin/elements/editImg/<?= $element->getId() ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                            <?php elseif($element instanceof ButtonModel): ?>
                                <a href="/admin/elements/editButton/<?= $element->getId() ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                            <?php endif; ?>

                            <form method="POST" action="/admin/elements/delete" onsubmit="return confirm('Delete this element?')" class="m-0">
                                <input type="hidden" name="id" value="<?= $element->getId() ?>">
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white py-3">
        <form method="GET" action="/admin/elements/createForm" class="row g-2 align-items-center justify-content-center">
            <input type="hidden" name="section" value="<?= htmlspecialchars($section) ?>">
            <input type="hidden" name="pageName" value="<?= htmlspecialchars($pageName) ?>">
            
            <div class="col-auto">
                <label class="small fw-bold text-muted">Add New:</label>
            </div>
            <div class="col-auto">
                <select name="type" class="form-select form-select-sm">
                    <option value="text">Text</option>
                    <option value="image">Image</option>
                    <option value="button">Button</option>
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-sm btn-success">
                    <i class="bi bi-plus-lg"></i> Create Element
                </button>
            </div>
        </form>
    </div>
</div>

<?php endforeach; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-cup-hot-fill me-2"></i>Manage Restaurants</h2>
    <a href="/admin/yummy/create" class="btn btn-primary">Add New Restaurant</a>
</div>

<div class="card shadow-sm border-0 mb-5">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th class="ps-3">ID</th>
                    <th>Restaurant</th>
                    <th>Cuisine</th>
                    <th>Location</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($restaurants)): ?>
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No restaurants found in database.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($restaurants as $restaurant): ?>
                        <tr>
                            <td class="ps-3"><?= $restaurant->getId() ?></td>
                            <td><strong><?= htmlspecialchars($restaurant->getName()) ?></strong></td>
                            <td>
                                <span class="badge bg-info text-dark">
                                    <?= htmlspecialchars($restaurant->getCuisine()) ?>
                                </span>
                            </td>
                            <td>
                                <small class="text-muted"><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($restaurant->getLocation()) ?></small>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-info" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modal-<?= $restaurant->getId() ?>">
                                        <i class="bi bi-search"></i> View Detailed Page
                                    </button>

                                    <a href="/admin/yummy/edit/<?= $restaurant->getId() ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    
                                    <a href="/admin/yummy/delete/<?= $restaurant->getId() ?>" 
                                    class="btn btn-sm btn-outline-danger" 
                                    onclick="return confirm('Are you sure you want to delete this restaurant?')">
                                        <i class="bi bi-trash"></i> Delete
                                    </a>
                                </div>
                            </td>
                        </tr>

                        <div class="modal fade" id="modal-<?= $restaurant->getId() ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-light">
                                        <h5 class="modal-title">Content Preview: <?= htmlspecialchars($restaurant->getName()) ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body" style="max-height: 500px; overflow-y: auto;">                     
                                        <div>
                                            <label class="fw-bold text-muted small text-uppercase">Detailed Page Content</label>
                                            <div class="p-3 border rounded bg-white">
                                                <?= $restaurant->getLongDescription() ?: '<span class="text-muted">No detailed content.</span>' ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <a href="/admin/yummy/edit/<?= $restaurant->getId() ?>" class="btn btn-primary">Edit This Content</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<hr class="my-5 opacity-25">

<div class="d-flex justify-content-between align-items-center mb-4 mt-2">
    <h2><i class="bi bi-person-badge me-2"></i>Manage Chefs</h2>
    <a href="/admin/chefs/create" class="btn btn-primary">Add New Chef</a>
</div>

<div class="card shadow-sm border-0 mb-5">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th class="ps-3">ID</th>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Experience</th>
                    <th>Description</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($chefs)): ?>
                    <tr><td colspan="6" class="text-center py-4 text-muted">No chefs found in database.</td></tr>
                <?php else: ?>
                    <?php foreach ($chefs as $chef): ?>
                        <tr>
                            <td class="ps-3"><?= $chef->getId() ?></td>
                            <td>
                                <?php if($chef->getImageUrl()): ?>
                                    <img src="<?= $chef->getImageUrl() ?>" alt="Chef" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="bg-secondary rounded-circle d-inline-block" style="width: 40px; height: 40px;"></div>
                                <?php endif; ?>
                            </td>
                            <td><strong><?= htmlspecialchars($chef->getName()) ?></strong></td>
                            <td><?= $chef->getExperienceYears() ?> years</td>
                            <td><small class="text-truncate d-inline-block" style="max-width: 250px;"><?= strip_tags($chef->getDescription()) ?></small></td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="/admin/chefs/edit/<?= $chef->getId() ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i> Edit</a>
                                    <a href="/admin/chefs/delete/<?= $chef->getId() ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete chef?')"><i class="bi bi-trash"></i> Delete</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require __DIR__ . '/../../partials/adminFooter.php'; ?>