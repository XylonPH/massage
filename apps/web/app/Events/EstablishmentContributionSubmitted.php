<?php

namespace App\Events;

use App\Models\Contribution;
use Illuminate\Foundation\Events\Dispatchable;

class EstablishmentContributionSubmitted
{
    use Dispatchable;

    public function __construct(public readonly Contribution $contribution) {}
}
