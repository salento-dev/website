# 06 · Sicurezza & Privacy

Documento tecnico-normativo per lo stack **Laravel 12 / Livewire 4 / Filament 5**. Riferimenti: **GDPR** (Reg. UE 2016/679), Linee guida **Garante Privacy** (2021), **OWASP Top 10**. Le criticità note del progetto sono incrociate con [Note e debito tecnico](04_note_e_debito_tecnico.md). Fonti in fondo.

> Le indicazioni qui sono **raccomandazioni**; gli interventi sul codice vanno discussi e fatti in PR dedicate.

## Indice
1. [Laravel — security best practices](#1-laravel-12--security-best-practices)
2. [OWASP Top 10 — rischi rilevanti](#2-owasp-top-10-2021--rischi-rilevanti)
3. [GDPR per un sito community](#3-gdpr-per-un-sito-community)
4. [Cookie law italiana (Garante)](#4-cookie-law-italiana--garante-privacy)
5. [Analytics privacy-friendly](#5-analytics-privacy-friendly-e-cookieless)
6. [Upload file e storage pubblico](#6-sicurezza-upload-file-e-storage-pubblico)
7. [Gestione segreti e .env](#7-gestione-segreti-e-env)
8. [Sicurezza Livewire & Filament](#8-sicurezza-livewire-e-filament)
9. [Checklist di hardening](#9-checklist-di-hardening)
10. [Fonti](#10-fonti)

---

## 1. Laravel 12 — security best practices

### 1.1 Mass assignment
Usare `$fillable` minimale su ogni modello; mai `$guarded = []` né `Model::unguard()`. Far passare l'input da una `FormRequest` validata. **Stato**: i modelli dichiarano `$fillable` espliciti (`User` → `name,email,password`; `PageView` → solo i campi previsti). ✓ — [doc](https://laravel.com/docs/12.x/eloquent#mass-assignment)

### 1.2 CSRF
`VerifyCsrfToken` attivo sul gruppo `web` e nei middleware Filament; Livewire gestisce il token automaticamente. Nessuna route API stateless. ✓ — [doc](https://laravel.com/docs/12.x/csrf)

### 1.3 Autenticazione e autorizzazione
- ⚠️ **CRITICO**: `User::canAccessPanel()` ritorna sempre `true` → qualunque utente accede all'intero admin.
- ✓ Il seeder dell'admin legge le credenziali dall'ambiente e non crea utenti in produzione (`AdminUserSeeder`).
- Nessuna Policy per i Resource Filament.

```php
// app/Models/User.php — opzioni per canAccessPanel
public function canAccessPanel(Panel $panel): bool
{
    return str_ends_with($this->email, '@salento.dev'); // A: dominio
    // return (bool) $this->is_admin;                     // B: campo booleano
    // return $this->hasRole('admin');                    // C: Filament Shield / Spatie
}
```

Cambiare subito la password admin di produzione; condizionare il seeder all'ambiente. — [auth](https://laravel.com/docs/12.x/authentication) · [authz](https://laravel.com/docs/12.x/authorization)

### 1.4 `env()` vs `config()`, APP_KEY
Mai `env()` fuori da `config/` (con `config:cache` ritorna `null`). L'`APP_KEY` cifra cookie/sessione e firma i payload: conservarne un backup sicuro. In produzione `APP_ENV=production`, `APP_DEBUG=false`. **Stato**: `.env` escluso da git ✓; nessun `env()` diretto rilevato. — [encryption](https://laravel.com/docs/12.x/encryption)

### 1.5 Hashing password
`User` usa il cast `'password' => 'hashed'` ✓. In produzione imporre `Password::defaults()` ed eventualmente `uncompromised()`. — [hashing](https://laravel.com/docs/12.x/hashing)

### 1.6 Rate limiting
Filament applica throttle sul login. Per futuri form pubblici, applicare `throttle:5,1`. — [rate limiting](https://laravel.com/docs/12.x/routing#rate-limiting)

---

## 2. OWASP Top 10 (2021) — rischi rilevanti

### A01 · Broken Access Control — ⚠️ CRITICO
`canAccessPanel()` permissivo + nessuna Policy. Mitigazioni: implementare `canAccessPanel()`, generare Policy (`make:policy EventPolicy --model=Event`), valutare [Filament Shield](https://filamentphp.com/plugins/bezhansalleh-shield). — [A01](https://owasp.org/Top10/2021/A01_2021-Broken_Access_Control/) · [OWASP Laravel Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/Laravel_Cheat_Sheet.html)

### A03 · Injection / XSS — ✓ POSITIVO
Eloquent usa binding parametrizzati (ricerche `LIKE` sicure). Il Markdown è reso con `Str::markdown(..., ['html_input' => 'strip', 'allow_unsafe_links' => false])` → niente HTML iniettato. Non usare mai `{!! !!}` su input non fidato. — [A03](https://owasp.org/Top10/2021/A03_2021-Injection/)

### A05 · Security Misconfiguration — ⚠️ CRITICO
Credenziali di default nel seeder; assenza di security header HTTP; verificare `APP_DEBUG=false`.

```php
// DatabaseSeeder.php — non seedare in produzione
if (app()->environment('production')) { return; }
```

Aggiungere header (X-Frame-Options, X-Content-Type-Options, Referrer-Policy, CSP via `spatie/laravel-csp`). — [A05](https://owasp.org/Top10/2021/A05_2021-Security_Misconfiguration/)

### A07 · Authentication Failures
`passwordReset()` attivo; nessun MFA; password debole nel seeder. Valutare 2FA Filament. — [A07](https://owasp.org/Top10/2021/A07_2021-Identification_and_Authentication_Failures/)

### A08 · Software/Data Integrity
Nessuna CI: aggiungere pipeline con `composer audit` e `npm audit`. — [A08](https://owasp.org/Top10/2021/A08_2021-Software_and_Data_Integrity_Failures/)

---

## 3. GDPR per un sito community

salento.dev tratta dati di utenti UE → si applica il GDPR ([Reg. UE 2016/679](https://eur-lex.europa.eu/legal-content/IT/TXT/?uri=celex:32016R0679)).

- **Minimizzazione (art. 5.1.c)**: `TrackPageView` salva `path,url,referrer,user_agent,viewable` ma **non l'IP** → minimizzazione applicata.
- **Base giuridica (art. 6)**: per l'analytics interna, **legittimo interesse** (art. 6.1.f), non pregiudizievole grazie alla minimizzazione (no IP, no cookie di profilazione, dedup per sessione).
- **Informativa (artt. 13-14)**: la pagina `/privacy-policy` deve indicare titolare, finalità/base giuridica, conservazione, diritti dell'interessato.
- **Conservazione (art. 5.1.e)**: definire una retention dei `PageView` (es. 12 mesi) con job schedulato.
- **`user_agent`**: da solo non identifica, ma combinato può contribuire a uno pseudonimo → documentare finalità (analytics aggregata, non profilazione). — [gdpr-info.eu](https://gdpr-info.eu/)

---

## 4. Cookie law italiana — Garante Privacy

Le [Linee guida cookie del Garante (10/06/2021)](https://www.garanteprivacy.it/home/docweb/-/docweb-display/docweb/9677876) distinguono:
- **Cookie tecnici** (sessione, CSRF, autenticazione, preferenze): **non** richiedono consenso.
- **Cookie di profilazione/terze parti**: richiedono **consenso esplicito** e banner (no cookie wall).

**Stato — conforme**: salento.dev usa **solo cookie tecnici** (sessione Laravel/Livewire, CSRF, sessione admin Filament); nessun Google Analytics/Pixel. → **Nessun cookie banner necessario**. La Cookie Policy deve elencare i cookie tecnici (nome, durata, finalità) e dichiarare l'assenza di profilazione. — [FAQ Cookie Garante](https://www.garanteprivacy.it/faq/cookie)

---

## 5. Analytics privacy-friendly e cookieless

`TrackPageView` è funzionalmente analogo a Plausible/Fathom, ma più conservativo:

| | TrackPageView | Plausible | Fathom |
|---|---|---|---|
| IP salvato | **No** | No (hash temporaneo) | No (hash temporaneo) |
| Cookie di tracciamento | No | No | No |
| ID persistente | No | No | No |
| Filtro bot | Sì | Sì | Sì |
| Dedup | 30 min/sessione | giornaliera | per sessione |
| Consenso GDPR | Non necessario | Non necessario | Non necessario |

Plausible spiega che l'IP è usato solo per un hash giornaliero e **mai memorizzato** ([data policy](https://plausible.io/data-policy)); qui non si calcola nemmeno l'hash. Dati senza IP né ID persistenti non raggiungono la soglia di identificabilità diretta (art. 4.1 GDPR). CNIL/DSK hanno confermato l'esenzione dal consenso per analytics privacy-first ([assessment](https://plausible.io/blog/legal-assessment-gdpr-eprivacy)).

**Rischio residuo**: la dedup usa la **sessione PHP** (cookie tecnico già presente per Livewire), non un cookie di tracciamento separato → corretto. Documentarlo in Cookie/Privacy Policy.

---

## 6. Sicurezza upload file e storage pubblico

Config attuale (`ResourceForm`): `FileUpload::make('file_path')->disk('public')->directory('resources/files')->visibility('public')->acceptedFileTypes([...])` (PDF, Office, archivi; nessun eseguibile).

**Rischi**: validazione solo su MIME/magic-bytes aggirabile; file serviti dal disco `public` → se il web server eseguisse PHP in quella dir, rischio RCE. Nomi file generati casualmente da Filament ✓ (no traversal/override).

**Mitigazioni**:
```nginx
location ~* /storage/.*\.php$ { deny all; return 403; }
```
```php
FileUpload::make('file_path')->rules(['mimes:pdf,doc,docx,odt,xls,xlsx,ods,ppt,pptx,odp,zip,rar,gz,tar']);
```
Per contenuti non pubblici, usare `->visibility('private')` con download autenticato. — [Securing Laravel — file upload](https://securinglaravel.com/laravel-security-file-upload-vulnerability/)

---

## 7. Gestione segreti e .env

- ✓ `.env` (e `.env.backup`, `.env.production`) esclusi da git; presente `.env.example`.
- ✓ Nessuna credenziale nel codice: l'admin di sviluppo usa `ADMIN_EMAIL` / `ADMIN_PASSWORD` dal `.env`.
- **Verifica una tantum**: `git log --all -- .env*` per accertarsi che nessun segreto sia mai stato committato.

```php
// config/auth.php
'seed_admin' => [
    'name' => env('ADMIN_NAME', 'Admin'),
    'email' => env('ADMIN_EMAIL', 'admin@salento.dev'),
    'password' => env('ADMIN_PASSWORD'), // vuota = generata a caso
],
```

In produzione: `chmod 600 .env`; bloccare i dotfile in Nginx (`location ~ /\. { deny all; }`); backup dell'`APP_KEY` in un vault esterno. — [Laravel security best practices](https://benjamincrozat.com/laravel-security-best-practices)

---

## 8. Sicurezza Livewire e Filament

### 8.1 Proprietà pubbliche e `#[Locked]`
Ogni proprietà pubblica Livewire è **input non fidato** (modificabile lato client). `EventsPage` espone `$search` e `$sortBy`. `$sortBy` è usata in un `match()` con `default` sicuro, ma andrebbe protetta:
```php
use Livewire\Attributes\Locked;
#[Locked] public string $sortBy = 'newest';
// oppure validare nel setter contro array_keys(self::sortOptions())
```
— [Livewire security](https://livewire.laravel.com/docs/4.x/security) · [#[Locked]](https://livewire.laravel.com/docs/4.x/attribute-locked)

### 8.2 Autorizzazione nelle azioni
I componenti pubblici sono in sola lettura ✓. Per future azioni che modificano dati, chiamare `$this->authorize(...)` in ogni metodo pubblico.

### 8.3 File visibility Filament 5
Default **privato** (miglioria di sicurezza). Il progetto imposta `visibility('public')` intenzionalmente per le risorse scaricabili: accettabile per contenuti pubblici, con le misure della §6. Per upload non pubblici, lasciare il default privato.

### 8.4 `canAccessPanel()` — criticità
Implementazione attuale (`return true`) = rischio di sicurezza più grave del progetto. Vedi §1.3. — [Filament panel users](https://filamentphp.com/docs/3.x/panels/users)

---

## 9. Checklist di hardening

**🔴 Critici (subito)**
- [ ] `canAccessPanel()` con logica reale (ruolo / dominio email / `is_admin`).
- [ ] Cambiare la password admin di produzione se coincide con quella di sviluppo usata in passato.
- [x] Rimuovere la password fissa dal seeder.
- [x] Guardia sulla produzione nel seeder dell'admin (`AdminUserSeeder`).

**🟠 Alti (breve periodo)**
- [ ] `APP_DEBUG=false` e `APP_ENV=production` verificati.
- [ ] Security header HTTP (X-Frame-Options, X-Content-Type-Options, Referrer-Policy, CSP).
- [ ] Nginx: blocco esecuzione PHP in `storage` e accesso ai dotfile.
- [ ] Validazione `$sortBy` / `#[Locked]` in `EventsPage`.
- [ ] Policy Filament per Event/Resource/Story/Partner/Author.
- [ ] Retention policy dei `PageView`.

**🟡 Medi (roadmap)**
- [ ] CI con `composer audit` + `npm audit`.
- [ ] MFA per l'admin.
- [ ] `mimes` + `mimetypes` sugli upload.
- [ ] Aggiornare Privacy/Cookie Policy (user_agent, retention, base giuridica, elenco cookie tecnici).

**🔵 Verifiche una tantum**
- [ ] `git log --all -- .env*`.
- [ ] `chmod 600 .env` in produzione; backup `APP_KEY` in vault.
- [ ] `composer outdated` / `npm outdated`.

---

## 10. Fonti

**Laravel** — [CSRF](https://laravel.com/docs/12.x/csrf) · [Auth](https://laravel.com/docs/12.x/authentication) · [Authorization](https://laravel.com/docs/12.x/authorization) · [Hashing](https://laravel.com/docs/12.x/hashing) · [Encryption](https://laravel.com/docs/12.x/encryption) · [Rate limiting](https://laravel.com/docs/12.x/routing#rate-limiting) · [Mass assignment](https://laravel.com/docs/12.x/eloquent#mass-assignment)

**Livewire / Filament** — [Livewire security](https://livewire.laravel.com/docs/4.x/security) · [#[Locked]](https://livewire.laravel.com/docs/4.x/attribute-locked) · [Filament panel users](https://filamentphp.com/docs/3.x/panels/users) · [FileUpload](https://filamentphp.com/docs/3.x/forms/fields/file-upload) · [Filament Shield](https://filamentphp.com/plugins/bezhansalleh-shield)

**OWASP** — [Top 10:2021](https://owasp.org/Top10/2021/) · [A01](https://owasp.org/Top10/2021/A01_2021-Broken_Access_Control/) · [A03](https://owasp.org/Top10/2021/A03_2021-Injection/) · [A05](https://owasp.org/Top10/2021/A05_2021-Security_Misconfiguration/) · [Laravel Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/Laravel_Cheat_Sheet.html)

**GDPR / Garante** — [Reg. UE 2016/679 (EUR-Lex)](https://eur-lex.europa.eu/legal-content/IT/TXT/?uri=celex:32016R0679) · [gdpr-info.eu](https://gdpr-info.eu/) · [Linee guida cookie Garante 2021](https://www.garanteprivacy.it/home/docweb/-/docweb-display/docweb/9677876) · [FAQ Cookie](https://www.garanteprivacy.it/faq/cookie)

**Analytics privacy** — [Plausible data policy](https://plausible.io/data-policy) · [Plausible legal assessment](https://plausible.io/blog/legal-assessment-gdpr-eprivacy) · [Laravel Blog — Fathom](https://blog.laravel.com/ditching-google-analytics-for-fathom)

**Upload / segreti** — [Securing Laravel — file upload](https://securinglaravel.com/laravel-security-file-upload-vulnerability/) · [Laravel security best practices](https://benjamincrozat.com/laravel-security-best-practices) · [spatie/laravel-csp](https://github.com/spatie/laravel-csp)
