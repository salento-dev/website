<?php

namespace Tests\Feature;

use Tests\TestCase;

class PolicyPagesTest extends TestCase
{
    public function test_privacy_policy_describes_cookieless_analytics_and_retention(): void
    {
        config(['analytics.retention_months' => 12]);

        $this->get(route('privacy-policy'))
            ->assertOk()
            ->assertSee('Statistiche di utilizzo')
            ->assertSee('senza cookie', false)
            ->assertSee('identificativo')
            ->assertSee('cambia ogni giorno e viene poi cancellato')
            ->assertSee('conservate in forma dettagliata per 12 mesi')
            ->assertSee('art. 11 GDPR')
            ->assertDontSee('vengono cancellati immediatamente dopo');
    }

    public function test_privacy_policy_retention_follows_configuration(): void
    {
        config(['analytics.retention_months' => 6]);

        $this->get(route('privacy-policy'))
            ->assertOk()
            ->assertSee('conservate in forma dettagliata per 6 mesi');
    }

    public function test_cookie_policy_lists_technical_cookies_with_configured_duration(): void
    {
        config(['session.lifetime' => 120, 'session.cookie' => 'salento_session']);

        $this->get(route('cookie-policy'))
            ->assertOk()
            ->assertSeeInOrder(['XSRF-TOKEN', '2 ore', 'salento_session', '2 ore'])
            ->assertSee('Le statistiche di utilizzo del sito sono raccolte')
            ->assertSee('href="'.route('privacy-policy').'"', false);
    }
}
