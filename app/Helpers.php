<?php

if(!function_exists("moneyFormat"))
{
    function moneyFormat($value, ?string $prefix = null) : string
    {
        if(!$prefix)
        {
            $prefix = config("app.money_prefix");
        }

        return $prefix . number_format((float)$value, 2, ".", ",");
    }
}
