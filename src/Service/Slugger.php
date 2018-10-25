<?php

namespace App\Service;

class Slugger
{
    public function slugify(string $text): string
    {
        $slug = trim(strip_tags($text));
        $slug = filter_var($slug, FILTER_SANITIZE_STRING);
        $slug = preg_replace('/([^a-zA-Z0-9]|-)+/', '-', $slug);
        $slug = strtolower($slug);
        return $slug;
    }
}