<?php namespace Harmony\Http\Controllers\Api;

use Harmony\Crashes\Commands\ReportCrash;
use Harmony\Crashes\Exceptions\InvalidApiKey;
use Harmony\Http\Controllers\Controller;

use Illuminate\Http\Request;

class CrashesController extends Controller
{
	public function store(Request $request)
	{
		try {
			$instance = $this->dispatch(new ReportCrash($request->json('apiKey'), $request->json('data')));
		} catch (InvalidApiKey $e) {
			return response()->json([ 'error' => 'Invalid api key.' ], 400);
		}

		return response()->json([
			'crashId'    => $instance->crash->id,
			'instanceId' => $instance->id,
			'hash'       => $instance->crash->hash,
			'url'        => route('crashes details', [ 'app' => $instance->crash->application->slug, 'crash' => $instance->crash->id ])
		]);
	}
}
