<?php

namespace App\Enums;

trait EnumToArray
{

	public static function names(): array
	{
		return array_column(self::cases(), 'name');
	}

	public static function values(): array
	{
		return array_column(self::cases(), 'value');
	}

	public static function array(): array
	{
		return array_combine(self::values(), self::names());
	}

	public static function fromName(string $name): string | int
	{
		foreach (self::cases() as $case) {
			if ($name === $case->name) {
				return $case->value;
			}
		}
		throw new \ValueError("$name is not a valid backing value for enum " . self::class);
	}
}
