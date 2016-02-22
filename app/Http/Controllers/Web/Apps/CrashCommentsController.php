<?php namespace Harmony\Http\Controllers\Web\Apps;

use Harmony\Applications\ApplicationsRepository;
use Harmony\Crashes\Comments\Commands\PostComment;

use Illuminate\Http\Request;

class CrashCommentsController extends Controller
{
	public function store(ApplicationsRepository $apps, Request $request, $appSlug, $crashId)
	{
		$crash = $this->getCrash($apps, $appSlug, $crashId);

		$this->dispatch(new PostComment($request->input('message'), $crash, $this->signedIn));

		return redirect(url()->previous() . '#lastComment');
	}
}
