<?php

namespace App\GeometryDash\Enums\Objects;

enum GeometryDashMapPackObjectDefinition: int
{
	public const string SEGMENTATION = '#';
	public const string GLUE = ':';
	public const string SEPARATOR = '|';

	case ID = 1;
	case NAME = 2;
	case LEVELS = 3;
	case STARS = 4;
	case COINS = 5;
	case DIFFICULTY = 6;
	case TEXT_COLOR = 7;
	case BAR_COLOR = 8;
}