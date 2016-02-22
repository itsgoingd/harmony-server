<?php namespace Harmony\Crashes;

use Illuminate\Database\Eloquent\Model;

class Instance extends Model
{
	protected $table = 'crashes_instances';

	protected $fillable = [
		'callStack', 'requestData', 'requestHeaders', 'queryLog'
	];

	/**
	 * Relations
	 */

	public function crash()
	{
		return $this->belongsTo(\Harmony\Crashes\Crash::class, 'crashId');
	}

	/**
	 * Methods
	 */

	public function getCallStack()
	{
		$callStackData = json_decode($this->callStack, true);

		return new CallStack(
			$callStackData ?: [],
			$this->crash->fileName,
			$this->crash->lineNumber,
			$this->crash->application->path
		);
	}

	public function getRequestData()
	{
		return json_decode($this->requestData, true);
	}

	public function getRequestHeaders()
	{
		return json_decode($this->requestHeaders, true);
	}

	public function getQueryLog()
	{
		$queryLogData = json_decode($this->queryLog, true);

		return new QueryLog($queryLogData ?: []);
	}
}
