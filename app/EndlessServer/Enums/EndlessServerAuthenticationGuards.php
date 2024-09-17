<?php

namespace App\EndlessServer\Enums;

enum EndlessServerAuthenticationGuards: string
{
	case ACCOUNT = 'endless_server.account';
	case PLAYER = 'endless_server.player';
}