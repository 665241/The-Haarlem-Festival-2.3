<form method="POST" action="/admin/elements/store">
    <input type="hidden" name="type" value="text">
    <input type="hidden" name="section" value="<?= $section ?>">
    <input type="hidden" name="pageName" value="home">

    <textarea name="content" placeholder="Enter text"></textarea>

    <button>Create Text</button>
</form>