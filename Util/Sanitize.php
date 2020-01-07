<?php

namespace Kernel\Util\Sanitize;

/**
 * Serialize to only return alphanumerical values
 * @param string $string
 * @return string
 */
function alphanum(string $string): string
{
    return preg_replace("/[^a-zA-Z0-9]+/", "", $string);
}

/**
 * Serialize to remove quotation and reform entities
 * @param string $string
 * @return string
 */
function quotes(string $string): string
{
    return htmlspecialchars($string, ENT_QUOTES);
}
