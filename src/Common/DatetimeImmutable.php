<?php

declare(strict_types=1);

namespace App\Common;

use DateTime;
use DateTimeZone;

class DatetimeImmutable extends \DateTimeImmutable
{
    public const FORMAT = 'Y-m-d H:i:s';
    private const TIMEZONE = 'Europe/Paris'; // @TODO: could be UTC...

    public static function create(string $time): \DateTimeImmutable
    {
        return parent::createFromFormat(
            self::FORMAT,
            parent::createFromMutable(new DateTime($time))->format(self::FORMAT),
            new DateTimeZone(self::TIMEZONE)
        );
    }
}
