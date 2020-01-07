<?php

namespace GreenBeans\Util;

class Validator
{
    /**
     * @param string $method
     * @return bool
     */
    public static function isHttpMethod(string $method): bool
    {
        return
            $method === "GET" ||
            $method === "POST" ||
            $method === "DELETE" ||
            $method == "UPDATE";
    }

}