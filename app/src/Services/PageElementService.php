<?php 
namespace App\Services;

use App\Services\Interfaces\IPageElementService;
use App\Repositories\Interfaces\IPageElementRepository;
use App\Models\PageElementModel;
use App\Repositories\ButtonRepository;
use App\Repositories\ImageRepository;
use App\Repositories\TextRepository;
use App\Repositories\PageElementRepository;

use App\Repositories\Interfaces\IButtonRepository;
use App\Repositories\Interfaces\IImageRepository;
use App\Repositories\Interfaces\ITextRepository;

class PageElementService implements IPageElementService
{
    private IPageElementRepository $pageElementRepository;
    private IButtonRepository $buttonRepo;
    private IImageRepository $imageRepo;
    private ITextRepository $textRepo;
    public function __construct(
    ) {
       $this->pageElementRepository = new PageElementRepository();
       $this->buttonRepo = new ButtonRepository();
       $this->imageRepo = new ImageRepository();
       $this->textRepo = new TextRepository();

    }
   
     /**
     * @return PageElementModel[]
     */
    public function getByPageName(string $pageName): array
    {
     return $this->pageElementRepository->getByPageName($pageName);
    }

    public function getById(int $id): ?PageElementModel
    {
       return $this->pageElementRepository->getById($id);
    }
    public function getPageSections(string $pageName): array
{
    $elements = $this->getByPageName($pageName);
    $sections = [];

    foreach ($elements as $el) {
        $model = match ($el->getType()) {
            'text' => $this->textRepo->getById($el->getSubElementId()),
            'image' => $this->imageRepo->getById($el->getSubElementId()),
            'button' => $this->buttonRepo->getById($el->getSubElementId()),
            default => null
        };

        if ($model !== null) {
            $sections[$el->getSection()][] = $model;
        }
    }

    return $sections;
}
public function createElement(string $type,int $section,string $pageName,array $data): bool {

    switch ($type) {
        case 'text':
            $subId = $this->textRepo->create($data['content']);
            break;

        // case 'image':
        //     $subId = $this->imageRepo->createImage(
        //         $data['imgURL'],
        //         $data['altText']
        //     );
        //     break;

        // case 'button':
        //     $subId = $this->buttonRepo->createButton(
        //         $data['text'],
        //         $data['path']
        //     );
        //     break;

        default:
            throw new \Exception("Invalid type");
    }

    $position = $this->pageElementRepository
        ->getNextPosition($pageName, $section);

    return $this->pageElementRepository->create(
        $subId,
        $type,
        $pageName,
        $section,
        $position
    );
}
}