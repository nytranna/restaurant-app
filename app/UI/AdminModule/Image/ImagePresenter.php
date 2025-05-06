<?php

namespace App\UI\Admin\Image;

use Nette;
use App\Forms\ImageFormControl;
use App\Forms\ImageFormFactory;
use Nette\Utils\FileSystem;
use Nette\IOException;

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

    #[Requires(methods: 'POST', sameOrigin: true)]
    public function actionDelete(int $id): void {

        $path = '/var/www/restaurant-app/www/images/' . $this->imageFacade->getOne(['id' => $id])->name;

        $this->imageFacade->getOne(['id' => $id])->delete();

//        unlink($path);

        try {
            FileSystem::delete($path);
        } catch (IOException $e) {
            echo "Nepodařilo se smazat soubor: " . $e->getMessage();
        }

        $this->redirect('Image:default');
    }

    public function handleUpdateOrder(): void {

        $data = json_decode($_POST['order_data'], true);
        $dbTable = $_POST['db_table'] ?? null;

        foreach ($data as $id => $position) {
                $this->imageFacade->getOne(['id' => $id])->update(['order' => $position]);
            
        }

        $this->sendJson(['status' => 'ok']);
    }

    protected function createComponentImageForm(): ImageFormControl {
        return $this->imageFormFactory->create();
    }
}
