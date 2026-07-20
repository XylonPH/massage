<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceProfileController extends Controller
{
    public function show(string $service_slug)
    {
        $service = Service::where('service_slug', $service_slug)
            ->where('status_record_lifecycle', 'ACT')
            ->firstOrFail();

        return view('service.profile', [
            'service' => $service,
        ]);
    }
}
