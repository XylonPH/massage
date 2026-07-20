<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Versioned Policy Acceptance
    |--------------------------------------------------------------------------
    |
    | docs/07-accounts/account-and-authentication-system.txt
    | requires a versioned Terms acceptance and Privacy acknowledgment record.
    | The actual Terms of Use and Privacy Notice documents have not been
    | drafted yet (docs/01-project/simple-checklist.txt still lists Terms of
    | Use Requirements and Privacy Notice Requirements as not started), so
    | these versions identify the current DRAFT placeholder text shown at
    | /legal/terms and /legal/privacy. Bump the version here when that
    | placeholder text changes, and again when real legal text replaces it.
    |
    */

    'terms_version' => env('LEGAL_TERMS_VERSION', '0.1-draft'),

    'privacy_version' => env('LEGAL_PRIVACY_VERSION', '0.1-draft'),

];
