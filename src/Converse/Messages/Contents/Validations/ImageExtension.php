<?php

declare(strict_types=1);

namespace Descom\AwsBedrock\Converse\Messages\Contents\Validations;

enum ImageExtension: string
{
    case JPEG = 'jpeg';
    case PNG = 'png';
    case GIF = 'gif';
    case BMP = 'bmp';
    case WEBP = 'webp';

    public static function valid(string $format): bool
    {
        return in_array($format, array_map(fn ($case) => $case->value, self::cases()), true);
    }
}
