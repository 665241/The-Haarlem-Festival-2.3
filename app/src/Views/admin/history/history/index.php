<?php 
use App\ViewModels\PageElementViewModel;
use App\Models\TextModel;
use App\Models\ImageModel;
use App\Models\ButtonModel;
/** @var PageElementViewModel $vm */
?>
<?php require __DIR__ . '/../../partials/adminHeader.php'; ?>


<style>
table {
    border-collapse: collapse;
    width: 85%;
    margin: 30px auto;
}

th, td {
    border: 1px solid #aaa;
    padding: 10px;
    text-align: center;
}

th {
    background: #f2f2f2;
}

.actions{
    display:flex;
    justify-content:center;
    gap:8px;
}

.edit-btn{
    background:#4CAF50;
    color:white;
    border:none;
    padding:6px 12px;
}

.delete-btn{
    background:#e53935;
    color:white;
    border:none;
    padding:6px 12px;
}

.add-btn{
    margin:20px auto;
    display:block;
    padding:10px 20px;
}

.section-title{
    text-align:center;
    margin-top:40px;
}
td img {
    max-width: 150px;
    height: auto;
    display: block;
    margin: 0 auto;
}
</style>
</head>
<body>

<h1 style="text-align:center;">Admin Page Manager</h1>

<a href="/admin/sections/create">
    <button class="add-btn">Add Section</button>
</a>


<?php foreach ($vm->getSections() as $section => $elements): ?>

<h2 class="section-title">
    Section: <?= htmlspecialchars($section) ?>
</h2>

<table>
<thead>
<tr>
    <th>Element ID</th>
    <th>Type</th>
    <th>Preview</th>
    <th>Actions</th>
</tr>
</thead>

<tbody>

<?php foreach ($elements as $element): ?>

<tr>
<td><?= htmlspecialchars($element->getId()) ?></td>

<td><?= htmlspecialchars(get_class($element)) ?></td>

<td>
    <?= $element->render(); ?>
</td>

<td>
<div class="actions">
<?php if($element instanceof TextModel){ ?>
 <a href="/admin/elements/edit/<?= $element->getId() ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <?php } elseif($element instanceof ImageModel){?>
                                   <a href="/admin/elements/editImg/<?= $element->getId() ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>  <?php } elseif($element instanceof ButtonModel){?>
                                   <a href="/admin/elements/editButton/<?= $element->getId() ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a> <?php } ?>

<form method="POST" action="/admin/elements/delete"
onsubmit="return confirm('Delete this element?')">
<input type="hidden" name="id" value="<?= $element->getId() ?>">
<button class="delete-btn">Delete</button>
</form>

</div>
</td>

</tr>

<?php endforeach; ?>

</tbody>
</table>

<div style="text-align:center;">
<form method="GET" action="/admin/elements/create">
<input type="hidden" name="section" value="<?= htmlspecialchars($section) ?>">
<button>Add Element to Section</button>
</form>
</div>

<?php endforeach; ?>
