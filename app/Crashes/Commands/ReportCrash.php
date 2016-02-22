<?php namespace Harmony\Crashes\Commands;

use Harmony\Applications\Application;
use Harmony\Applications\ApplicationsRepository;
use Harmony\Crashes\Crash;
use Harmony\Crashes\CrashesRepository;
use Harmony\Crashes\Instance;
use Harmony\Crashes\NotificationsRepository;
use Harmony\Crashes\Exceptions\InvalidApiKey;

use Illuminate\Contracts\Mail\Mailer;

class ReportCrash
{
	protected $apiKey;
	protected $crashData;

	protected $notifications;
	protected $mailer;

	public function __construct($apiKey, $crashData)
	{
		$this->apiKey = $apiKey;
		$this->crashData = $crashData;
	}

	public function handle(ApplicationsRepository $apps, CrashesRepository $crashes, NotificationsRepository $notifications, Mailer $mailer)
	{
		$this->notifications = $notifications;
		$this->mailer        = $mailer;

		$app = $apps->findByApiKey($this->apiKey);

		if (! $app) {
			throw new InvalidApiKey;
		}

		$crash = $crashes->findByHashNotArchived($this->generateCrashHash($this->crashData));

		if (! $crash) {
			$crash = $this->storeNewCrash($app, $this->crashData);
		}

		$instance = $this->storeNewCrashInstance($crash, $this->crashData);

		if (! $app->path) {
			$this->updateApplicationPath($app, $instance);
		}

		$this->sendEmailNotifications($app, $crash);

		return $instance;
	}

	protected function storeNewCrash(Application $app, $crashData)
	{
		return $app->crashes()->create([
			'exception'  => array_get($crashData, 'exception'),
			'message'    => array_get($crashData, 'message'),
			'fileName'   => array_get($crashData, 'fileName'),
			'lineNumber' => array_get($crashData, 'lineNumber'),
			'hash'       => $this->generateCrashHash($this->crashData)
		]);
	}

	protected function storeNewCrashInstance(Crash $crash, $crashData)
	{
		return $crash->instances()->create([
			'callStack'      => json_encode(array_get($crashData, 'callStack')),
			'requestData'    => json_encode(array_get($crashData, 'requestData')),
			'requestHeaders' => json_encode(array_get($crashData, 'requestHeaders')),
			'queryLog'       => json_encode(array_get($crashData, 'queryLog'))
		]);
	}

	protected function sendEmailNotifications(Application $app, Crash $crash)
	{
		foreach ($app->users as $user) {
			if ($user->pivot->emailNotifications != 'asap') {
				continue;
			}

			$notification = $this->notifications->findOrCreateForUser($crash, $user);

			if ($notification->lastSentAt && $notification->lastSentAt->diffInMinutes() < 5) {
				continue;
			}

			$this->mailer->send('emails.crash-reported', [ 'crash' => $crash ], function($mail) use($user, $crash)
			{
				$mail->to($user->email)->subject('Harmony - New crash reported for "' . $crash->application->name . '"');
			});

			$notification->lastSentAt = time();
			$notification->save();
		}
	}

	protected function generateCrashHash($crashData)
	{
		$exception  = array_get($crashData, 'exception');
		$message    = array_get($crashData, 'message');
		$fileName   = array_get($crashData, 'fileName');
		$lineNumber = array_get($crashData, 'lineNumber');

		return sha1("{$fileName}-{$lineNumber}-{$exception}-{$message}");
	}

	protected function updateApplicationPath(Application $app, Instance $crashInstance)
	{
		$callStack = $crashInstance->getCallStack();

		if ($callStack->getIterator()->count() < 5) {
			return;
		}

		$paths = [];

		foreach ($callStack as $frame) {
			if ($frame['fileName']) {
				$paths[] = explode('/', $frame['fileName']);
			}
		}

		$app->path = $this->findCommonPath($paths);
		$app->save();
	}

	protected function findCommonPath($paths)
	{
		$commonPath = '';

		foreach ($paths[0] as $index => $token) {
			foreach ($paths as $path) {
				if (! isset($path[$index]) || $path[$index] !== $token) {
					break 2;
				}
			}

			$commonPath .= $token . '/';
		}

		$commonPath = rtrim($commonPath, '/');

		if (! $commonPath) {
			return null;
		}

		return $commonPath . '/';
	}
}
