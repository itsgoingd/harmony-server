<?php namespace Harmony\Crashes;

use Harmony\Applications\Application;
use Harmony\Support\Repository;

class CrashesRepository extends Repository
{
	protected $model = Crash::class;

	public function findByHashNotArchived($hash)
	{
		return $this->where('hash', $hash)->whereNull('archivedAt')->first();
	}

	public function getFilteredForApplication(Application $app, array $filter = [])
	{
		$crashes = $app->crashes();

		if ($searchQuery = array_get($filter, 'search')) {
			$searchQuery = '%' . str_replace([ '%', '?', '_' ], [ '\%', '\?', '\_' ], $searchQuery) . '%';

			$crashes->where(function($query) use($searchQuery)
			{
				$query->where('exception', 'LIKE', $searchQuery)
					->orWhere('message', 'LIKE', $searchQuery)
					->orWhere('fileName', 'LIKE', $searchQuery);
			});
		}

		if (array_get($filter, 'order') == 'oldest') {
			$crashes->oldest();
		} elseif (array_get($filter, 'order') == 'most-frequent') {
			$crashes->select(app('db')->raw('crashes.*, (select count(*) from crashes_instances where crashes_instances.crashId = crashes.id ) as instancesCount'))
				->orderBy('instancesCount', 'desc');
		} else {
			$crashes->latest();
		}

		if (! array_get($filter, 'showArchived', false)) {
			$crashes->whereNull('archivedAt');
		}

		return $crashes->get();
	}
}
