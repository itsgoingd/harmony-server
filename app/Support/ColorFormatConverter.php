<?php namespace Harmony\Support;

class ColorFormatConverter
{
	public static function hslToRgb($h, $s, $l)
	{
		if ($s == 0) {
			return [ $l, $l, $l ];
		}

		$q = $l < 0.5 ? $l * (1 + $s) : $l + $s - $l * $s;
		$p = 2 * $l - $q;

		return [
			round(255 * self::hueToRgb($p, $q, $h + 1/3)),
			round(255 * self::hueToRgb($p, $q, $h)),
			round(255 * self::hueToRgb($p, $q, $h - 1/3))
		];
	}

	protected static function hueToRgb($p, $q, $t)
	{
		if ($t < 0) $t += 1;
		if ($t > 1) $t -= 1;

		if ($t < 1/6) return $p + ($q - $p) * 6 * $t;
		if ($t < 1/2) return $q;
		if ($t < 2/3) return $p + ($q - $p) * (2/3 - $t) * 6;

		return $p;
	}

	public static function rgbToHex($r, $g, $b)
	{
		return self::decimalToHex($r) . self::decimalToHex($g) . self::decimalToHex($b);
	}

	protected static function decimalToHex($decimal, $length = 2)
	{
		$hex = dechex($decimal);

		if (strlen($hex) < $length) {
			$hex = str_repeat('0', $length - strlen($hex)) . $hex;
		}

		return $hex;
	}
}
