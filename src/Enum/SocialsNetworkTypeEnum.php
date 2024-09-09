<?php

namespace App\Enum;

enum SocialsNetworkTypeEnum: string
{
    case TWITTER = 'twitter';
    case FACEBOOK = 'facebook';
    case INSTAGRAM = 'instagram';
    case TIKTOK = 'tiktok';
    case YOUTUBE = 'youtube';
    case TWITCH = 'twitch';
    case DISCORD = 'discord';

    public static function choices(): array
    {
        return array_column(self::cases(), 'value', 'name');
    }
}

