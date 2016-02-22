<?php namespace Harmony\Crashes\Commands;

use Harmony\Applications\Application;
use Harmony\Applications\ApplicationsRepository;
use Harmony\Crashes\NotificationsRepository;
use Harmony\Users\User;

use Illuminate\Contracts\Mail\Mailer;

class SendSummaryEmail
{
	protected $type;

	protected $notifications;
	protected $mailer;

	public function __construct($type)
	{
		$this->type = $type;
	}

	public function handle(ApplicationsRepository $apps, NotificationsRepository $notifications, Mailer $mailer)
	{
		$this->notifications = $notifications;
		$this->mailer        = $mailer;

		foreach ($apps->all() as $app) {
			foreach ($app->users as $user) {
				if ($user->pivot->emailNotifications != $this->type) {
					continue;
				}

				$this->sendSummaryEmail($app, $user);
			}
		}
	}

	protected function sendSummaryEmail(Application $app, User $user)
	{
		$crashes = $app->crashes->filter(function($crash) use($user)
		{
			$notification = $this->notifications->findOrCreateForUser($crash, $user);

			if ($notification->lastSentAt) {
				if ($notification->lastSentAt->gte($crash->getLastInstance()->created_at)) {
					return false;
				}

				if ($this->type == 'hourly' && $notification->lastSentAt->diffInHours() < 1) {
					return false;
				} elseif ($this->type == 'daily' && $notification->lastSentAt->diffInDays() < 1) {
					return false;
				}
			}

			$notification->lastSentAt = time();
			$notification->save();

			return true;
		});

		if ($crashes->isEmpty()) {
			return;
		}

		$this->mailer->send('emails.crashes-summary', [ 'app' => $app, 'crashes' => $crashes ], function($mail) use($user, $app)
		{
			$mail->to($user->email)->subject('Harmony - Crashes summary for "' . $app->name . '"');
		});
	}
}
