<?php

namespace App\Forms;

use Nette\Application\UI\Control;
use Nette\Application\UI\Form;

class ImageFormControl extends Control {

    public function __construct(
            private BaseFormFactory $baseFormFactory,
            private \App\Model\Facade\ImageFacade $imageFacade
    ) {
        
    }

    public function createComponentForm(): Form {


        $form = $this->baseFormFactory->create();

        $form->addHidden('id');
        $form->addHidden('image_name');

        $form->addUpload('image', 'Obrázek:')
                ->addRule(Form::IMAGE, 'Musí být obrázek (JPEG, PNG, GIF).')
                ->setRequired('Nahrajte obrázek.');

        $is_gallery = ['1' => 'ano', '0' => 'ne'];

        $form->addRadioList('is_gallery', 'Zobrazit v galerii:', $is_gallery);

        $form->addSubmit('send', 'Uložit');

        $form->onSuccess[] = $this->submitted(...);

        return $form;
    }

    public function submitted(Form $form, \stdClass $data): void {

        $image = $data->image;

        $imageName = $image->name;

        $imageData = [
            'name' => $imageName,
            'is_gallery' => $data->is_gallery
        ];

        if ($data->id) {

            $this->imageFacade->getOne(['id' => $data->id])->update($imageData);
        } else {

            if ($image->isOk()) {
                $path = __DIR__ . '/../../../www/images';

                $this->imageFacade->insert($imageData);

                $image->move($path . '/' . $imageName);

                $form->getPresenter()->flashMessage('Obrázek ' . $imageName . 'byl úspěšně nahrán.', 'success');
                $form->getPresenter()->redirect('Image:default');
            } else {

                $form->getPresenter()->flashMessage('Chyba při nahrávání souboru', 'danger');
            }
        }

        if (!$form->hasErrors()) {

            $message = $data->id ? 'Změna obrázku proběhla úspěšně' : 'Vytvoření nového obrázku proběhlo úspěšně.';

            $form->getPresenter()->flashMessage($message, 'success');
            $form->getPresenter()->redirect('Image:default');
        }
    }

    public function render() {
        $this->template->render(__DIR__ . '/_imageForm.latte');
    }
}
