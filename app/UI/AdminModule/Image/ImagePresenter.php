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

        $images = $this->imageFacade->getAll();
        $this->template->images = $images;
    }

    public function renderEdit($id = null): void {

        if ($id) {
            $image = $this->imageFacade->getOne(['id' => $id]);

            if (!$image) {
                $this->error(404);
            }

            $this['imageForm']->setDefaults($image);
        }

        $this->template->imageData = $image ?? null;
    }

    public function handleGallery(int $id): void {
        $image = $this->imageFacade->getOne(['id' => $id]);

        if ($image) {
            $image->update(['is_gallery' => !$image->is_gallery]);
            $this->flashMessage('Galerie byla změněna.', 'success');
        } else {
            $this->flashMessage('Položka nebyla nalezena.', 'error');
        }

        $this->redirect('this');
    }




    protected function createComponentImageForm(): ImageFormControl {
        return $this->imageFormFactory->create();
    }
}
