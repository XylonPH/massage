<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Hash Driver
    |--------------------------------------------------------------------------
    |
    | Massage Nexus uses Argon2id per the accepted password policy in
    | docs/07-accounts/account-and-authentication-system.txt
    | section 5.4. Passwords are never stored in plaintext or reversibly
    | encrypted; this is the one-way hashing driver used to verify them.
    |
    */

    'driver' => env('HASH_DRIVER', 'argon2id'),

    'bcrypt' => [
        'rounds' => env('BCRYPT_ROUNDS', 12),
        'verify' => true,
        'limit' => null,
    ],

    'argon' => [
        'memory' => env('ARGON_MEMORY', 65536),
        'threads' => env('ARGON_THREADS', 1),
        'time' => env('ARGON_TIME', 4),
        'verify' => true,
    ],

    'rehash_on_login' => true,

];
