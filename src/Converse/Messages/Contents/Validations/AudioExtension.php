<?php

declare(strict_types=1);

namespace Descom\AwsBedrock\Converse\Messages\Contents\Validations;

enum AudioExtension: string
{
    case MP3 = 'mp3';
    case OPUS = 'opus';
    case WAV = 'wav';
    case AAC = 'aac';
    case FLAC = 'flac';
    case MP4 = 'mp4';
    case OGG = 'ogg';
    case MKV = 'mkv';
    case MKA = 'mka';
    case X_AAC = 'x-aac';
    case M4A = 'm4a';
    case MPEG = 'mpeg';
    case MPGA = 'mpga';
    case PCM = 'pcm';
    case WEBM = 'webm';

    public static function valid(string $format): bool
    {
        return in_array($format, array_map(fn ($case) => $case->value, self::cases()), true);
    }
}
