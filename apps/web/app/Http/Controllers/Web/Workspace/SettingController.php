<?php

namespace App\Http\Controllers\Web\Workspace;

use App\Http\Controllers\Controller;
use App\Models\Reference\TimeZone;
use App\Models\UserDevice;
use App\Models\UserSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user();
        $sessions = UserSession::where('user_id', (string) $user->getKey())->orderByDesc('last_activity_at')->get();
        $devices = UserDevice::where('user_id', (string) $user->getKey())->orderByDesc('last_seen_at')->get();
        $timeZones = TimeZone::query()->orderBy('time_zone_name')->get();

        if ($timeZones->isEmpty()) {
            $timeZones = collect([
                (object) ['time_zone_id' => 255, 'time_zone_name' => 'Asia/Manila'],
                (object) ['time_zone_id' => 312, 'time_zone_name' => 'Etc/UTC'],
            ]);
        }

        return view('workspace.setting', compact('user', 'sessions', 'devices', 'timeZones'));
    }

    public function update(Request $request): RedirectResponse
    {
        $timeZoneIds = TimeZone::query()->pluck('time_zone_id')->map(fn ($id): int => (int) $id)->all();
        $timeZoneIds = $timeZoneIds ?: [255, 312];

        $validated = $request->validate([
            'color_mode' => ['sometimes', Rule::in(['SYS', 'LGT', 'DRK'])],
            'text_scale_percent' => ['sometimes', 'integer', Rule::in([90, 100, 110, 125])],
            'digest_frequency' => ['sometimes', Rule::in(['IMM', 'DLY', 'WKL', 'OFF'])],
            'interface_language_id' => ['sometimes', 'integer', Rule::in([3049])],
            'time_zone_id' => ['sometimes', 'integer', Rule::in($timeZoneIds)],
            'notification_channel_list' => ['sometimes', 'array'],
            'notification_channel_list.*' => [Rule::in(['WEB', 'EML'])],
            'notification_category_list' => ['sometimes', 'array'],
            'notification_category_list.*' => [Rule::in(['SEC', 'BKG', 'CON'])],
        ]);

        $appearance = array_replace($request->user()->appearance_preference ?? [], [
            'color_mode' => $validated['color_mode'] ?? ($request->user()->appearance_preference['color_mode'] ?? 'SYS'),
            'text_scale_percent' => $validated['text_scale_percent'] ?? ($request->user()->appearance_preference['text_scale_percent'] ?? 100),
            'is_high_contrast' => $request->boolean('is_high_contrast'),
            'is_reduced_motion' => $request->boolean('is_reduced_motion'),
        ]);
        $notification = array_replace($request->user()->notification_preference ?? [], [
            'notification_channel_list' => array_values($validated['notification_channel_list'] ?? []),
            'notification_category_list' => array_values($validated['notification_category_list'] ?? []),
            'digest_frequency' => $validated['digest_frequency'] ?? ($request->user()->notification_preference['digest_frequency'] ?? 'IMM'),
            'is_marketing_email_opt_in' => $request->boolean('marketing_opt_in'),
        ]);
        $account = array_replace($request->user()->account_preference ?? [], [
            'interface_language_id' => $validated['interface_language_id'] ?? ($request->user()->account_preference['interface_language_id'] ?? 3049),
            'time_zone_id' => $validated['time_zone_id'] ?? ($request->user()->account_preference['time_zone_id'] ?? 255),
        ]);

        // Marketing consent stays a separate, revocable decision; the versioned
        // grant/withdrawal history belongs to user_policy once that exists.
        $request->user()->forceFill([
            'account_preference' => $account,
            'appearance_preference' => $appearance,
            'notification_preference' => $notification,
            // Retained temporarily for compatibility with older runtime reads.
            'is_marketing_email_opt_in' => $notification['is_marketing_email_opt_in'],
        ])->save();

        return redirect()
            ->route('workspace.setting.edit')
            ->with('status', __('workspace.settings_saved'));
    }
}
