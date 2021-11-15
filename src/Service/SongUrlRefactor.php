<?php

namespace App\Service;

class SongUrlRefactor
{
    public function songUrlRefactor(string $songUrl): string
    {
        $parameters = [''];
        if (str_starts_with($songUrl, 'https://youtu.be/')) {
            $songUrl = str_split($songUrl, 17);
            $songUrl = array_splice($songUrl, -1);
            $songUrl = implode($songUrl);
        } elseif (str_starts_with($songUrl, 'https://www.youtube.com/embed/')) {
            $songUrl = str_split($songUrl, 30);
            $songUrl = array_splice($songUrl, -1);
            $songUrl = implode($songUrl);
        } elseif (str_starts_with($songUrl, 'https://www.youtube.com/watch?v=')) {
            $queryString = parse_url($songUrl)['query'];
            parse_str($queryString, $parameters);
            $songUrl = $parameters['v'];
        }
        return $songUrl;
    }
}
