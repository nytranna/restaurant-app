<?php

declare(strict_types=1);

use Latte\Runtime as LR;

/** source: /var/www/restaurant-app/app/UI/@layout.latte */
final class Template_a231757dec extends Latte\Runtime\Template
{
	public const Source = '/var/www/restaurant-app/app/UI/@layout.latte';

	public const Blocks = [
		['scripts' => 'blockScripts'],
	];


	public function main(array $ʟ_args): void
	{
		extract($ʟ_args);
		unset($ʟ_args);

		if ($this->global->snippetDriver?->renderSnippets($this->blocks[self::LayerSnippet], $this->params)) {
			return;
		}

		echo '<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width">
	<title>';
		if ($this->hasBlock('title')) /* line 6 */ {
			$this->renderBlock('title', [], function ($s, $type) {
				$ʟ_fi = new LR\FilterInfo($type);
				return LR\Filters::convertTo($ʟ_fi, 'html', $this->filters->filterContent('stripHtml', $ʟ_fi, $s));
			}) /* line 6 */;
			echo ' | ';
		}
		echo 'Restaurant App</title>
	<link rel="stylesheet" href="';
		echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 7 */;
		echo '/css/style.css">
</head>



<body>

	<ul class="navig">
		<!--<li><a n:href="Home:">Články</a></li>-->
';
		if ($user->isLoggedIn()) /* line 16 */ {
			echo '			<li><a href="';
			echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link('Sign:out')) /* line 17 */;
			echo '">Odhlásit</a></li>
';
		} else /* line 18 */ {
			echo '			<li><a href="';
			echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link('Sign:in')) /* line 19 */;
			echo '">Přihlásit</a></li>
';
		}
		echo '	</ul>

';
		foreach ($flashes as $flash) /* line 23 */ {
			echo '	<div';
			echo ($ʟ_tmp = array_filter(['flash', $flash->type])) ? ' class="' . LR\Filters::escapeHtmlAttr(implode(" ", array_unique($ʟ_tmp))) . '"' : "" /* line 23 */;
			echo '>
		';
			echo LR\Filters::escapeHtmlText($flash->message) /* line 24 */;
			echo '
	</div>
';

		}

		echo "\n";
		$this->renderBlock('content', [], 'html') /* line 27 */;
		echo "\n";
		$this->renderBlock('scripts', get_defined_vars()) /* line 29 */;
		echo '	
</body>
</html>
';
	}


	public function prepare(): array
	{
		extract($this->params);

		if (!$this->getReferringTemplate() || $this->getReferenceType() === 'extends') {
			foreach (array_intersect_key(['flash' => '23'], $this->params) as $ʟ_v => $ʟ_l) {
				trigger_error("Variable \$$ʟ_v overwritten in foreach on line $ʟ_l");
			}
		}
		return get_defined_vars();
	}


	/** {block scripts} on line 29 */
	public function blockScripts(array $ʟ_args): void
	{
		echo '	<script src="https://unpkg.com/nette-forms@3"></script>
';
	}
}
