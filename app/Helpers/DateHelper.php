<?php


namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    public static function formatStartDate($date)
    {
        $startDate = Carbon::parse($date)->startOfDay();

        return $startDate;
    }

    public static function formatEndDate($date)
    {
        $endDate = Carbon::parse($date)->addDay()->endOfDay();

        return $endDate;
    }

    public static function formatDatesForReport($startDate, $endDate)
    {
        $startDate = self::formatStartDate($startDate);
        $endDate = self::formatEndDate($endDate);

        return [$startDate, $endDate];
    }
}
