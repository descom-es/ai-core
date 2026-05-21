<?php

declare(strict_types=1);

namespace Descom\Ai\Bedrock\Messages\Contents\Validations;

enum DocumentExtension: string
{
    case PDF = 'pdf';
    case CSV = 'csv';
    case DOC = 'doc';
    case DOCX = 'docx';
    case XLS = 'xls';
    case XLSX = 'xlsx';
    case HTML = 'html';
    case TXT = 'txt';
    case MARKDOWN = 'md';

    public static function valid(string $format): bool
    {
        return in_array($format, array_map(fn ($case) => $case->value, self::cases()), true);
    }
}
