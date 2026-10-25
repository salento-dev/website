<?php

namespace Tests\Feature;

use App\Enums\CoverFormat;
use App\Filament\Resources\Events\Pages\EditEvent;
use App\Filament\Resources\Events\Pages\EventCovers;
use App\Models\Event;
use App\Models\Partner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;
use Tests\TestCase;

class EventCoversTest extends TestCase
{
    use RefreshDatabase;

    public function test_cover_route_rejects_unsigned_requests(): void
    {
        $event = $this->communityEvent();

        $this->get(route('events.cover', ['event' => $event, 'format' => CoverFormat::Website]))
            ->assertForbidden();
    }

    public function test_cover_shows_the_event_details(): void
    {
        $event = $this->communityEvent();

        $this->get($this->signedCoverUrl($event, CoverFormat::Website))
            ->assertOk()
            ->assertSee('salento.dev')
            ->assertSee('#6 Meetup')
            ->assertSee('Lightning talks')
            ->assertSee('29.10.26')
            ->assertSeeInOrder(['h.19.00', 'Gradita conferma'])
            ->assertSeeInOrder(['Caffetteria Normal', 'Taurisano'])
            ->assertSee('noindex, nofollow', false);
    }

    public function test_cover_falls_back_to_the_event_title_and_location(): void
    {
        $event = Event::factory()->create([
            'title' => 'AperiDev',
            'location' => 'Showbiz',
            'starts_at' => '2026-04-29 18:00:00',
        ]);

        $this->get($this->signedCoverUrl($event, CoverFormat::Post))
            ->assertOk()
            ->assertSee('AperiDev')
            ->assertSee('Showbiz')
            ->assertSee('h.20.00')
            ->assertDontSee('h.20.00 -');
    }

    public function test_vertical_covers_show_the_edition_on_its_own_line(): void
    {
        $event = $this->communityEvent();

        $this->get($this->signedCoverUrl($event, CoverFormat::Story))
            ->assertOk()
            ->assertSeeInOrder(['>Salento</p>', '>Dev</p>', '>Meetup</p>', '>#6</p>'], false);
    }

    public function test_cover_background_is_the_logo_pictogram_with_the_brand_gradient(): void
    {
        $event = $this->communityEvent();

        $this->get($this->signedCoverUrl($event, CoverFormat::Post))
            ->assertOk()
            ->assertSee('id="cover-gradient"', false)
            ->assertSee('fill: url(#cover-gradient)', false)
            ->assertSee('stop-color="#6a14d1"', false)
            ->assertSee('stop-color="#c82090"', false);
    }

    public function test_vertical_covers_show_only_partners_with_a_cover_logo(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('partners/logos/normal-white.svg', '<svg></svg>');
        Storage::disk('public')->put('partners/logos/opaque.png', 'png');

        $event = $this->communityEvent();
        $withCoverLogo = Partner::factory()->create(['cover_logo_path' => 'partners/logos/normal-white.svg']);
        $withoutCoverLogo = Partner::factory()->create(['logo_path' => 'partners/logos/opaque.png']);
        $event->partners()->attach([$withCoverLogo->id, $withoutCoverLogo->id]);

        $this->get($this->signedCoverUrl($event, CoverFormat::Post))
            ->assertOk()
            ->assertSee($withCoverLogo->cover_logo_url, false)
            ->assertDontSee($withoutCoverLogo->logo_url, false);
    }

    public function test_subtitle_line_breaks_are_kept_and_escaped(): void
    {
        $event = $this->communityEvent(['subtitle' => "Lightning talks\n<b>Night</b>"]);

        $this->get($this->signedCoverUrl($event, CoverFormat::Post))
            ->assertOk()
            ->assertSee('Lightning talks<br />', false)
            ->assertSee('&lt;b&gt;Night&lt;/b&gt;', false);
    }

    public function test_cover_route_rejects_unknown_formats(): void
    {
        $event = $this->communityEvent();

        $this->get(URL::signedRoute('events.cover', ['event' => $event, 'format' => 'banner']))
            ->assertNotFound();
    }

    public function test_covers_page_requires_authentication(): void
    {
        $event = $this->communityEvent();

        $this->get(EventCovers::getUrl(['record' => $event]))
            ->assertRedirect();
    }

    public function test_covers_page_previews_every_format(): void
    {
        $event = $this->communityEvent();

        $this->actingAs(User::factory()->create())
            ->get(EventCovers::getUrl(['record' => $event]))
            ->assertOk()
            ->assertSee(CoverFormat::Website->label())
            ->assertSee(CoverFormat::Post->label())
            ->assertSee(CoverFormat::Story->label())
            ->assertSee('Usa come immagine del sito');
    }

    public function test_website_cover_becomes_the_event_image_and_replaces_the_previous_generated_one(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('events/covers/old.png', 'old');

        $event = $this->communityEvent(['image_path' => 'events/covers/old.png']);
        $this->actingAs(User::factory()->create());

        Livewire::test(EventCovers::class, ['record' => $event->getRouteKey()])
            ->set('websiteCover', UploadedFile::fake()->image('cover.png', 1920, 1080))
            ->call('saveWebsiteCover')
            ->assertHasNoErrors()
            ->assertNotified('Immagine del sito aggiornata');

        $event->refresh();

        $this->assertStringStartsWith('events/covers/'.$event->slug.'-', $event->image_path);
        Storage::disk('public')->assertExists($event->image_path);
        Storage::disk('public')->assertMissing('events/covers/old.png');
    }

    public function test_website_cover_keeps_a_manually_uploaded_image_on_disk(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('events/manual.jpg', 'manual');

        $event = $this->communityEvent(['image_path' => 'events/manual.jpg']);
        $this->actingAs(User::factory()->create());

        Livewire::test(EventCovers::class, ['record' => $event->getRouteKey()])
            ->set('websiteCover', UploadedFile::fake()->image('cover.png', 1920, 1080))
            ->call('saveWebsiteCover')
            ->assertHasNoErrors();

        Storage::disk('public')->assertExists('events/manual.jpg');
    }

    public function test_website_cover_must_be_a_png_image(): void
    {
        Storage::fake('public');

        $event = $this->communityEvent();
        $this->actingAs(User::factory()->create());

        Livewire::test(EventCovers::class, ['record' => $event->getRouteKey()])
            ->set('websiteCover', UploadedFile::fake()->create('cover.pdf', 10, 'application/pdf'))
            ->call('saveWebsiteCover')
            ->assertHasErrors(['websiteCover']);

        $this->assertNull($event->refresh()->image_path);
    }

    public function test_edit_page_links_to_covers_only_for_community_events(): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(EditEvent::class, ['record' => $this->communityEvent()->getRouteKey()])
            ->assertActionVisible('covers');

        Livewire::test(EditEvent::class, ['record' => $this->communityEvent(['is_community_event' => false])->getRouteKey()])
            ->assertActionHidden('covers');
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    private function communityEvent(array $attributes = []): Event
    {
        return Event::factory()->create([
            'title' => 'Salento Dev – Lightning Night @ Taurisano',
            'cover_title' => 'Meetup',
            'edition' => 6,
            'subtitle' => 'Lightning talks',
            'cover_note' => 'Gradita conferma',
            'location' => 'Caffetteria Normal - Taurisano',
            'starts_at' => '2026-10-29 18:00:00',
            'image_path' => null,
            'is_published' => true,
            'is_community_event' => true,
            ...$attributes,
        ]);
    }

    private function signedCoverUrl(Event $event, CoverFormat $format): string
    {
        return URL::signedRoute('events.cover', ['event' => $event, 'format' => $format]);
    }
}
