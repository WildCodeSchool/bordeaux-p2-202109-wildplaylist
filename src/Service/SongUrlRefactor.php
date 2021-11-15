<?php

namespace App\Service;

class SongUrlRefactor
{
    public function songUrlRefactor(string $songUrl): string
    {
        if (str_starts_with($songUrl, 'https://youtu.be/') === true) {
            $songUrl = substr($songUrl, 0, strpos($songUrl, '&ab_channel='));
            $songUrl = str_split($songUrl, 17);
            $songUrl = array_splice($songUrl, -1);
            $songUrl = implode($songUrl);
        } elseif (str_starts_with($songUrl, 'https://www.youtube.com/watch?v=') === true) {
            $songUrl = substr($songUrl, 0, strpos($songUrl, '&ab_channel='));
            $songUrl = str_split($songUrl, 32);
            $songUrl = array_splice($songUrl, -1);
            $songUrl = implode($songUrl);
        } elseif (str_starts_with($songUrl, 'https://www.youtube.com/embed/') === true) {
            $songUrl = substr($songUrl, 0, strpos($songUrl, '&ab_channel='));
            $songUrl = str_split($songUrl, 30);
            $songUrl = array_splice($songUrl, -1);
            $songUrl = implode($songUrl);
        }
        return $songUrl;
    }
}
