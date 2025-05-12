<?php

namespace App\UI\Admin\News;

use Nette;
use App\Forms\NewsFormControl;
use Nette\Application\Attributes\Requires;

final class NewsPresenter extends Nette\Application\UI\Presenter {

    use \App\Core\PresenterTrait;

    public function __construct(
            private \App\Model\Facade\NewsFacade $newsFacade,
            private \App\Forms\NewsFormFactory $newsFormFactory,
    ) {
        
    }

    public function renderDefault(): void {
        $news = $this->newsFacade->getAll();
        $this->template->news = $news;
    }

    public function renderEdit($id = null): void {

        if ($id) {
            $news = $this->newsFacade->getOne(['id' => $id]);

            if (!$news) {
                $this->error(404);
            }

            $this['newsForm']->setDefaults($news);
        }

        $this->template->newsData = $news ?? null;
    }

    protected function createComponentNewsForm(): NewsFormControl {
        return $this->newsFormFactory->create();
    }

    #[Requires(methods: 'POST', sameOrigin: true)]
    public function actionDelete(int $id): void {

        $this->newsFacade->getOne(['id' => $id])->delete();
        $this->redirect('News:default');
    }
    
    public function getImage(){
        
    }
}
