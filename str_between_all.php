<?php

function str_between(string $string, string $start, string $end, bool $includeDelimiters = false, int &$offset = 0): ?string
{
    if ($string === '' || $start === '' || $end === '') return null;

    $startLength = strlen($start);
    $endLength = strlen($end);

    $startPos = strpos($string, $start, $offset);
    if ($startPos === false) return null;

    $endPos = strpos($string, $end, $startPos + $startLength);
    if ($endPos === false) return null;

    $length = $endPos - $startPos + ($includeDelimiters ? $endLength : -$startLength);
    if (!$length) return '';

    $offset = $startPos + ($includeDelimiters ? 0 : $startLength);

    $result = substr($string, $offset, $length);

    return ($result !== false ? $result : null);
}


function str_between_all(string $string, string $start, string $end, bool $includeDelimiters = false, int &$offset = 0): ?array
{
    $strings = [];
    $length = strlen($string);

    while ($offset < $length)
    {
        $found = str_between($string, $start, $end, $includeDelimiters, $offset);
        if ($found === null) break;

        $strings[] = $found;
        $offset += strlen($includeDelimiters ? $found : $start . $found . $end); // move offset to the end of the newfound string
    }

    return $strings;
}

?>
