<?php

declare(strict_types=1);

use Latte\Runtime as LR;

/** source: /var/www/restaurant-app/app/UI/Home/default.latte */
final class Template_41d04cd0e1 extends Latte\Runtime\Template
{
	public const Source = '/var/www/restaurant-app/app/UI/Home/default.latte';

	public const Blocks = [
		['content' => 'blockContent'],
	];


	public function main(array $ʟ_args): void
	{
		extract($ʟ_args);
		unset($ʟ_args);

		if ($this->global->snippetDriver?->renderSnippets($this->blocks[self::LayerSnippet], $this->params)) {
			return;
		}

		$this->renderBlock('content', get_defined_vars()) /* line 1 */;
	}


	/** {block content} on line 1 */
	public function blockContent(array $ʟ_args): void
	{
		echo '	<h1>Restaurant App</h1>
';
	}
}
