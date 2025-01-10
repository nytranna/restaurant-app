<?php
namespace App\Model;

use Nette;

final class PostFacade
{
	public function __construct(
		private Nette\Database\Explorer $database,
	) {
	}

	public function getPublicArticles()
	{

		\Tracy\Debugger::barDump(new \DateTime);
		return $this->database
			->table('posts')
			->where('created_at < ', new \DateTime)
			->order('created_at DESC');
	}
}