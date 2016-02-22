<?php namespace Harmony\Crashes;

use ArrayIterator;
use IteratorAggregate;

class QueryLog implements IteratorAggregate
{
	protected $data;

	public function __construct(array $queryLog)
	{
		$this->data = $queryLog;

		foreach ($this->data as &$item) {
			$item['runnableQuery']  = $item['runnableQuery'] ?? $item['query'];
			$item['decoratedQuery'] = $this->decorateQuery($item['runnableQuery']);
		}
	}

	protected function decorateQuery($query)
	{
		$query = htmlspecialchars($query);

		$keywords = [ 'select', 'insert', 'update', 'delete', 'where', 'from', 'limit', 'is', 'null', 'having', 'group by', 'order by', 'asc', 'desc' ];
		$regexp = '/\b' . implode('\b|\b', $keywords) . '\b/i';

		$query = preg_replace_callback($regexp, function($match)
		{
			return "<span class=\"keyword\">{$match[0]}</span>";
		}, $query);

		return $query;
	}

	public function getIterator()
	{
		return new ArrayIterator($this->data);
	}
}
