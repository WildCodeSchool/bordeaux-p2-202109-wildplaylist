<?php

namespace App\Service;

class SongTitleCollector
{
    public function songTitleCollector(string $videoId): string
    {
        $json = file_get_contents("https://noembed.com/embed?url=https://www.youtube.com/watch?v=" . $videoId);
        $data = json_decode($json);
        return $data->title;
    }
}
