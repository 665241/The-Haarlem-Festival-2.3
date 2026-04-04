<?php

namespace App\Controllers;

use App\Services\PageElementService;
use App\Repositories\TextRepository;
use App\Repositories\ImageRepository;
use App\Models\PageElementModel;
use App\Models\TextModel;
use App\Models\ImageModel;

class PageElementController
{
    private PageElementService $service;
    private TextRepository $textService;
     private ImageRepository $imgService;

    public function __construct()
    {
        $this->service = new PageElementService();
         $this->textService = new TextRepository();
          $this->imgService = new ImageRepository();
    }


    // public function store(): void
    // {
    //     $element = new PageElementModel();

    //     $element->setSection($_POST['section']);
    //     $element->setType($_POST['type']);
    //     $element->setContent($_POST['content']);
    //     $element->setOrder((int)$_POST['display_order']);

    //     $this->service->createElement($element);

    //     header('Location: /admin/page-elements');
    //     exit;
    // }

    public function showEditForm(array $vars): void
    {
        // $id = (int)$vars['id'];
        // $pageElement = $this->service->getById($id);
        // if($pageElement->getType()=='text'){
        //  $text = $this->textService->getById($pageElement->getId());
        //   include __DIR__ . '/../Views/admin/text/textEditForm.php';
        // }elseif($pageElement->getType()=='image'){
        // $img = $this->imgService->getById($pageElement->getId());
        // include __DIR__ . '/../Views/admin/img/imgEditForm.php';
        // }
         $id = (int)$vars['id'];
       
         $text = $this->textService->getById($id);
          include __DIR__ . '/../Views/admin/text/textEditForm.php';
    }
     public function showImgEditForm(array $vars): void
    {
        $id = (int)$vars['id'];
        $img = $this->imgService->getById($id);
        include __DIR__ . '/../Views/admin/img/imgEditForm.php';
        
    }
    public function saveTextChanges(array $vars):void
    {
       $id = (int)$vars['id'];
       $newText = $_POST['newText'];
       $this->textService->saveTextChanges($id, $newText);
      header('Location: /admin/kidsPage');
    }

    // public function update(array $vars): void
    // {
    //     $id = (int)$vars['id'];

    //     $element = $this->service->getElementById($id);

    //     if (!$element) {
    //         header('Location: /admin/page-elements');
    //         exit;
    //     }

    //     $element->setSection($_POST['section']);
    //     $element->setType($_POST['type']);
    //     $element->setContent($_POST['content']);
    //     $element->setOrder((int)$_POST['display_order']);

    //     $this->service->updateElement($element);

    //     header('Location: /admin/page-elements');
    //     exit;
    // }

    // public function delete(array $vars): void
    // {
    //     $id = (int)$vars['id'];

    //     if ($this->service->deleteElement($id)) {
    //         header('Location: /admin/page-elements');
    //         exit;
    //     }

    //     echo "Error deleting element.";
    // }
    public function saveImgChanges(array $vars): void
{
    $id = (int)$vars['id'];

    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        echo "Upload failed.";
        return;
    }

    $file = $_FILES['image'];

    $fileName = uniqid() . '_' . basename($file['name']);
    $targetPath = __DIR__ . '/../../public/assets/images' . $fileName;

    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        echo "Failed to move uploaded file.";
        return;
    }

    $imgURL = '/assets/images' . $fileName;
    $altText = $_POST['altText'] ?? '';

    $this->imgService->updateImage($id, $imgURL, $altText);

    header('Location: /admin/kidsPage');
    exit;
}
public function createForm(): void
{
    $type = $_GET['type'] ?? null;
    $section = $_GET['section'] ?? null;

    if (!$type || !$section) {
        echo "Invalid request";
        return;
    }

    require __DIR__ . "/../Views/admin/elements/create_" . $type . ".php";
}
  public function store()
    {
        $type = $_POST['type'];
        $section = (int)$_POST['section'];
        $pageName = $_POST['pageName'];

        $this->service->createElement(
            $type,
            $section,
            $pageName, 
            $_POST
        );

        header("Location: /admin/home");
        exit;
    }
}