<?php

return [

    /*
    |--------------------------------------------------------------------------
    | BCP 47 Locale Mapping
    |--------------------------------------------------------------------------
    |
    | Laravel's internal locale (APP_LOCALE, and the matching lang/ folder
    | name) is just a string key used to pick translation files — it does
    | not have to be a real language code. The HTML <html lang="..."> tag,
    | hreflang links, and the Content-Language header do have a real
    | technical requirement: a valid BCP 47 / ISO 639-1 tag. Map from the
    | internal locale to the correct public tag here so the two can differ
    | without breaking accessibility, SEO, or standards compliance.
    |
    | Unmapped locales fall back to the internal locale value as-is.
    |
    */

    'bcp47' => [
        'eng' => 'en',
    ],

];
