<?php

namespace App\GeometryDash\Enums\Objects;

enum GeometryDashLevelGauntletObjectDefinition: int
{
	public const string SEGMENTATION = '#';
	public const string GLUE = ':';
	public const string SEPARATOR = '|';

	case ID = 1;
	case LEVELS = 3;
}