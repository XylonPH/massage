<?php

namespace App\Support\Review;

use Illuminate\Support\Str;

class ReviewContent
{
    /** @return array{body: string, word_count: int, character_count: int, visual_seconds: int, spoken_seconds: int, short_description: string} */
    public function prepare(string $body): array
    {
        $body = trim((string) preg_replace("/\r\n?|\n/u", "\n", $body));
        $plain = trim((string) preg_replace('/\s+/u', ' ', $body));
        $wordCount = preg_match_all("/[\pL\pN]+(?:['’-][\pL\pN]+)*/u", $plain, $matches);
        $wordCount = $wordCount === false ? 0 : $wordCount;
        $characterCount = mb_strlen($plain);

        return [
            'body' => $body,
            'word_count' => $wordCount,
            'character_count' => $characterCount,
            'visual_seconds' => max(1, (int) ceil(($wordCount / 225) * 60)),
            'spoken_seconds' => max(1, (int) ceil(($wordCount / 150) * 60)),
            'short_description' => Str::limit($plain, 255, ''),
        ];
    }
}
