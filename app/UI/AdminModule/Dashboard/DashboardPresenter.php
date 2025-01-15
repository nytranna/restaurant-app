<?php
namespace App\UI\Admin\Dashboard;

use App\Model\PostFacade;
use Nette;

final class DashboardPresenter extends Nette\Application\UI\Presenter
{
	/*
	public function __construct(
		private PostFacade $facade,
	) {
	}

	public function renderDefault(): void
	{
		$this->template->posts = $this->facade
			->getPublicArticles()
			->limit(5);
	}
	*/
}
