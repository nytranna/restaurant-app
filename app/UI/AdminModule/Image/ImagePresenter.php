<?php

namespace App\UI\Admin\Image;

use App\Model\PostFacade;
use Nette;
use App\Forms\ImageFormControl;
use App\Forms\ImageFormFactory;

final class ImagePresenter extends Nette\Application\UI\Presenter {

    use \App\Core\PresenterTrait;

    public function __construct(
            private ImageFormFactory $imageFormFactory,
            private \App\Model\Facade\ImageFacade $imageFacade
    ) {
        
    }

    public function renderDefault(): void {
        $image = $this->imageFacade->getOne();
        $this->template->image = $image;

    }

    public function renderEdit($id = null): void {


    }

    protected function createComponentImageForm(): ImageFormControl {
        return $this->ImageFormFactory->create();
    }
}
