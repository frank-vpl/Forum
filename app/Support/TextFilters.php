<?php

namespace App\Support;

class TextFilters
{
    public static function flagify(?string $text): string
    {
        $escaped = e((string) $text);
        $img = '<img src="'.asset('iran.png').'" alt="🇮🇷" style="display:inline;height:1em;width:auto;vertical-align:-0.2em" class="emoji-flag emoji-flag-ir" />';

        return str_replace('🇮🇷', $img, $escaped);
    }
}
