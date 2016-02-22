<?php namespace Harmony\Crashes;

use ArrayIterator;
use IteratorAggregate;

class CallStack implements IteratorAggregate
{
	protected $data;

	protected $applicationPath;

	public function __construct(array $callStack, $exceptionFileName = null, $exceptionLineNumber = null, $applicationPath = null)
	{
		$this->applicationPath = $applicationPath;

		array_shift($callStack);

		if ($exceptionFileName) {
			$callStack[0]['file'] = $exceptionFileName;
		}

		if ($exceptionLineNumber) {
			$callStack[0]['line'] = $exceptionLineNumber;
		}

		$this->data = $this->parse($callStack);
	}

	protected function parse($callStack)
	{
		$data = [];

		foreach ($callStack as $index => $item) {
			$data[] = [
				'number'           => $index,
				'call'             => $this->parseCall($item),
				'shortClass'       => $item['short_class'] ?? null,
				'class'            => $item['class'] ?? null,
				'type'             => $item['type'] ?? null,
				'function'         => $item['function'] ?? null,
				'shortFileName'    => $this->parseShortFileName($item),
				'fileName'         => $item['file'] ?? null,
				'lineNumber'       => $item['line'] ?? null,
				'filePreview'      => $item['file_preview'] ?? null,
				'filePreviewRange' => $this->parseFilePreviewRange($item),
				'args'             => $item['args'] ?? [],
				'isVendor'         => isset($item['file']) ? $this->isVendorPath($item['file']) : true
			];
		}

		return $data;
	}

	protected function parseCall($item)
	{
		if (isset($item['class'], $item['type'], $item['function'])) {
			return $item['short_class'] . $item['type'] . $item['function'];
		} elseif (isset($item['function'])) {
			return $item['function'];
		}
	}

	protected function parseShortFileName($item)
	{
		$fileName = $item['file'] ?? '';

		return $this->applicationPath ? str_replace("{$this->applicationPath}/", '', $fileName) : $fileName;
	}

	protected function parseFilePreviewRange($item)
	{
		if (! isset($item['file_preview'])) {
			return [ 'start' => null, 'end' => null ];
		}

		$firstLine = array_keys($item['file_preview'])[0];

		return [ 'start' => $firstLine, 'end' => $firstLine + count($item['file_preview']) - 1 ];
	}

	protected function isVendorPath($path)
	{
		return strpos($path, "{$this->applicationPath}/vendor") === 0;
	}

	public function getIterator()
	{
		return new ArrayIterator($this->data);
	}
}
