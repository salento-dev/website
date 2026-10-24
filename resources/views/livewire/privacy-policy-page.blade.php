@section('hero')
    <x-sections.page-hero :badge="'Legal // Data Protection'" :description="'Informativa sul trattamento dei dati personali ai sensi del Regolamento UE 2016/679 (GDPR).'">
        Privacy <span class="text-primary">Policy</span>
    </x-sections.page-hero>
@endsection

<div class="mx-auto max-w-3xl">

    {{-- Last updated --}}
    <div class="mb-12 flex items-center gap-3 font-roboto-mono text-xs text-surface-400">
        <span class="text-primary">$</span>
        <span>last_updated: <span class="text-surface-200">25 ottobre 2026</span></span>
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
                    <a href="mailto:{{ config('community.email') }}" class="text-primary hover:text-primary-light transition-colors">{{ config('community.email') }}</a>.
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
                        Questi dati sono usati per far funzionare il sito, per proteggerlo da abusi e per produrre le statistiche
                        di utilizzo descritte qui sotto. L'indirizzo IP non viene salvato nelle statistiche.
                    </p>
                </div>

                <div>
                    <h3 class="mb-2 font-semibold text-surface-200">Statistiche di utilizzo</h3>
                    <p>
                        Per capire quali contenuti sono utili e da dove arrivano i visitatori, il sito registra in un database
                        proprio, senza servizi di terze parti e <strong class="text-surface-200">senza cookie</strong>:
                    </p>
                    <ul class="mt-3 space-y-2">
                        <li class="flex items-start gap-2">
                            <span class="mt-1 shrink-0 text-primary">›</span>
                            <span><strong class="text-surface-200">Pagine visitate</strong> — indirizzo della pagina, data e ora, sito di provenienza (referrer) ed eventuali parametri di campagna (<span class="font-roboto-mono text-xs">utm_source</span>, <span class="font-roboto-mono text-xs">utm_medium</span>, <span class="font-roboto-mono text-xs">utm_campaign</span>).</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="mt-1 shrink-0 text-primary">›</span>
                            <span><strong class="text-surface-200">Browser e dispositivo</strong> — la stringa user agent inviata dal browser e il tipo di dispositivo che se ne ricava (mobile, tablet o desktop).</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="mt-1 shrink-0 text-primary">›</span>
                            <span><strong class="text-surface-200">Click in uscita</strong> — l'apertura di link esterni (gruppo WhatsApp, profili social, risorse, e-mail, altri siti): destinazione del link e pagina da cui è stato aperto.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="mt-1 shrink-0 text-primary">›</span>
                            <span><strong class="text-surface-200">Pagine non trovate</strong> — indirizzi che hanno restituito errore 404, con il numero di richieste e l'ultima pagina di provenienza, per correggere i link rotti.</span>
                        </li>
                    </ul>
                    <p class="mt-3">
                        Per contare i visitatori unici senza cookie viene calcolato un <strong class="text-surface-200">identificativo
                        pseudonimo</strong>: un'impronta crittografica (hash) di indirizzo IP e user agent, combinata con un valore
                        casuale che cambia ogni giorno e viene poi cancellato. L'indirizzo IP non viene memorizzato e, una volta
                        cancellato il valore casuale, non è più possibile collegare tra loro visite di giorni diversi né risalire
                        all'indirizzo di partenza. Le visite riconosciute come provenienti da bot e crawler non vengono registrate.
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
                        <span><strong class="text-surface-200">Statistiche di utilizzo</strong> — misurare in forma aggregata l'uso del sito,
                            l'efficacia dei canali con cui promuoviamo gli eventi e i link rotti, per migliorare contenuti e iniziative
                            della community (base giuridica: legittimo interesse del Titolare, art. 6 lett. f GDPR).</span>
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
                    Le statistiche di utilizzo descritte nella sezione 02 <strong class="text-surface-200">non usano cookie</strong>
                    né altre forme di memorizzazione nel browser (come <span class="font-roboto-mono text-xs">localStorage</span>).
                    Il dettaglio dei cookie tecnici è nella
                    <a href="{{ route('cookie-policy') }}" class="text-primary hover:text-primary-light transition-colors">Cookie Policy</a>.
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
                <ul class="space-y-2">
                    <li class="flex items-start gap-2">
                        <span class="mt-1 shrink-0 text-primary">›</span>
                        <span><strong class="text-surface-200">Valore casuale per l'identificativo pseudonimo</strong> — cambia ogni giorno e viene cancellato al più tardi entro il giorno successivo.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="mt-1 shrink-0 text-primary">›</span>
                        <span><strong class="text-surface-200">Statistiche di utilizzo (visite e click)</strong> — conservate in forma dettagliata per {{ config('analytics.retention_months') }} mesi.
                            Trascorso questo periodo vengono ridotte a totali giornalieri per pagina (numero di visite, click e visitatori),
                            senza identificativi, user agent né provenienza, e i dati dettagliati vengono cancellati.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="mt-1 shrink-0 text-primary">›</span>
                        <span><strong class="text-surface-200">Pagine non trovate</strong> — cancellate quando il link viene corretto o, in ogni caso, {{ config('analytics.retention_months') }} mesi dopo l'ultima richiesta.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="mt-1 shrink-0 text-primary">›</span>
                        <span><strong class="text-surface-200">Comunicazioni e-mail</strong> — per il tempo necessario a gestire la richiesta e, in ogni caso, non oltre i termini di legge applicabili.</span>
                    </li>
                </ul>
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
                        <span class="mt-0.5 shrink-0 font-roboto-mono text-xs text-primary/80">{{ $article }}</span>
                        <span><strong class="text-surface-200">{{ $name }}</strong> — {{ $desc }}.</span>
                    </li>
                    @endforeach
                </ul>
                <p class="mt-4">
                    Per esercitare i tuoi diritti scrivi a
                    <a href="mailto:{{ config('community.email') }}" class="text-primary hover:text-primary-light transition-colors">{{ config('community.email') }}</a>.
                    Poiché le statistiche non conservano l'indirizzo IP né altri dati che ti identifichino direttamente, potremmo
                    non essere in grado di ritrovare le visite che ti riguardano (art. 11 GDPR); in questo caso te lo comunicheremo.
                    Hai inoltre il diritto di proporre reclamo al
                    <a href="https://www.garanteprivacy.it" target="_blank" rel="noopener noreferrer"
                        class="text-primary hover:text-primary-light transition-colors">Garante per la Protezione dei Dati Personali<span class="sr-only">, si apre in una nuova scheda</span></a>.
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
