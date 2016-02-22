<?php namespace Harmony\Http\Controllers\Web\Apps;

use Harmony\Applications\ApplicationsRepository;
use Harmony\Http\Controllers\Controller as BaseController;

class Controller extends BaseController
{
	protected function getApp(ApplicationsRepository $apps, $slug, $mustBeOwner = false)
	{
		$app = $apps->findBySlug($slug);

		if (! $app) {
			abort(404);
		}

		if ($mustBeOwner && $app->ownedBy !== $this->signedIn->id) {
			abort(403);
		} elseif (! $app->users->contains($this->signedIn)) {
			abort(403);
		}

		return $app;
	}

	protected function getOwnedApp(ApplicationsRepository $apps, $slug)
	{
		logger('owner');
		return $this->getApp($apps, $slug, true);
	}

	protected function getCrash(ApplicationsRepository $apps, $appSlug, $crashId)
	{
		$app = $this->getApp($apps, $appSlug);

		$crash = $app->crashes()->find($crashId);

		if (! $crash) {
			abort(404);
		}

		return $crash;
	}
}
