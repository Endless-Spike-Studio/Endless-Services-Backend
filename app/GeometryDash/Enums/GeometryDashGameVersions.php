<?php

namespace App\GeometryDash\Enums;

enum GeometryDashGameVersions: int
{
	case _21 = 21;
	case _22 = 22;

	public const GeometryDashGameVersions LATEST = GeometryDashGameVersions::_22;
}