<?php

namespace App\Http\Controllers;

use App\Enums\CoverFormat;
use App\Models\Event;
use Illuminate\View\View;

class EventCoverController extends Controller
{
    /**
     * Render an event cover at its native size, ready to be exported as PNG by the admin's browser.
     */
    public function __invoke(Event $event, CoverFormat $format): View
    {
        $event->loadMissing('partners');

        [$venue, $town] = $this->splitLocation($event->location);

        return view('covers.event', [
            'event' => $event,
            'format' => $format,
            'title' => $event->cover_title ?: $event->title,
            'venue' => $venue,
            'town' => $town,
            'partnerLogos' => $event->partners->map->cover_logo_url->filter()->values(),
        ]);
    }

    /**
     * Split "Venue, Town" or "Venue - Town" so the venue can be emphasised on the cover.
     *
     * @return array{?string, ?string}
     */
    private function splitLocation(?string $location): array
    {
        if (blank($location)) {
            return [null, null];
        }

        $parts = preg_split('/\s*(?:,|\s[-–]\s)\s*/u', trim($location));

        if (count($parts) < 2) {
            return [trim($location), null];
        }

        $town = array_pop($parts);

        return [implode(', ', $parts), $town];
    }
}
