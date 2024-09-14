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

    public static function getChoices(): array
    {
        return array_map(fn($case) => $case->name, self::cases());
    }
}
