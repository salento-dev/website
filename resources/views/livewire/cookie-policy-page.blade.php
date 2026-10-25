@section('hero')
    <x-sections.page-hero :badge="'Legal // Browser Storage'" :description="'Informativa sull\'utilizzo dei cookie sul sito salento.dev ai sensi del Provvedimento del Garante Privacy del 10 giugno 2021.'">
        Cookie <span class="text-primary">Policy</span>
    </x-sections.page-hero>
@endsection

<div class="mx-auto max-w-3xl">

    {{-- Last updated --}}
    <div class="mb-12 flex items-center gap-3 font-roboto-mono text-xs text-surface-400">
        <span class="text-primary">$</span>
        <span>last_updated: <span class="text-surface-200">25 ottobre 2026</span></span>
        <span class="ml-auto rounded-xs border border-primary/20 bg-primary/5 px-2 py-0.5 text-primary">Solo cookie tecnici</span>
    </div>

    <div class="flex flex-col gap-12">

        {{-- 1. Cosa sono i cookie --}}
        <section>
            <div class="mb-4 flex items-center gap-3">
                <span class="font-roboto-mono text-xs text-primary">01.</span>
                <h2 class="font-outfit text-xl font-bold uppercase tracking-tight text-surface-50">Cosa sono i Cookie</h2>
            </div>
            <div class="border-l-2 border-primary/20 pl-6 text-sm leading-relaxed text-surface-400">
                <p>
                    I cookie sono piccoli file di testo che i siti web visitati dall'utente inviano al suo terminale (computer,
                    tablet, smartphone), dove vengono memorizzati per essere ritrasmessi agli stessi siti alla visita successiva.
                    Permettono al sito di ricordare le azioni e le preferenze dell'utente nel corso del tempo.
                </p>
            </div>
        </section>

        <div class="border-t border-border"></div>

        {{-- 2. Cookie utilizzati --}}
        <section>
            <div class="mb-4 flex items-center gap-3">
                <span class="font-roboto-mono text-xs text-primary">02.</span>
                <h2 class="font-outfit text-xl font-bold uppercase tracking-tight text-surface-50">Cookie Utilizzati da Questo Sito</h2>
            </div>
            <div class="border-l-2 border-primary/20 pl-6 text-sm leading-relaxed text-surface-400 space-y-4">
                <p>
                    Il sito <span class="text-surface-200">salento.dev</span> utilizza esclusivamente
                    <strong class="text-surface-200">cookie tecnici strettamente necessari</strong> al funzionamento del sito.
                    Non vengono utilizzati cookie di profilazione, cookie analitici o cookie di terze parti a scopo pubblicitario.
                    Per i cookie tecnici non è richiesto il consenso dell'utente.
                </p>
                <p>
                    Le statistiche di utilizzo del sito sono raccolte <strong class="text-surface-200">senza cookie</strong> e senza
                    altre forme di memorizzazione nel browser: i cookie elencati qui sotto non vengono usati per contare le visite.
                    Come funzionano le statistiche è spiegato nella
                    <a href="{{ route('privacy-policy') }}" class="text-primary hover:text-primary-light transition-colors">Privacy Policy</a>.
                </p>

                {{-- Cookie table --}}
                <div class="mt-6 overflow-x-auto rounded-xs border border-border">
                    <table class="w-full text-xs">
                        <thead>
                            <tr class="border-b border-border bg-surface-900">
                                <th class="px-4 py-3 text-left font-roboto-mono font-semibold text-surface-200">Nome</th>
                                <th class="px-4 py-3 text-left font-roboto-mono font-semibold text-surface-200">Tipo</th>
                                <th class="px-4 py-3 text-left font-roboto-mono font-semibold text-surface-200">Finalità</th>
                                <th class="px-4 py-3 text-left font-roboto-mono font-semibold text-surface-200">Durata</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border/50">
                            @php
                                $sessionMinutes = (int) config('session.lifetime');
                                $sessionDuration = $sessionMinutes % 60 === 0
                                    ? trans_choice(':count ora|:count ore', intdiv($sessionMinutes, 60))
                                    : $sessionMinutes.' minuti';
                            @endphp
                            @foreach ([
                                ['XSRF-TOKEN', 'Tecnico / Sicurezza', 'Protezione contro attacchi CSRF (Cross-Site Request Forgery). Generato automaticamente dal framework Laravel.', $sessionDuration],
                                [config('session.cookie', 'laravel_session'), 'Tecnico / Sessione', 'Mantiene lo stato della sessione dell\'utente durante la navigazione. Necessario per il corretto funzionamento del sito.', $sessionDuration],
                            ] as [$name, $type, $purpose, $duration])
                            <tr class="hover:bg-surface-900/50 transition-colors">
                                <td class="px-4 py-3 font-roboto-mono text-primary">{{ $name }}</td>
                                <td class="px-4 py-3 text-surface-400">{{ $type }}</td>
                                <td class="px-4 py-3 text-surface-400">{{ $purpose }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-surface-200">{{ $duration }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <div class="border-t border-border"></div>

        {{-- 3. Nessun banner --}}
        <section>
            <div class="mb-4 flex items-center gap-3">
                <span class="font-roboto-mono text-xs text-primary">03.</span>
                <h2 class="font-outfit text-xl font-bold uppercase tracking-tight text-surface-50">Perché Non c'è il Banner Cookie</h2>
            </div>
            <div class="border-l-2 border-primary/20 pl-6 text-sm leading-relaxed text-surface-400 space-y-3">
                <p>
                    Secondo il Provvedimento del Garante per la protezione dei dati personali del 10 giugno 2021 e le
                    Linee guida ePrivacy, i <strong class="text-surface-200">cookie tecnici strettamente necessari</strong> sono
                    esenti dall'obbligo di consenso.
                </p>
                <p>
                    Poiché questo sito utilizza esclusivamente cookie tecnici, non è necessario mostrare un banner di consenso
                    né richiedere autorizzazioni all'utente per il loro utilizzo.
                </p>
            </div>
        </section>

        <div class="border-t border-border"></div>

        {{-- 4. Gestione browser --}}
        <section>
            <div class="mb-4 flex items-center gap-3">
                <span class="font-roboto-mono text-xs text-primary">04.</span>
                <h2 class="font-outfit text-xl font-bold uppercase tracking-tight text-surface-50">Gestione dei Cookie dal Browser</h2>
            </div>
            <div class="border-l-2 border-primary/20 pl-6 text-sm leading-relaxed text-surface-400 space-y-4">
                <p>
                    Puoi controllare, limitare o eliminare i cookie attraverso le impostazioni del tuo browser. Tieni presente
                    che disabilitare i cookie tecnici potrebbe compromettere il corretto funzionamento del sito.
                </p>
                <p>Di seguito i link alle guide ufficiali dei principali browser:</p>
                <ul class="space-y-2 mt-2">
                    @foreach ([
                        ['Chrome', 'https://support.google.com/chrome/answer/95647'],
                        ['Firefox', 'https://support.mozilla.org/it/kb/protezione-antitracciamento-avanzata-firefox-desktop'],
                        ['Safari', 'https://support.apple.com/it-it/guide/safari/sfri11471/mac'],
                        ['Edge', 'https://support.microsoft.com/it-it/microsoft-edge/eliminare-i-cookie-in-microsoft-edge-63947406-40ac-c3b8-57b9-2a946a29ae09'],
                    ] as [$browser, $url])
                    <li class="flex items-center gap-2">
                        <span class="text-primary">›</span>
                        <a href="{{ $url }}" target="_blank" rel="noopener noreferrer"
                            class="text-primary hover:text-primary-light transition-colors">{{ $browser }}<span class="sr-only">, si apre in una nuova scheda</span></a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </section>

        <div class="border-t border-border"></div>

        {{-- 5. Contatti --}}
        <section>
            <div class="mb-4 flex items-center gap-3">
                <span class="font-roboto-mono text-xs text-primary">05.</span>
                <h2 class="font-outfit text-xl font-bold uppercase tracking-tight text-surface-50">Contatti</h2>
            </div>
            <div class="border-l-2 border-primary/20 pl-6 text-sm leading-relaxed text-surface-400">
                <p>
                    Per qualsiasi domanda relativa all'utilizzo dei cookie su questo sito, puoi contattarci all'indirizzo
                    <a href="mailto:{{ config('community.email') }}" class="text-primary hover:text-primary-light transition-colors">{{ config('community.email') }}</a>.
                    Per informazioni più ampie sul trattamento dei dati personali, consulta la nostra
                    <a href="{{ route('privacy-policy') }}" class="text-primary hover:text-primary-light transition-colors">Privacy Policy</a>.
                </p>
            </div>
        </section>

    </div>
</div>
