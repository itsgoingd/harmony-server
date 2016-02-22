<?php namespace Harmony\Applications\Commands;

use Harmony\Applications\ApplicationsRepository;
use Harmony\Support\ColorFormatConverter;
use Harmony\Users\User;

class CreateApplication
{
	protected $name;
	protected $logo;
	protected $user;

	protected $apps;

	public function __construct($name, $logo, User $user)
	{
		$this->name = $name;
		$this->logo = $logo;
		$this->user = $user;
	}

	public function handle(ApplicationsRepository $apps)
	{
		$this->apps = $apps;

		$app = $apps->store([
			'name'    => $this->name,
			'logo'    => $this->logo,
			'apiKey'  => $this->generateApiKey(),
			'color'   => $this->generateColor(),
			'ownedBy' => $this->user->id
		]);

		$app->users()->attach($this->user);

		return $app;
	}

	protected function generateApiKey()
	{
		do {
			$apiKey = str_random(32);
		} while ($this->apps->findByApiKey($apiKey));

		return $apiKey;
	}

	protected function generateColor()
	{
		$h = rand(0, 359);
		$s = rand(50, 100);
		$l = rand(40, 50);

		list($r, $g, $b) = ColorFormatConverter::hslToRgb($h, $s, $l);

		return ColorFormatConverter::rgbToHex($r, $g, $b);
	}
}
