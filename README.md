# کافه صاحبقرانیه — Saheb Gharaniyeh Cafe

A mobile-first digital menu for a traditional Persian café. Deep black and antique gold,
ornate line-art frames, full RTL, Persian typography — modelled on the café's printed
four-panel menu (`images/photo_5767233449319141139_y.jpg`).

No cart, no checkout, no payments: it is a menu you read on a phone at the table.

---

## Getting started

```bash
composer install
npm install

cp .env.example .env         # already done in this checkout
php artisan key:generate

php artisan migrate:fresh --seed   # schema + the real menu from the printed reference
npm run build                      # or: npm run dev

php artisan serve
```

Run the test suite with `php artisan test`.

The database is SQLite (`database/database.sqlite`) out of the box; nothing else is required.

---

## Pages

| Route | Name | What it does |
| --- | --- | --- |
| `/` | `home` | Three large cards (گرم / سرد / قلیان) right under the hero, café intro below them. Each card links into the menu. |
| `/menu` | `menu` | The whole menu: one ornate panel per section, sticky bar showing the section in view. |
| `/menu/{section}` | `menu.section` | Same page, auto-scrolled to that section on load (e.g. `/menu/hookah-deluxe`). |

Both routes are single-action controllers (`HomeController`, `MenuController`). An unknown
`{section}` is ignored rather than 404-ing, so a stale link still shows the full menu.

### Themes

Dark is the house theme. The switch is the round button pinned to the **top-right corner**
of every page (`resources/views/components/theme-toggle.blade.php`), and light is the
opt-in: the same printed menu on cream card stock.

- The palette is chosen in `<head>` by a short inline script — before the first paint, so
  no page ever flashes the wrong theme — and remembered in `localStorage` under `sg-theme`.
  Anything other than an explicit `light` means dark, including a first visit.
- `resources/js/app.js` only keeps the button (`aria-pressed`, `title`),
  `<meta name="theme-color">` and `localStorage` in step after a tap.
- The light theme lives in one block at the bottom of `resources/css/app.css` under
  `html[data-theme='light']`. It re-points the palette tokens — so every Tailwind utility
  in Blade (`text-cream-dim`, `bg-gold-900/30` …) follows along without touching a view —
  and then restates only the hard-coded colours that were tuned to glow on black. Those
  rules sit outside `@layer`, so they win over the component layer without `!important`.
- Ramp semantics are preserved: a low gold index still means "for headings", it is just
  deep bronze on paper instead of pale gold on black.

### Sticky section bar

`resources/js/app.js` runs a rAF-throttled scroll spy: the current section is the last one
whose top edge has passed under the bar. It swaps the label in `#section-flag-text`, marks
the matching chip with `aria-current="true"`, scrolls that chip into view, updates the URL
hash with `history.replaceState`, and drives the thin gold progress rule. Anchor clicks are
intercepted so the sticky bar height is subtracted from the scroll target.

---

## Data model

Everything on both pages comes from the database — no menu copy is hard-coded in Blade.

**`categories`** — one row per menu section.

| Column | Purpose |
| --- | --- |
| `slug` | Anchor id on the menu page and the `{section}` route segment |
| `name`, `short_name`, `latin_name`, `subtitle`, `description` | Section copy (`short_name` is the chip label) |
| `kind` | `drink` \| `hookah` — `App\Enums\CategoryKind` |
| `layout` | `grid` \| `list` — `App\Enums\CategoryLayout`; drinks use the card grid, hookah uses flavour rows |
| `icon`, `image_path` | Small glyph next to the title; optional section image |
| `price`, `price_note` | One service price for the whole section (the hookah panels) |
| `card_order`, `card_title`, `card_subtitle`, `card_latin` | Landing-page card. `card_order = NULL` means "not on the landing page" |
| `sort_order`, `is_active` | Ordering and visibility |

**`products`** — one row per item. `price` is nullable on purpose: the printed menu leaves
`قیمت :` blank, so an empty price renders a dotted gold slot instead of a number.
`image_path` is nullable too — when it is empty the card shows the ornate placeholder.
`is_active` hides an item, `is_available` renders it as "موقتاً تمام شد".

**`category_features`** — the extras strip under the Super Deluxe hookah panel
(چای زغالی، میوه فصل، باقلوا …).

**`settings`** — editable site copy (café name, tagline, intro paragraph, hours, address,
phone, Instagram) as `key`/`value` rows. Read through `Setting::map()`, which is cached and
busted automatically on save/delete.

Seeders hold the real menu transcribed from the reference photo: 15 hot drinks, 18 cold
drinks and 16 hookah flavours (seeded into both hookah services), plus 8 deluxe extras.
They use `updateOrCreate`, so re-running them is safe. `tests/Feature/MenuSeederTest.php`
locks those counts in.

---

## Adding an admin panel later

The data layer is already shaped for one:

- Models are plain Eloquent with `$fillable`, enum casts, `active`/`ordered`/`onLanding`
  scopes and factories — usable as-is by Filament, Nova, Livewire or hand-written CRUD.
- `Category::getRouteKeyName()` is `slug`, and slugs are generated on save when blank.
- Prices are `unsignedBigInteger` Tomans (no decimals) and always nullable.
- `image_path` is resolved through `Product::imageUrl()`, which understands both a full URL
  and a path on the `public` disk — so uploads only need `php artisan storage:link`.
- Site copy lives in `settings` rather than in Blade, so it is editable without a deploy.

A panel therefore needs CRUD over four tables and nothing else; no view changes.

---

## Front-end

- Tailwind CSS 4 via `@tailwindcss/vite`, with the design tokens (night/gold palette,
  shadows, easing) declared in `@theme` in `resources/css/app.css` and re-pointed for the
  light theme (see [Themes](#themes)).
- Self-hosted variable fonts in `public/fonts`: Vazirmatn for Persian, Cinzel for the latin
  small-caps lines.
- Blade anonymous components in `resources/views/components`: `frame`, `ornament.*`,
  `icon.*`, `product-card`, `flavor-row`, `price-tag`, `emblem`, `theme-toggle`,
  `site-footer`.
- `@fa(...)` prints Persian digits and `@price(...)` prints a Persian price with " تومان"
  (see `App\Support\Persian` and `AppServiceProvider`).
- Vanilla JS only: theme switch, preloader, IntersectionObserver reveals, image fade-in,
  scroll spy, back-to-top. `prefers-reduced-motion` is respected.

### Screenshots during development

`tools/shot.mjs` drives headless Chrome over CDP for mobile-viewport captures and in-page
diagnostics (overflow check, current section flag):

```bash
node tools/shot.mjs --url=http://127.0.0.1:8000/menu --out=/tmp/menu.png \
  --width=390 --height=844 --reveal --scroll='#hookah-deluxe'
```
