<?php

namespace App\GeometryDash\Enums;

enum GeometryDashBinaryVersions: int
{
	case _35 = 35;
	case _42 = 42;

	public const GeometryDashBinaryVersions LATEST = GeometryDashBinaryVersions::_42;
}