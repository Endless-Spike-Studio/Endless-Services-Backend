<?php

namespace App\GeometryDash\Enums;

enum GeometryDashGameVersions: int
{
	case __21 = 21;
	case __22 = 22;

	public const GeometryDashGameVersions LATEST = GeometryDashGameVersions::__22;
}