<?php namespace Harmony\Support\Twig;

use Twig_Extension;
use Twig_SimpleFunction;

class AssetsExtension extends Twig_Extension
{
	protected $assetsManifest = null;

	public function getFunctions()
	{
		return [
			new Twig_SimpleFunction('stylesheet', [ $this, 'stylesheet' ], [ 'is_safe' => [ 'html' ] ]),
			new Twig_SimpleFunction('javascript', [ $this, 'javascript' ], [ 'is_safe' => [ 'html' ] ])
		];
	}

	public function getName()
	{
		return 'assets';
	}

	public function stylesheet($name)
	{
		$url = $this->getVersionedAssetUrl($name);

		return '<link rel="stylesheet" href="' . $url . '">';
	}

	public function javascript($name)
	{
		$url = $this->getVersionedAssetUrl($name);

		return '<script src="' . $url . '"></script>';
	}

	protected function getVersionedAssetUrl($name)
	{
		if (! $this->assetsManifest) {
			$this->assetsManifest = json_decode(file_get_contents(public_path('assets/rev-manifest.json')), true) ?? [];
		}

		return asset('assets/' . array_get($this->assetsManifest, $name));
	}
}
