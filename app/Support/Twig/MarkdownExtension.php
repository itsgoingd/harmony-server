<?php namespace Harmony\Support\Twig;

use Parsedown;
use Twig_Extension;
use Twig_SimpleFilter;

class MarkdownExtension extends Twig_Extension
{
	public function getFilters()
	{
		return [
			new Twig_SimpleFilter('markdown', [ $this, 'parseMarkdown' ], [ 'is_safe' => [ 'html' ] ])
		];
	}

	public function getName()
	{
		return 'markdown';
	}

	public function parseMarkdown($content)
	{
		return (new Parsedown())->text($content);
	}
}
