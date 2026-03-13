<?php

use Carbon\Carbon;

if (! function_exists('dataFormatada')) {
    function dataFormatada($date)
    {
        return Carbon::parse($date)
            ->translatedFormat('j \d\e F \d\e Y');
    }
}
