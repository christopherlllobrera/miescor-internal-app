# Filament v5 — Design System

> Extracted from the Filament v5 source code (`filament/filament`, `filament/support`).
> Built on **Tailwind CSS v4** with OKLCH color space and CSS `@theme` / `@layer` architecture.

---

## 1. Brand Foundations

### Mission / Purpose

Filament is a collection of **full-stack components for accelerated Laravel application development**. It provides a polished, production-ready admin panel and form builder that lets developers ship beautiful, accessible CRUD interfaces without hand-crafting every screen.

### Brand Personality

| Adjective       | Meaning in the Design System                                        |
| ---------------- | ------------------------------------------------------------------- |
| **Polished**     | Every pixel is considered — consistent radii, shadows, and spacing  |
| **Accessible**   | WCAG AA contrast baked into the color resolver; `prefers-reduced-motion` honoured globally |
| **Adaptive**     | First-class dark mode; RTL support (`start`/`end` logical properties) |
| **Composable**   | Tiny, single-responsibility components that nest and compose freely |
| **Utilitarian**  | Function over ornament — clean, neutral surfaces with targeted color accents |

### Voice & Tone

- **UI copy** should be short, direct, and action-oriented (e.g. "Create resource", "Delete record").
- **Error messages** state what went wrong and what the user can do about it.
- Use sentence case for headings, labels, and buttons. Avoid ALL-CAPS except in badges where tracking is tightened.
- Favour positive, confirmatory language ("Saved successfully") over negative phrasing.

---

## 2. Color Palette

Filament uses the **OKLCH** color space for all palette definitions and resolves them via CSS custom properties at runtime. Colors are organized into **semantic aliases** and **named palettes**.

### 2.1 Semantic Color Aliases (Defaults)

| Alias        | Default Palette | Purpose                                     |
| ------------ | --------------- | ------------------------------------------- |
| `primary`    | **Amber**       | Brand actions, active states, focus rings    |
| `danger`     | **Red**         | Destructive actions, validation errors       |
| `gray`       | **Zinc**        | Neutral surfaces, borders, disabled states   |
| `info`       | **Blue**        | Informational badges, callouts               |
| `success`    | **Green**       | Positive feedback, completion states         |
| `warning`    | **Amber**       | Caution alerts, attention-needed states      |

> **Customisation:** Override any alias via `FilamentColor::register()` in a service provider. Pass a named palette constant (e.g. `Color::Indigo`) or a hex string to auto-generate a palette.

### 2.2 Shade Scale

Every palette provides **11 shades**: `50`, `100`, `200`, `300`, `400`, `500`, `600`, `700`, `800`, `900`, `950`.

These are exposed as CSS custom properties:

```css
--primary-50  … --primary-950
--danger-50   … --danger-950
--gray-50     … --gray-950
--info-50     … --info-950
--success-50  … --success-950
--warning-50  … --warning-950
```

### 2.3 Available Named Palettes (26 total)

#### Neutral Palettes

| Name         | 50 (Lightest)                    | 500 (Midtone)                    | 950 (Darkest)                    |
| ------------ | -------------------------------- | -------------------------------- | -------------------------------- |
| **Slate**    | `oklch(0.984 0.003 247.858)`    | `oklch(0.554 0.046 257.417)`    | `oklch(0.129 0.042 264.695)`    |
| **Gray**     | `oklch(0.985 0.002 247.839)`    | `oklch(0.551 0.027 264.364)`    | `oklch(0.13 0.028 261.692)`     |
| **Zinc** ★   | `oklch(0.985 0 0)`              | `oklch(0.552 0.016 285.938)`    | `oklch(0.141 0.005 285.823)`    |
| **Neutral**  | `oklch(0.985 0 0)`              | `oklch(0.556 0 0)`              | `oklch(0.145 0 0)`              |
| **Stone**    | `oklch(0.985 0.001 106.423)`    | `oklch(0.553 0.013 58.071)`     | `oklch(0.147 0.004 49.25)`      |
| **Mauve**    | `oklch(0.985 0 0)`              | `oklch(0.542 0.034 322.5)`      | `oklch(0.145 0.008 326)`        |
| **Olive**    | `oklch(0.988 0.003 106.5)`      | `oklch(0.58 0.031 107.3)`       | `oklch(0.153 0.006 107.1)`      |
| **Mist**     | `oklch(0.987 0.002 197.1)`      | `oklch(0.56 0.021 213.5)`       | `oklch(0.148 0.004 228.8)`      |
| **Taupe**    | `oklch(0.986 0.002 67.8)`       | `oklch(0.547 0.021 43.1)`       | `oklch(0.147 0.004 49.3)`       |

> ★ = Filament's default `gray` alias

#### Chromatic Palettes

| Name         | 50                               | 500                              | 950                              |
| ------------ | -------------------------------- | -------------------------------- | -------------------------------- |
| **Red**      | `oklch(0.971 0.013 17.38)`      | `oklch(0.637 0.237 25.331)`     | `oklch(0.258 0.092 26.042)`     |
| **Orange**   | `oklch(0.98 0.016 73.684)`      | `oklch(0.705 0.213 47.604)`     | `oklch(0.266 0.079 36.259)`     |
| **Amber** ★  | `oklch(0.987 0.022 95.277)`     | `oklch(0.769 0.188 70.08)`      | `oklch(0.279 0.077 45.635)`     |
| **Yellow**   | `oklch(0.987 0.026 102.212)`    | `oklch(0.795 0.184 86.047)`     | `oklch(0.286 0.066 53.813)`     |
| **Lime**     | `oklch(0.986 0.031 120.757)`    | `oklch(0.768 0.233 130.85)`     | `oklch(0.274 0.072 132.109)`    |
| **Green** ★  | `oklch(0.982 0.018 155.826)`    | `oklch(0.723 0.219 149.579)`    | `oklch(0.266 0.065 152.934)`    |
| **Emerald**  | `oklch(0.979 0.021 166.113)`    | `oklch(0.696 0.17 162.48)`      | `oklch(0.262 0.051 172.552)`    |
| **Teal**     | `oklch(0.984 0.014 180.72)`     | `oklch(0.704 0.14 182.503)`     | `oklch(0.277 0.046 192.524)`    |
| **Cyan**     | `oklch(0.984 0.019 200.873)`    | `oklch(0.715 0.143 215.221)`    | `oklch(0.302 0.056 229.695)`    |
| **Sky**      | `oklch(0.977 0.013 236.62)`     | `oklch(0.685 0.169 237.323)`    | `oklch(0.293 0.066 243.157)`    |
| **Blue** ★   | `oklch(0.97 0.014 254.604)`     | `oklch(0.623 0.214 259.815)`    | `oklch(0.282 0.091 267.935)`    |
| **Indigo**   | `oklch(0.962 0.018 272.314)`    | `oklch(0.585 0.233 277.117)`    | `oklch(0.257 0.09 281.288)`     |
| **Violet**   | `oklch(0.969 0.016 293.756)`    | `oklch(0.606 0.25 292.717)`     | `oklch(0.283 0.141 291.089)`    |
| **Purple**   | `oklch(0.977 0.014 308.299)`    | `oklch(0.627 0.265 303.9)`      | `oklch(0.291 0.149 302.717)`    |
| **Fuchsia**  | `oklch(0.977 0.017 320.058)`    | `oklch(0.667 0.295 322.15)`     | `oklch(0.293 0.136 325.661)`    |
| **Pink**     | `oklch(0.971 0.014 343.198)`    | `oklch(0.656 0.241 354.308)`    | `oklch(0.284 0.109 3.907)`      |
| **Rose**     | `oklch(0.969 0.015 12.422)`     | `oklch(0.645 0.246 16.439)`     | `oklch(0.271 0.105 12.094)`     |

> ★ = used as a semantic default

### 2.4 Light / Dark Mode Variants

Filament uses the Tailwind `dark:` variant scoped via `&:where(.dark, .dark *)`. Key surface patterns:

| Token                         | Light Mode                    | Dark Mode                        |
| ----------------------------- | ----------------------------- | -------------------------------- |
| **Page background**           | `bg-gray-50`                  | `bg-gray-950` (implied)          |
| **Card / Section surface**    | `bg-white`                    | `bg-gray-900`                    |
| **Secondary surface**         | `bg-gray-50`                  | `bg-white/5`                     |
| **Border**                    | `ring-gray-950/5`             | `ring-white/10`                  |
| **Input border**              | `ring-gray-950/10`            | `ring-white/20`                  |
| **Overlay (modal backdrop)**  | `bg-gray-950/50`              | `bg-gray-950/75`                 |
| **Primary text**              | `text-gray-950`               | `text-white`                     |
| **Secondary text**            | `text-gray-500`               | `text-gray-400`                  |
| **Muted icon**                | `text-gray-400`               | `text-gray-500`                  |

### 2.5 Usage Rules

- **Semantic colors over raw palettes**: Always use `primary`, `danger`, `success`, `warning`, `info`, or `gray` aliases in component code — never reference `Red` or `Blue` directly. This ensures theme portability.
- **Shade 500/600 for high-contrast foregrounds**, shade 50/100 for tinted backgrounds, shade 400 for muted accents.
- **Dark mode inversion**: Where light mode uses shade 600, dark mode typically uses shade 500 or 400. Background shades invert similarly (light uses 50 → dark uses 400/10).
- **Color-mix for tinted backgrounds**: Use `color-mix(in oklab, …)` for subtle tinted callout backgrounds and overlay effects.

---

## 3. Typography

### 3.1 Font Families

| Role        | CSS Variable              | Stack                                                                                                                          |
| ----------- | ------------------------- | ------------------------------------------------------------------------------------------------------------------------------ |
| **Sans**    | `--font-sans`             | `var(--font-family)`, `ui-sans-serif`, `system-ui`, `sans-serif`, `Apple Color Emoji`, `Segoe UI Emoji`, `Segoe UI Symbol`, `Noto Color Emoji` |
| **Mono**    | `--font-mono`             | `var(--mono-font-family)`, `ui-monospace`, `SFMono-Regular`, `Menlo`, `Monaco`, `Consolas`, `Liberation Mono`, `Courier New`, `monospace` |
| **Serif**   | `--font-serif`            | `var(--serif-font-family)`, `ui-serif`, `Georgia`, `Cambria`, `Times New Roman`, `Times`, `serif`                             |

> **Customisation:** Set `--font-family`, `--mono-font-family`, or `--serif-font-family` CSS variables in your theme to swap in a custom font (e.g. Inter, Outfit).

### 3.2 Type Scale (Prose / Content)

| Level   | Size Variable      | Actual Size  | Weight           | Line Height          | Letter Spacing |
| ------- | ------------------ | ------------ | ---------------- | -------------------- | -------------- |
| **H1**  | `--text-3xl`       | 1.875rem     | `bold` (700)     | `calc(36 / 30)`      | `-0.025em`     |
| **H2**  | `--text-2xl`       | 1.5rem       | `bold` (700)     | `calc(32 / 24)`      | `-0.025em`     |
| **H3**  | `--text-xl`        | 1.25rem      | `bold` (700)     | `calc(28 / 20)`      | —              |
| **H4**  | `--text-lg`        | 1.125rem     | `bold` (700)     | `calc(28 / 18)`      | —              |
| **H5**  | `--text-base`      | 1rem         | `bold` (700)     | `calc(24 / 16)`      | —              |
| **H6**  | `--text-sm`        | 0.875rem     | `bold` (700)     | `calc(20 / 14)`      | —              |
| **Body**| `--text-sm`        | 0.875rem     | `normal` (400)   | `1.5`                | —              |
| **Lead**| `--text-base`      | 1rem         | `normal` (400)   | `1.5`                | —              |

### 3.3 UI Type Tokens

| Context                  | Size Class | Weight            |
| ------------------------ | ---------- | ----------------- |
| **Modal heading**        | `text-base`| `font-semibold`   |
| **Section heading**      | `text-base`| `font-semibold`   |
| **Callout heading**      | `text-sm`  | `font-medium`     |
| **Button label**         | `text-sm`  | `font-medium`     |
| **Badge label**          | `text-xs`  | `font-medium`     |
| **Input text**           | `text-sm`  | `normal`          |
| **Input label/helper**   | `text-sm`  | —                 |
| **Description text**     | `text-sm`  | `normal`          |
| **Pagination label**     | `text-sm`  | `font-semibold`   |
| **Tab label**            | `text-sm`  | `font-medium`     |
| **Link**                 | varies     | `font-medium`     |
| **Fieldset legend**      | `text-sm`  | `font-medium`     |

### 3.4 Fallback Fonts

All font stacks use system UI fonts as fallbacks (see §3.1). No external font CDN dependency by default.

---

## 4. Spacing & Layout

### 4.1 Base Spacing Unit

Filament uses the **Tailwind spacing scale** (`--spacing` variable × multiplier). The base unit is **4px** (`0.25rem`).

### 4.2 Spacing Scale

| Token   | Value     | Tailwind Class | Common Usage                             |
| ------- | --------- | -------------- | ---------------------------------------- |
| **xs**  | 4px       | `p-1` / `gap-1`   | Icon gap in xs buttons                   |
| **sm**  | 8px       | `p-2` / `gap-2`   | Compact section padding, badge padding   |
| **md**  | 12px      | `p-3` / `gap-3`   | Input wrapper padding, action gaps       |
| **lg**  | 16px      | `p-4` / `gap-4`   | Content padding, card content gap        |
| **xl**  | 24px      | `p-6` / `gap-6`   | Section/modal padding (standard)         |
| **2xl** | 48px      | `py-12`            | Empty state vertical padding             |

### 4.3 Component-Specific Spacing

| Component              | Padding                           | Gap                     |
| ---------------------- | --------------------------------- | ----------------------- |
| **Section (standard)** | `p-6` (content), `px-6 py-4` (header/footer) | —            |
| **Section (compact)**  | `p-4` (content), `px-4 py-2.5` (header) | —                |
| **Modal content**      | `px-6 py-6`                       | `gap-y-4` (between items) |
| **Modal header**       | `px-6 pt-6`                       | —                       |
| **Tabs (contained)**   | `px-3 py-2.5`                     | `gap-x-1`              |
| **Tabs (standalone)**  | `p-2`                             | `gap-x-1`              |
| **Callout**            | `p-4`                             | `gap-3`                |
| **Fieldset**           | `p-6` (contained)                 | —                       |
| **Empty state**        | `px-6 py-12`                      | —                       |

### 4.4 Grid / Breakpoints

Filament uses **responsive grid columns** driven by CSS custom properties (`--cols-default`, `--cols-sm`, etc.) and supports both **viewport breakpoints** and **container query breakpoints**.

| Breakpoint | Viewport Width | Container Width |
| ---------- | -------------- | --------------- |
| `sm`       | 640px          | `@sm` (24rem)   |
| `md`       | 768px          | `@md` (28rem)   |
| `lg`       | 1024px         | `@lg` (32rem)   |
| `xl`       | 1280px         | `@xl` (36rem)   |
| `2xl`      | 1536px         | `@2xl` (42rem)  |

**Modal max-width tokens:** `3xs`, `2xs`, `xs`, `sm`, `md`, `lg`, `xl`, `2xl`, `3xl`, `4xl`, `5xl`, `6xl`, `7xl`, `screen-sm`, `screen-md`, `screen-lg`, `screen-xl`, `screen-2xl`, `full`, `none`, `min`, `max`, `fit`, `prose`.

---

## 5. Border Radius & Elevation

### 5.1 Border Radius Tokens

| Token      | Tailwind Class      | Value       | Usage                                                  |
| ---------- | ------------------- | ----------- | ------------------------------------------------------ |
| **sm**     | `rounded-md`        | 0.375rem    | Badges, checkboxes                                     |
| **md**     | `rounded-lg`        | 0.5rem      | Buttons, inputs, icon buttons, tabs items, compact sections |
| **lg**     | `rounded-xl`        | 0.75rem     | Cards, sections, modals, callouts, empty states, tabs containers |
| **full**   | `rounded-full`      | 9999px      | Avatars (circular), toggle switches, icon backgrounds  |

### 5.2 Shadow / Elevation Levels

| Level             | Tailwind Class | Usage                                           |
| ----------------- | -------------- | ----------------------------------------------- |
| **None**          | `shadow-none`  | Button-group children                            |
| **sm** (Level 1)  | `shadow-sm`    | Buttons, inputs, cards/sections, badges, pagination, tabs |
| **xl** (Level 2)  | `shadow-xl`    | Modal windows (highest elevation)                |

### 5.3 Ring (Border) System

Filament uses `ring-1` instead of `border` for most component outlines:

| Surface Type     | Light                          | Dark                            |
| ---------------- | ------------------------------ | ------------------------------- |
| Card / Section   | `ring-1 ring-gray-950/5`      | `ring-1 ring-white/10`          |
| Input wrapper    | `ring-1 ring-gray-950/10`     | `ring-1 ring-white/20`          |
| Button (default) | `ring-1 ring-gray-950/10`     | `ring-1 ring-white/20`          |
| Button (colored) | No ring (solid bg)             | No ring (solid bg)              |
| Badge            | `ring-1 ring-gray-600/10 ring-inset` | `ring-1 ring-gray-400/20 ring-inset` |
| Callout (colored)| `ring-1 ring-color-600/20`    | `ring-1 ring-color-400/30`      |

---

## 6. Components

### 6.1 Buttons (`.fi-btn`)

#### Size Variants

| Size  | Class          | Padding          | Gap     | Text Size  |
| ----- | -------------- | ---------------- | ------- | ---------- |
| `xs`  | `.fi-size-xs`  | `px-2 py-1.5`   | `gap-1` | `text-xs`  |
| `sm`  | `.fi-size-sm`  | `px-2.5 py-1.5` | `gap-1` | `text-sm`  |
| `md`  | *(default)*    | `px-3 py-2`     | `gap-1.5` | `text-sm`|
| `lg`  | `.fi-size-lg`  | `px-3.5 py-2.5` | `gap-1.5` | `text-sm`|
| `xl`  | `.fi-size-xl`  | `px-4 py-3`     | `gap-1.5` | `text-sm`|

#### Style Variants

| Variant          | Light Appearance                                | Dark Appearance                                 |
| ---------------- | ----------------------------------------------- | ----------------------------------------------- |
| **Default**      | White bg, `text-gray-950`, ring border           | `bg-white/5`, `text-white`, ring border          |
| **Colored**      | Dynamic bg/text from color map                   | Dynamic bg/text from color map                   |
| **Outlined**     | Transparent bg, `text-gray-950`, ring border     | Transparent bg, `text-white`, ring border        |
| **Outlined+Color** | Transparent bg, colored text, colored ring     | Transparent bg, colored text, colored ring       |

#### States

| State          | Behavior                                          |
| -------------- | ------------------------------------------------- |
| **Default**    | Base styling                                       |
| **Hover**      | `hover:bg-gray-50` (default) or `hover:bg-{shade}` (colored) |
| **Focus**      | `focus-visible:ring-2` (primary-600 or color-500)  |
| **Disabled**   | `cursor-default opacity-70 pointer-events-none`    |
| **Processing** | `cursor-wait opacity-70`                           |

#### Button Groups (`.fi-btn-group`)

Buttons are laid out in a `grid grid-flow-col` with shared ring, `rounded-s-lg` on first, `rounded-e-lg` on last, separator shadow between items.

---

### 6.2 Icon Buttons (`.fi-icon-btn`)

| Size  | Dimensions   |
| ----- | ------------ |
| `xs`  | `size-7` (28px) |
| `sm`  | `size-8` (32px) |
| `md`  | `size-9` (36px) *(default)* |
| `lg`  | `size-10` (40px) |
| `xl`  | `size-11` (44px) |

- `rounded-lg`, no background, text-gray-500
- Hover: `text-gray-600` / dark `text-gray-400`
- Focus: `ring-2 ring-primary-600` / dark `ring-primary-500`
- Colored variant: uses `--text` / `--dark-text` custom properties

---

### 6.3 Badges (`.fi-badge`)

- **Shape:** `rounded-md`, `ring-1 ring-inset`
- **Default:** `bg-gray-50`, `text-gray-600`, `ring-gray-600/10`
- **Colored:** `bg-color-50`, dynamic text, `ring-color-600/10`
- **Dark default:** `bg-gray-400/10`, `text-gray-200`, `ring-gray-400/20`
- **Sizes:** Default (`min-w-6 px-2 py-1`), `xs` (`min-w-4 px-0.5 py-0 tracking-tighter`), `sm` (`min-w-5 px-1.5 py-0.5 tracking-tight`)

---

### 6.4 Forms & Inputs

#### Input (`.fi-input`)

- `text-sm`, `leading-6`, `text-gray-950` / dark `text-white`
- Placeholder: `text-gray-400` / dark `text-gray-500`
- Disabled: `text-gray-500` / dark `text-gray-400`
- Safari zoom fix: `text-base` on touch-capable Safari devices

#### Input Wrapper (`.fi-input-wrp`)

- `rounded-lg`, `bg-white`, `shadow-sm`, `ring-1 ring-gray-950/10`
- Dark: `bg-white/5`, `ring-white/20`
- Focus-within: `ring-2 ring-primary-600` / dark `ring-primary-500`
- Invalid: `ring-danger-600` / dark `ring-danger-500`
- Disabled: `bg-gray-50` / dark `bg-transparent`
- Prefix/suffix areas with `border-e` / `border-s` dividers

#### Checkbox (`.fi-checkbox-input`)

- `size-4`, `rounded`, `shadow-sm`, `ring-1 ring-gray-950/10`
- Checked: `bg-primary-600` / dark `bg-primary-500`, `ring-0`
- Focus: `ring-2 ring-primary-600`
- Invalid: Switches all primary references to `danger`
- Indeterminate: Same bg as checked with dash icon

#### Toggle (`.fi-toggle`)

- `h-6 w-11`, `rounded-full`, `bg-gray-200` / dark `bg-gray-700`
- On state: thumb translates `translate-x-5` (RTL: `-translate-x-5`)
- Colored: uses `--bg` / `--dark-bg` custom properties
- Transition: `duration-200 ease-in-out`

---

### 6.5 Cards / Sections (`.fi-section`)

| Variant            | Surface                                     | Radius       |
| ------------------ | ------------------------------------------- | ------------ |
| **Contained**      | `bg-white shadow-sm ring-1 ring-gray-950/5` | `rounded-xl` |
| **Compact**        | Same as contained                           | `rounded-lg` |
| **Secondary**      | `bg-gray-50` / dark `bg-white/5`            | `rounded-xl` |
| **Aside**          | 3-column grid (`grid-cols-3`), content in 2 cols | `rounded-xl` |
| **Not contained**  | No surface decoration                        | None         |
| **Divided**        | `divide-y divide-gray-200` / dark `divide-white/10` | — |
| **Collapsible**    | Collapse icon rotates 180°, content hidden   | —            |

---

### 6.6 Modals (`.fi-modal`)

- **Window:** `bg-white shadow-xl ring-1 ring-gray-950/5` / dark `bg-gray-900 ring-white/10`
- **Standard modal:** `rounded-xl`, centered, scales 95%→100% on enter
- **Slide-over:** Full height, translates in from start/end
- **Overlay:** `bg-gray-950/50` / dark `bg-gray-950/75`, 300ms fade
- **Width tokens:** `3xs` through `7xl`, `screen-*` variants, `full`, `fit`, `prose`
- **Sticky header:** `border-b border-gray-200`, sticks to top with z-10
- **Sticky footer:** `border-t border-gray-200`, sticks to bottom
- **Icon background:** `rounded-full bg-gray-100` / colored `bg-color-100`
- **Transitions:** `duration-300` for enter/leave

---

### 6.7 Navigation — Tabs (`.fi-tabs`)

- **Standalone:** `rounded-xl bg-white p-2 shadow-sm ring-1 ring-gray-950/5`
- **Contained:** `border-b border-gray-200 px-3 py-2.5`
- **Tab item:** `rounded-lg px-3 py-2`, `text-sm font-medium`
- **Active state:** `bg-gray-50`, label and icon switch to `text-primary-700` / dark `text-primary-400`
- **Hover:** `bg-gray-50` / dark `bg-white/5`
- **Vertical variant:** Column flex, `border-e` instead of `border-b`

---

### 6.8 Dropdown (`.fi-dropdown`)

- Panel has `shadow-sm ring-1 ring-gray-950/5` / dark `ring-white/10`
- Items use hover/focus states similar to tabs
- Supports header component, dividers, and nested item groups

---

### 6.9 Callout (`.fi-callout`)

- `rounded-xl`, `bg-white`, `ring-1 ring-gray-950/5`, `p-4`, `gap-3`
- Colored variant: tinted background via `color-mix(in oklab, white 90%, var(--color-400))`
- Dark colored: `color-mix(in oklab, var(--gray-900) 90%, var(--color-400))`

---

### 6.10 Empty State (`.fi-empty-state`)

- `px-6 py-12`, centered content, max-w-lg
- Icon: `rounded-full bg-gray-100 p-3` / colored `bg-color-100`
- Heading: `text-base font-semibold`, Description: `text-sm text-gray-500`
- **Compact variant:** Horizontal layout with `flex items-start gap-4`

---

### 6.11 Pagination (`.fi-pagination`)

- Container: `grid grid-cols-[1fr_auto_1fr]`
- Items: `rounded-lg bg-white shadow-sm ring-1 ring-gray-950/10`
- Active: `bg-gray-50`, label `text-primary-700` / dark `text-primary-400`
- Item buttons: `p-2`, labels `text-sm font-semibold`
- Responsive: Shows simple prev/next on small screens, full pagination on `@4xl` / `md`

---

### 6.12 Links (`.fi-link`)

- `font-medium`, `text-gray-700` / dark `text-gray-200`
- Hover/focus: `underline`, focus adds `outline-2 outline-current`
- Colored: uses `--text` / `--dark-text`
- Sizes: `xs` (`text-xs`), `sm` (`text-sm`), `md`/`lg`/`xl` (`text-sm`)
- Supports weight modifiers: `thin` → `black` (all Tailwind weights)

---

### 6.13 Avatars (`.fi-avatar`)

| Size    | Class        | Dimensions |
| ------- | ------------ | ---------- |
| `sm`    | `.fi-size-sm`| `size-6` (24px) |
| `md`    | *(default)*  | `size-8` (32px) |
| `lg`    | `.fi-size-lg`| `size-10` (40px) |

- Default shape: `rounded-md`
- Circular: `.fi-circular` → `rounded-full`
- `object-cover object-center`

---

### 6.14 Icons (`.fi-icon`)

| Size   | Class          | Dimensions |
| ------ | -------------- | ---------- |
| `xs`   | `.fi-size-xs`  | `size-3` (12px) |
| `sm`   | `.fi-size-sm`  | `size-4` (16px) |
| `md`   | `.fi-size-md`  | `size-5` (20px) *(default)* |
| `lg`   | `.fi-size-lg`  | `size-6` (24px) |
| `xl`   | `.fi-size-xl`  | `size-7` (28px) |
| `2xl`  | `.fi-size-2xl` | `size-8` (32px) |

- Filament uses **Heroicons** (outline style) by default
- SVGs inherit parent dimensions: `h-[inherit] w-[inherit]`

---

### 6.15 Loading Indicator & Loading Section

- Minimal CSS — delegates animation to the compiled JS/SVG loader
- Respects `prefers-reduced-motion` via the global base CSS rule

---

## 7. Accessibility

### 7.1 Contrast Ratios

Filament's `Color` class defines **WCAG 2.1 constants** and uses them in its automatic color-mapping algorithm:

| Constant                | Value | Standard                                |
| ----------------------- | ----- | --------------------------------------- |
| `WCAG_AA_TEXT`          | 4.5:1 | Normal text, AA conformance             |
| `WCAG_AA_LARGE_TEXT`    | 3.0:1 | Large text (≥18pt / ≥14pt bold), AA     |
| `WCAG_AA_NON_TEXT`      | 3.0:1 | UI components & graphical objects, AA   |
| `WCAG_AAA_TEXT`         | 7.0:1 | Normal text, AAA conformance            |
| `WCAG_AAA_LARGE_TEXT`   | 4.5:1 | Large text, AAA conformance             |

**The `ButtonComponentColorMap` resolver targets `WCAG_AA_TEXT` (4.5:1)** as its minimum contrast ratio when selecting text colors against button backgrounds. It probes from dark text shades (≥800) first, falls back to shade 50 (light text), and uses white (`oklch(1 0 0)`) as last resort.

### 7.2 Color Lightness Detection

The `Color::isLight()` method considers any OKLCH color with `lightness >= 0.65` to be "light". This threshold drives the automatic text-on-background selection for all colored components.

### 7.3 Focus States

| Component      | Focus Indicator                                         |
| -------------- | ------------------------------------------------------- |
| **Button**     | `focus-visible:ring-2` (color-dependent)                |
| **Icon Button**| `focus-visible:ring-2 ring-primary-600`                 |
| **Input**      | `focus-within:ring-2 ring-primary-600`                  |
| **Checkbox**   | `focus:ring-2 ring-primary-600`                         |
| **Toggle**     | `focus-visible:ring-2 ring-primary-600 ring-offset-1`   |
| **Link**       | `focus-visible:outline-2 outline-current` + underline   |
| **Tab item**   | `focus-visible:bg-gray-50`                              |
| **Pagination** | `focus-visible:ring-2 ring-primary-600 z-10`            |

All focus indicators use `focus-visible:` (not `focus:`) so they only appear on keyboard navigation.

### 7.4 Reduced Motion

Applied globally in the base CSS layer:

```css
@media (prefers-reduced-motion: reduce) {
    *, *::before, *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
        scroll-behavior: auto !important;
    }
}
```

> Uses `0.01ms` (not `0`) to keep `transitionend` / `animationend` events firing for Alpine.js `x-transition` compatibility.

### 7.5 Screen Reader Support

- `.fi-sr-only` maps to Tailwind's `sr-only` utility
- Disabled elements use `pointer-events-none` + `opacity-70` while remaining in the DOM
- Form validation scroll targets use `scroll-margin-top: 8rem` to avoid top-bar occlusion

---

## 8. Logo & Imagery

### 8.1 Logo

- Filament provides a dedicated `logo` Blade component slot in the panel layout
- The logo is rendered in the sidebar header and login page
- **Clear space:** No explicit clear-space tokens are defined — controlled by parent container padding

### 8.2 Avatars

- See §6.13 for sizing and shape rules
- Default shape is `rounded-md`; use `circular` for user profile avatars
- Always use `object-cover object-center` for user-uploaded images

### 8.3 Icons

- **Style:** Heroicons (outline) — consistent 24×24 viewBox, 1.5px stroke
- **Sizing:** 6 sizes from 12px to 32px (see §6.14)
- **Color:** Icons inherit text color from their parent or use explicit color utility classes
- Avoid mixing icon libraries; use Heroicons for consistency

---

## 9. Do's and Don'ts

### ✅ Do's

| Guideline | Example |
| --------- | ------- |
| Use **semantic color aliases** (`primary`, `danger`, etc.) | `->color('danger')` not `->color('red')` |
| Use **`rounded-xl`** for cards/sections, **`rounded-lg`** for inputs/buttons | Section: `rounded-xl`; Button: `rounded-lg` |
| Use **`ring-1`** for borders instead of `border` | `ring-1 ring-gray-950/5` |
| Use **`shadow-sm`** for standard elevation | Cards, inputs, buttons |
| Test **both light and dark modes** for every change | All tokens have light/dark pairs |
| Use **`focus-visible:`** for keyboard focus indicators | `focus-visible:ring-2` |
| Use **logical properties** (`ps-`, `pe-`, `ms-`, `me-`, `start`, `end`) for RTL | `@apply ps-3 pe-3` |
| Let the **color resolver** pick text colors on colored backgrounds | `ButtonComponentColorMap` handles contrast |
| Use **opacity modifiers** on rings for subtlety | `ring-gray-950/5`, `ring-white/20` |
| Honour **`prefers-reduced-motion`** — keep transitions | Global base layer handles this |

### ❌ Don'ts

| Anti-pattern | Why |
| ------------ | --- |
| Hardcode hex colors in component CSS | Breaks theme customisation; use CSS variables |
| Use `border` instead of `ring` on components | Inconsistent with the design system; `ring` doesn't affect layout |
| Use `focus:` instead of `focus-visible:` | Shows focus rings on mouse click, poor UX |
| Use `rounded-full` on rectangular containers | Reserved for avatars, toggles, and icon backgrounds |
| Mix icon libraries (Font Awesome, etc.) | Heroicons is the standard; mixing breaks visual consistency |
| Use `shadow-lg` or `shadow-2xl` on cards | Only modals use `shadow-xl`; everything else uses `shadow-sm` |
| Reference raw palette names in business logic | `Color::Red` is for registration, not component usage |
| Skip dark mode variants | Every surface, text, and border must have a `dark:` counterpart |
| Use physical properties (`pl-`, `pr-`, `ml-`, `mr-`) | Breaks RTL; use `ps-`, `pe-`, `ms-`, `me-` |
| Override `opacity` below `0.7` for disabled states | `0.7` is the system standard for disabled |

---

## Appendix: CSS Architecture

### Layer Order

```
base       → Global resets, reduced-motion, cursor: pointer
components → All .fi-* component styles (compiled + source)
utilities  → Color mapping utilities, prose styles, grid layouts
```

### Naming Convention

All Filament CSS classes use the `.fi-` prefix:

| Pattern                | Example                     |
| ---------------------- | --------------------------- |
| `.fi-{component}`      | `.fi-btn`, `.fi-modal`      |
| `.fi-{component}-{part}` | `.fi-modal-header`, `.fi-btn-badge-ctn` |
| `.fi-size-{size}`      | `.fi-size-xs`, `.fi-size-lg`|
| `.fi-color-{name}`     | `.fi-color-danger`, `.fi-color-primary` |
| `.fi-{state}`          | `.fi-active`, `.fi-disabled`, `.fi-collapsed` |
| `.fi-{variant}`        | `.fi-outlined`, `.fi-compact`, `.fi-divided` |

### Transition Defaults

- **Duration:** `75ms` for most interactions (hover, focus)
- **Duration:** `200ms` for toggle switches, tab transitions
- **Duration:** `300ms` for modal enter/leave
- **Easing:** Default Tailwind ease (CSS `ease`) unless specified (`ease-in-out` for toggles)
