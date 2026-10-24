@section('hero')
    <x-sections.page-hero :badge="'Legal // Data Protection'" :description="'Informativa sul trattamento dei dati personali ai sensi del Regolamento UE 2016/679 (GDPR).'">
        Privacy <span class="text-primary">Policy</span>
    </x-sections.page-hero>
@endsection

<div class="mx-auto max-w-3xl">

    {{-- Last updated --}}
    <div class="mb-12 flex items-center gap-3 font-roboto-mono text-xs text-surface-400">
        <span class="text-primary">$</span>
        <span>last_updated: <span class="text-surface-200">10 aprile 2026</span></span>
    </div>

    <div class="flex flex-col gap-12">

        {{-- 1. Titolare --}}
        <section>
            <div class="mb-4 flex items-center gap-3">
                <span class="font-roboto-mono text-xs text-primary">01.</span>
                <h2 class="font-outfit text-xl font-bold uppercase tracking-tight text-surface-50">Titolare del Trattamento</h2>
            </div>
            <div class="border-l-2 border-primary/20 pl-6 text-sm leading-relaxed text-surface-400 space-y-3">
                <p>
                    Il Titolare del trattamento dei dati personali raccolti tramite il sito <span class="text-surface-200">salento.dev</span> è la
                    community <strong class="text-surface-200">Salento.dev</strong>, un'associazione informale di sviluppatori con sede nel Salento (Puglia, Italia).
                </p>
                <p>
                    Per esercitare i tuoi diritti o per qualsiasi informazione relativa al trattamento dei dati, puoi contattarci
                    all'indirizzo e-mail:
                    <a href="mailto:{{ config('community.email') }}" class="text-primary hover:text-primary-dark transition-colors">{{ config('community.email') }}</a>.
                </p>
            </div>
        </section>

        <div class="border-t border-border"></div>

        {{-- 2. Dati raccolti --}}
        <section>
            <div class="mb-4 flex items-center gap-3">
                <span class="font-roboto-mono text-xs text-primary">02.</span>
                <h2 class="font-outfit text-xl font-bold uppercase tracking-tight text-surface-50">Dati Raccolti</h2>
            </div>
            <div class="border-l-2 border-primary/20 pl-6 text-sm leading-relaxed text-surface-400 space-y-6">

                <div>
                    <h3 class="mb-2 font-semibold text-surface-200">Dati di navigazione</h3>
                    <p>
                        I sistemi informatici e le procedure software preposte al funzionamento del sito acquisiscono, nel corso
                        del loro normale esercizio, alcuni dati personali la cui trasmissione è implicita nell'uso dei protocolli di
                        comunicazione di Internet. Rientrano in questa categoria gli indirizzi IP, il tipo di browser, il sistema
                        operativo, il nome a dominio del computer utilizzato per la connessione e altri parametri relativi al sistema
                        operativo e all'ambiente informatico dell'utente.
                    </p>
                    <p class="mt-2">
                        Questi dati vengono utilizzati esclusivamente al fine di ricavare informazioni statistiche anonime sull'uso
                        del sito e per controllarne il corretto funzionamento, e vengono cancellati immediatamente dopo
                        l'elaborazione.
                    </p>
                </div>

                <div>
                    <h3 class="mb-2 font-semibold text-surface-200">Dati forniti volontariamente</h3>
                    <p>
                        L'invio facoltativo, esplicito e volontario di comunicazioni agli indirizzi indicati su questo sito comporta
                        la successiva acquisizione dell'indirizzo del mittente, necessario per rispondere alle richieste, nonché
                        degli eventuali altri dati personali inseriti nella comunicazione.
                    </p>
                </div>

            </div>
        </section>

        <div class="border-t border-border"></div>

        {{-- 3. Finalità e base giuridica --}}
        <section>
            <div class="mb-4 flex items-center gap-3">
                <span class="font-roboto-mono text-xs text-primary">03.</span>
                <h2 class="font-outfit text-xl font-bold uppercase tracking-tight text-surface-50">Finalità e Base Giuridica</h2>
            </div>
            <div class="border-l-2 border-primary/20 pl-6 text-sm leading-relaxed text-surface-400 space-y-3">
                <p>I dati personali sono trattati per le seguenti finalità:</p>
                <ul class="space-y-2 mt-3">
                    <li class="flex items-start gap-2">
                        <span class="mt-1 shrink-0 text-primary">›</span>
                        <span><strong class="text-surface-200">Erogazione del servizio</strong> — consentire la navigazione e la fruizione dei contenuti del sito
                            (base giuridica: legittimo interesse del Titolare, art. 6 lett. f GDPR).</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="mt-1 shrink-0 text-primary">›</span>
                        <span><strong class="text-surface-200">Risposta alle comunicazioni</strong> — gestire le richieste inviate via e-mail
                            (base giuridica: esecuzione di misure precontrattuali o consenso, art. 6 lett. b/a GDPR).</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="mt-1 shrink-0 text-primary">›</span>
                        <span><strong class="text-surface-200">Sicurezza informatica</strong> — prevenire abusi, frodi e attacchi informatici
                            (base giuridica: legittimo interesse del Titolare, art. 6 lett. f GDPR).</span>
                    </li>
                </ul>
            </div>
        </section>

        <div class="border-t border-border"></div>

        {{-- 4. Cookie --}}
        <section>
            <div class="mb-4 flex items-center gap-3">
                <span class="font-roboto-mono text-xs text-primary">04.</span>
                <h2 class="font-outfit text-xl font-bold uppercase tracking-tight text-surface-50">Cookie</h2>
            </div>
            <div class="border-l-2 border-primary/20 pl-6 text-sm leading-relaxed text-surface-400 space-y-3">
                <p>
                    Il sito utilizza esclusivamente <strong class="text-surface-200">cookie tecnici strettamente necessari</strong> al
                    funzionamento del sito (ad esempio i cookie di sessione per il pannello di amministrazione). Non vengono
                    utilizzati cookie di profilazione, di tracciamento comportamentale o cookie di terze parti a scopo
                    pubblicitario.
                </p>
                <p>
                    Puoi configurare il tuo browser per bloccare o eliminare i cookie, ma questo potrebbe compromettere alcune
                    funzionalità del sito.
                </p>
            </div>
        </section>

        <div class="border-t border-border"></div>

        {{-- 5. Servizi terze parti --}}
        <section>
            <div class="mb-4 flex items-center gap-3">
                <span class="font-roboto-mono text-xs text-primary">05.</span>
                <h2 class="font-outfit text-xl font-bold uppercase tracking-tight text-surface-50">Servizi di Terze Parti</h2>
            </div>
            <div class="border-l-2 border-primary/20 pl-6 text-sm leading-relaxed text-surface-400 space-y-6">

                <div>
                    <h3 class="mb-2 font-semibold text-surface-200">Google Fonts</h3>
                    <p>
                        Il sito utilizza Google Fonts per il caricamento dei caratteri tipografici. Questo servizio è gestito da
                        Google LLC e può comportare il trasferimento dell'indirizzo IP del visitatore ai server di Google negli
                        Stati Uniti. Google tratta questi dati secondo la propria informativa privacy. Per maggiori informazioni:
                        <a href="https://policies.google.com/privacy" target="_blank" rel="noopener noreferrer"
                            class="text-primary hover:text-primary-dark transition-colors">policies.google.com/privacy</a>.
                    </p>
                </div>

                <div>
                    <h3 class="mb-2 font-semibold text-surface-200">Social Media</h3>
                    <p>
                        Il sito contiene link ai profili social della community (WhatsApp, Instagram, LinkedIn). Questi link
                        reindirizzano a piattaforme esterne; il trattamento dei dati effettuato su tali piattaforme è disciplinato
                        dalle rispettive informative privacy, sulle quali il Titolare non ha alcun controllo.
                    </p>
                </div>

            </div>
        </section>

        <div class="border-t border-border"></div>

        {{-- 6. Conservazione --}}
        <section>
            <div class="mb-4 flex items-center gap-3">
                <span class="font-roboto-mono text-xs text-primary">06.</span>
                <h2 class="font-outfit text-xl font-bold uppercase tracking-tight text-surface-50">Conservazione dei Dati</h2>
            </div>
            <div class="border-l-2 border-primary/20 pl-6 text-sm leading-relaxed text-surface-400 space-y-3">
                <p>
                    I dati di navigazione sono conservati per il tempo strettamente necessario alle finalità per cui sono stati
                    raccolti. Le comunicazioni e-mail sono conservate per il tempo necessario a gestire la richiesta e, in ogni
                    caso, non oltre i termini di legge applicabili.
                </p>
            </div>
        </section>

        <div class="border-t border-border"></div>

        {{-- 7. Diritti --}}
        <section>
            <div class="mb-4 flex items-center gap-3">
                <span class="font-roboto-mono text-xs text-primary">07.</span>
                <h2 class="font-outfit text-xl font-bold uppercase tracking-tight text-surface-50">Diritti dell'Interessato</h2>
            </div>
            <div class="border-l-2 border-primary/20 pl-6 text-sm leading-relaxed text-surface-400 space-y-3">
                <p>
                    In qualità di interessato, hai il diritto di esercitare in qualsiasi momento i diritti previsti dagli
                    articoli 15–22 del GDPR:
                </p>
                <ul class="space-y-2 mt-3">
                    @foreach ([
                        ['art. 15', 'Diritto di accesso', 'ottenere conferma del trattamento e copia dei dati che ti riguardano'],
                        ['art. 16', 'Diritto di rettifica', 'richiedere la correzione di dati inesatti o incompleti'],
                        ['art. 17', 'Diritto alla cancellazione', 'chiedere la rimozione dei dati (diritto all\'oblio)'],
                        ['art. 18', 'Diritto di limitazione', 'richiedere la limitazione del trattamento in determinate circostanze'],
                        ['art. 20', 'Diritto alla portabilità', 'ricevere i dati in formato strutturato e leggibile da macchina'],
                        ['art. 21', 'Diritto di opposizione', 'opporti al trattamento basato sul legittimo interesse'],
                    ] as [$article, $name, $desc])
                    <li class="flex items-start gap-2">
                        <span class="mt-0.5 shrink-0 font-roboto-mono text-xs text-primary/60">{{ $article }}</span>
                        <span><strong class="text-surface-200">{{ $name }}</strong> — {{ $desc }}.</span>
                    </li>
                    @endforeach
                </ul>
                <p class="mt-4">
                    Per esercitare i tuoi diritti scrivi a
                    <a href="mailto:{{ config('community.email') }}" class="text-primary hover:text-primary-dark transition-colors">{{ config('community.email') }}</a>.
                    Hai inoltre il diritto di proporre reclamo al
                    <a href="https://www.garanteprivacy.it" target="_blank" rel="noopener noreferrer"
                        class="text-primary hover:text-primary-dark transition-colors">Garante per la Protezione dei Dati Personali</a>.
                </p>
            </div>
        </section>

        <div class="border-t border-border"></div>

        {{-- 8. Modifiche --}}
        <section>
            <div class="mb-4 flex items-center gap-3">
                <span class="font-roboto-mono text-xs text-primary">08.</span>
                <h2 class="font-outfit text-xl font-bold uppercase tracking-tight text-surface-50">Modifiche alla Policy</h2>
            </div>
            <div class="border-l-2 border-primary/20 pl-6 text-sm leading-relaxed text-surface-400">
                <p>
                    Il Titolare si riserva il diritto di apportare modifiche alla presente informativa in qualsiasi momento,
                    dandone pubblicità agli utenti su questa pagina. Si prega di consultare questa pagina periodicamente,
                    facendo riferimento alla data di ultimo aggiornamento indicata in cima.
                </p>
            </div>
        </section>

    </div>
</div>
