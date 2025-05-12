<?php

namespace App\Forms;

use Nette\Application\UI\Control;
use Nette\Application\UI\Form;

class NewsFormControl extends Control {

    public function __construct(
            private BaseFormFactory $baseFormFactory,
            private \App\Model\Facade\NewsFacade $newsFacade,
            private \App\Model\Facade\ImageFacade $imageFacade
    ) {
        
    }

    public function createComponentForm(): Form {
        $form = $this->baseFormFactory->create();

        $form->addText('title', 'Nadpis:')
                ->setRequired('Prosím vyplňte nadpis.');

        $form->addTextArea('text', 'Text:', null, 15)
                ->setRequired('Prosím vyplňte text.');

        $is_shown = ['1' => 'ano', '0' => 'ne'];

        $form->addRadioList('is_shown', 'Zobrazit na stránce:', $is_shown);

        $images = [];
        foreach ($this->imageFacade->getAll() as $img) {
            $images[$img->id] = $img->name;
        }

        $form->addSelect('id_image', 'Obrázek v pozadí:', $images)
                ->setPrompt('--- bez obrázku ---');

        $form->addHidden('id');

        $form->addSubmit('send', 'Uložit');

        $form->onSuccess[] = $this->submitted(...);

        return $form;
    }

    public function setDefaults($data) {

        $id_image = $this->newsFacade->getOne(['id' => $data->id])->id_image;

        $data = ['id' => $data->id,
            'title' => $data->title,
            'text' => $data->text,
            'is_shown' => $data->is_shown,
            'id_image' => $id_image
        ];

        $this['form']->setDefaults($data);
    }

    public function submitted(Form $form, \stdClass $data): void {

        $newsData = [
            'title' => $data->title,
            'text' => $data->text,
            'is_shown' => $data->is_shown,
            'id_image' => $data->id_image
        ];

        if ($data->id) {

            $this->newsFacade->getOne(['id' => $data->id])->update($newsData);
        } else {


            $this->newsFacade->insert($newsData);
        }


        if (!$form->hasErrors()) {

            $message = $data->id ? 'Změna uživatele proběhla úspěšně' : 'Vytvoření nového uživatele proběhlo úspěšně.';

            $form->getPresenter()->flashMessage($message, 'success');
            $form->getPresenter()->redirect('News:default');
        }
    }

    public function render() {
        $this->template->render(__DIR__ . '/_newsForm.latte');
    }
}
