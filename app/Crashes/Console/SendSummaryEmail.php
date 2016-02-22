<?php namespace Harmony\Crashes\Console;

use Harmony\Crashes\Commands\SendSummaryEmail as SendSummaryEmailCommand;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class SendSummaryEmail extends Command
{
	use DispatchesJobs;

	protected $signature = 'harmony:send-summary-email {type}';

	protected $description = 'Send crashes summary for specified period.';

	public function handle()
	{
		$this->dispatch(new SendSummaryEmailCommand($this->argument('type')));
	}
}
