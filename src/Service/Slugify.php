<?php

// src/Service/MessageGenerator.php

namespace App\Service;

class Slugify
{
    public function generate(string $input, string $delimiter = '-'): string
    {
        $slug = strtolower(
            trim(
            preg_replace('/[\s-]+/', $delimiter,
            preg_replace('/[^A-Za-z0-9-]+/', $delimiter,
            preg_replace('/[&]/', 'and',
            preg_replace('/[\']/', '',
            iconv('UTF-8', 'ASCII//TRANSLIT', $input))))), $delimiter)
        );

        return $slug;
    }
}