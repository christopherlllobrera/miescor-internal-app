<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ __('filament-panels::layout.direction') ?? 'ltr' }}"
>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('code', 'Error') — @yield('title', 'Error') | {{ config('app.name', 'IAM Fleet') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <style>
        /*
         * =========================================================
         * Filament v5 Design System — Error Pages
         * =========================================================
         *
         * Color space : OKLCH
         * Gray alias  : Zinc
         * Primary     : Amber
         * Danger      : Red
         * Spacing grid: 4px base
         * Border model: ring (not border)
         * =========================================================
         */

        :root {
            color-scheme: light;

            /* ── Gray (Zinc) ──────────────────────────────── */
            --gray-50:  oklch(0.985 0 0);
            --gray-100: oklch(0.967 0.001 286.375);
            --gray-200: oklch(0.92 0.004 286.32);
            --gray-300: oklch(0.871 0.006 286.286);
            --gray-400: oklch(0.705 0.015 286.067);
            --gray-500: oklch(0.552 0.016 285.938);
            --gray-600: oklch(0.442 0.017 285.786);
            --gray-700: oklch(0.37 0.013 285.805);
            --gray-800: oklch(0.274 0.006 286.033);
            --gray-900: oklch(0.21 0.006 285.885);
            --gray-950: oklch(0.141 0.005 285.823);

            /* ── Danger (Red) ─────────────────────────────── */
            --danger-50:  oklch(0.971 0.013 17.38);
            --danger-100: oklch(0.936 0.032 17.717);
            --danger-200: oklch(0.885 0.062 18.334);
            --danger-400: oklch(0.704 0.191 22.216);
            --danger-500: oklch(0.637 0.237 25.331);
            --danger-600: oklch(0.577 0.245 27.325);
            --danger-950: oklch(0.258 0.092 26.042);

            /* ── Primary (Amber) ──────────────────────────── */
            --primary-400: oklch(0.828 0.189 84.429);
            --primary-500: oklch(0.769 0.188 70.08);
            --primary-600: oklch(0.666 0.179 58.318);
            --primary-700: oklch(0.555 0.163 48.998);
            --primary-900: oklch(0.414 0.112 45.904);

            /* ── Typography ───────────────────────────────── */
            --font-sans: 'Inter', ui-sans-serif, system-ui, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji';
            --font-mono: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, 'Liberation Mono', 'Courier New', monospace;

            /* ── Spacing (4px base) ───────────────────────── */
            --space-1:  0.25rem;  /*  4px */
            --space-2:  0.5rem;   /*  8px */
            --space-3:  0.75rem;  /* 12px */
            --space-4:  1rem;     /* 16px */
            --space-5:  1.25rem;  /* 20px */
            --space-6:  1.5rem;   /* 24px */
            --space-8:  2rem;     /* 32px */
            --space-12: 3rem;     /* 48px */

            /* ── Radii ────────────────────────────────────── */
            --radius-md:   0.375rem;  /* badges */
            --radius-lg:   0.5rem;    /* buttons, inputs */
            --radius-xl:   0.75rem;   /* cards, sections */
            --radius-full: 9999px;    /* pills, dots */

            /* ── Elevation ────────────────────────────────── */
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);

            /* ── Transition ───────────────────────────────── */
            --duration-fast: 75ms;
        }

        .dark {
            color-scheme: dark;
        }

        /* ── Reduced Motion (Filament base layer) ───────── */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
                scroll-behavior: auto !important;
            }
        }

        /* ── Reset ──────────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        button:not(:disabled), [role='button']:not(:disabled) { cursor: pointer; }

        /* ── Body ───────────────────────────────────────── */
        body {
            font-family: var(--font-sans);
            font-size: 0.875rem;          /* text-sm: 14px */
            line-height: 1.5;
            color: var(--gray-950);
            background-color: var(--gray-50);
            min-height: 100dvh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: var(--space-6);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .dark body {
            color: white;
            background-color: var(--gray-950);
        }

        /* ── Wrapper ────────────────────────────────────── */
        .fi-error-wrapper {
            width: 100%;
            max-width: 28rem; /* max-w-md */
            margin: 0 auto;
        }

        /* ── Card (fi-section pattern) ──────────────────── */
        .fi-error-card {
            background-color: white;
            border-radius: var(--radius-xl);     /* rounded-xl */
            box-shadow: var(--shadow-sm);         /* shadow-sm */
            outline: 1px solid oklch(0.141 0.005 285.823 / 0.05);  /* ring-1 ring-gray-950/5 */
            outline-offset: -1px;
            padding: var(--space-6);
            position: relative;
        }

        .dark .fi-error-card {
            background-color: var(--gray-900);    /* dark:bg-gray-900 */
            outline-color: oklch(1 0 0 / 0.1);   /* dark:ring-white/10 */
        }

        /* ── Header ─────────────────────────────────────── */
        .fi-error-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: var(--space-4);
            margin-bottom: var(--space-6);
            border-bottom: 1px solid var(--gray-200);
        }

        .dark .fi-error-header {
            border-bottom-color: oklch(1 0 0 / 0.1); /* dark:border-white/10 */
        }

        /* ── Badge (fi-badge pattern) ───────────────────── */
        .fi-error-badge {
            display: inline-flex;
            align-items: center;
            gap: var(--space-1);
            background-color: var(--danger-50);
            color: var(--danger-600);
            font-size: 0.75rem;           /* text-xs */
            font-weight: 500;             /* font-medium */
            line-height: 1;
            padding: 0.25rem 0.5rem;      /* py-1 px-2 */
            border-radius: var(--radius-md); /* rounded-md */
            outline: 1px solid oklch(0.577 0.245 27.325 / 0.1); /* ring-1 ring-inset ring-danger-600/10 */
            outline-offset: -1px;
            font-family: var(--font-mono);
            letter-spacing: -0.01em;
        }

        .dark .fi-error-badge {
            background-color: oklch(0.704 0.191 22.216 / 0.1); /* dark:bg-danger-400/10 */
            color: oklch(0.885 0.062 18.334);                  /* dark:text-danger-200 */
            outline-color: oklch(0.704 0.191 22.216 / 0.3);    /* dark:ring-danger-400/30 */
        }

        .fi-error-badge-dot {
            width: 6px;
            height: 6px;
            border-radius: var(--radius-full);
            background-color: var(--danger-500);
            flex-shrink: 0;
        }

        /* ── System Tag ─────────────────────────────────── */
        .fi-system-tag {
            font-family: var(--font-mono);
            font-size: 0.6875rem;         /* ~11px */
            color: var(--gray-400);
            letter-spacing: 0.05em;
            text-transform: uppercase;
            font-weight: 500;
        }

        .dark .fi-system-tag {
            color: var(--gray-500);       /* dark:text-gray-500 */
        }

        /* ── Error Code Display ─────────────────────────── */
        .fi-error-code {
            font-size: 3rem;              /* text-5xl equivalent */
            font-weight: 700;             /* font-bold */
            line-height: 1;
            letter-spacing: -0.025em;
            color: var(--gray-950);
            margin-bottom: var(--space-2);
        }

        .dark .fi-error-code {
            color: white;                 /* dark:text-white */
        }

        /* ── Title (fi-section-header-heading pattern) ──── */
        .fi-error-title {
            font-size: 1rem;              /* text-base */
            font-weight: 600;             /* font-semibold */
            line-height: 1.5;             /* leading-6 */
            color: var(--gray-950);
            margin: 0;
            margin-bottom: var(--space-2);
        }

        .dark .fi-error-title {
            color: white;
        }

        /* ── Description (fi-section-header-description) ── */
        .fi-error-message {
            font-size: 0.875rem;          /* text-sm */
            line-height: 1.5;
            color: var(--gray-500);
            font-weight: 400;
            margin-bottom: var(--space-6);
        }

        .dark .fi-error-message {
            color: var(--gray-400);       /* dark:text-gray-400 */
        }

        /* ── Actions ────────────────────────────────────── */
        .fi-error-actions {
            display: flex;
            align-items: center;
            gap: var(--space-2);
            flex-wrap: wrap;
        }

        /* ── Primary Button (fi-btn + fi-color) ─────────── */
        .fi-btn-primary {
            display: inline-grid;
            grid-auto-flow: column;
            align-items: center;
            justify-content: center;
            gap: var(--space-2);
            background-color: var(--primary-600);
            color: white;
            font-family: var(--font-sans);
            font-size: 0.875rem;          /* text-sm */
            font-weight: 500;             /* font-medium */
            line-height: 1.25rem;
            padding: 0.5rem 0.75rem;      /* py-2 px-3 */
            border-radius: var(--radius-lg); /* rounded-lg */
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: background-color var(--duration-fast) ease,
                        box-shadow var(--duration-fast) ease;
            outline: none;
        }

        .fi-btn-primary:hover {
            background-color: var(--primary-700);
        }

        .fi-btn-primary:focus-visible {
            box-shadow: 0 0 0 2px white, 0 0 0 4px oklch(0.769 0.188 70.08 / 0.5); /* focus-visible:ring-2 ring-primary-500/50 */
        }

        .dark .fi-btn-primary {
            background-color: var(--primary-500);
            color: var(--primary-900);
        }

        .dark .fi-btn-primary:hover {
            background-color: var(--primary-400);
        }

        .dark .fi-btn-primary:focus-visible {
            box-shadow: 0 0 0 2px var(--gray-900), 0 0 0 4px oklch(0.828 0.189 84.429 / 0.5); /* ring-primary-400/50 */
        }

        /* ── Ghost Button (fi-btn default variant) ──────── */
        .fi-btn-ghost {
            display: inline-grid;
            grid-auto-flow: column;
            align-items: center;
            justify-content: center;
            gap: var(--space-2);
            background-color: white;
            color: var(--gray-950);
            font-family: var(--font-sans);
            font-size: 0.875rem;          /* text-sm */
            font-weight: 500;             /* font-medium */
            line-height: 1.25rem;
            padding: 0.5rem 0.75rem;      /* py-2 px-3 */
            border-radius: var(--radius-lg); /* rounded-lg */
            text-decoration: none;
            border: none;
            cursor: pointer;
            outline: 1px solid oklch(0.141 0.005 285.823 / 0.1); /* ring-1 ring-gray-950/10 */
            outline-offset: -1px;
            box-shadow: var(--shadow-sm);
            transition: background-color var(--duration-fast) ease,
                        box-shadow var(--duration-fast) ease;
        }

        .fi-btn-ghost:hover {
            background-color: var(--gray-50);  /* hover:bg-gray-50 */
        }

        .fi-btn-ghost:focus-visible {
            outline: 2px solid var(--primary-600);
            outline-offset: -2px;
        }

        .dark .fi-btn-ghost {
            background-color: oklch(1 0 0 / 0.05); /* dark:bg-white/5 */
            color: white;
            outline-color: oklch(1 0 0 / 0.2);     /* dark:ring-white/20 */
        }

        .dark .fi-btn-ghost:hover {
            background-color: oklch(1 0 0 / 0.1);  /* dark:hover:bg-white/10 */
        }

        .dark .fi-btn-ghost:focus-visible {
            outline-color: var(--primary-500);
        }

        /* ── Icon inside buttons ────────────────────────── */
        .fi-btn-icon {
            width: 1.25rem;               /* size-5 */
            height: 1.25rem;
            flex-shrink: 0;
        }

        /* ── Footer ─────────────────────────────────────── */
        .fi-error-footer {
            margin-top: var(--space-5);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;           /* text-xs */
            color: var(--gray-400);
            padding: 0 var(--space-1);
            letter-spacing: 0.025em;
        }

        .dark .fi-error-footer {
            color: var(--gray-500);
        }

        /* ── Decorative icon background (fi-empty-state pattern) ── */
        .fi-error-icon-bg {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 3rem;                  /* size-12 */
            height: 3rem;
            border-radius: var(--radius-full); /* rounded-full */
            background-color: var(--danger-50);
            margin-bottom: var(--space-4);
            flex-shrink: 0;
        }

        .dark .fi-error-icon-bg {
            background-color: oklch(0.637 0.237 25.331 / 0.2); /* dark:bg-danger-500/20 */
        }

        .fi-error-icon-bg svg {
            width: 1.5rem;                /* size-6 */
            height: 1.5rem;
            color: var(--danger-500);
        }

        .dark .fi-error-icon-bg svg {
            color: var(--danger-400);     /* dark:text-danger-400 */
        }

        /* ── Dark mode auto-detect ──────────────────────── */
        @media (prefers-color-scheme: dark) {
            html:not(.light) {
                color-scheme: dark;
            }

            html:not(.light) body {
                color: white;
                background-color: var(--gray-950);
            }

            html:not(.light) .fi-error-card {
                background-color: var(--gray-900);
                outline-color: oklch(1 0 0 / 0.1);
            }

            html:not(.light) .fi-error-header {
                border-bottom-color: oklch(1 0 0 / 0.1);
            }

            html:not(.light) .fi-error-badge {
                background-color: oklch(0.704 0.191 22.216 / 0.1);
                color: oklch(0.885 0.062 18.334);
                outline-color: oklch(0.704 0.191 22.216 / 0.3);
            }

            html:not(.light) .fi-system-tag {
                color: var(--gray-500);
            }

            html:not(.light) .fi-error-code {
                color: white;
            }

            html:not(.light) .fi-error-title {
                color: white;
            }

            html:not(.light) .fi-error-message {
                color: var(--gray-400);
            }

            html:not(.light) .fi-btn-primary {
                background-color: var(--primary-500);
                color: var(--primary-900);
            }

            html:not(.light) .fi-btn-primary:hover {
                background-color: var(--primary-400);
            }

            html:not(.light) .fi-btn-primary:focus-visible {
                box-shadow: 0 0 0 2px var(--gray-900), 0 0 0 4px oklch(0.828 0.189 84.429 / 0.5);
            }

            html:not(.light) .fi-btn-ghost {
                background-color: oklch(1 0 0 / 0.05);
                color: white;
                outline-color: oklch(1 0 0 / 0.2);
            }

            html:not(.light) .fi-btn-ghost:hover {
                background-color: oklch(1 0 0 / 0.1);
            }

            html:not(.light) .fi-btn-ghost:focus-visible {
                outline-color: var(--primary-500);
            }

            html:not(.light) .fi-error-footer {
                color: var(--gray-500);
            }

            html:not(.light) .fi-error-icon-bg {
                background-color: oklch(0.637 0.237 25.331 / 0.2);
            }

            html:not(.light) .fi-error-icon-bg svg {
                color: oklch(0.704 0.191 22.216);
            }
        }
    </style>
</head>
<body>
    <div class="fi-error-wrapper">
        <div class="fi-error-card">
            {{-- Header --}}
            <div class="fi-error-header">
                <div class="fi-error-badge">
                    <span class="fi-error-badge-dot"></span>
                    <span>HTTP @yield('code', '500')</span>
                </div>
                <div class="fi-system-tag">
                    MLI · FLEET
                </div>
            </div>

            {{-- Icon --}}
            <div class="fi-error-icon-bg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                </svg>
            </div>

            {{-- Error Code --}}
            <div class="fi-error-code">
                @yield('code', '500')
            </div>

            {{-- Title --}}
            <h1 class="fi-error-title">
                @yield('title', 'Error')
            </h1>

            {{-- Description --}}
            <p class="fi-error-message">
                @yield('message', 'An unexpected error occurred.')
            </p>

            {{-- Actions --}}
            <div class="fi-error-actions">
                <a href="{{ url('/services') }}" class="fi-btn-primary">
                    {{-- Heroicon: home (outline, 20×20) --}}
                    <svg class="fi-btn-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.707 2.293a1 1 0 0 0-1.414 0l-7 7a1 1 0 0 0 1.414 1.414L4 10.414V17a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-2a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-6.586l.293.293a1 1 0 0 0 1.414-1.414l-7-7Z" />
                    </svg>
                    Dashboard
                </a>

                <a
                    href="{{ url()->previous() !== url()->current() ? url()->previous() : url('/fleet') }}"
                    class="fi-btn-ghost"
                    onclick="if (history.length > 1) { history.back(); return false; }"
                >
                    {{-- Heroicon: arrow-left (outline, 20×20) --}}
                    <svg class="fi-btn-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.793 2.232a.75.75 0 0 1-.025 1.06L3.622 7.25h10.003a5.375 5.375 0 0 1 0 10.75H10.75a.75.75 0 0 1 0-1.5h2.875a3.875 3.875 0 0 0 0-7.75H3.622l4.146 3.957a.75.75 0 0 1-1.036 1.085l-5.5-5.25a.75.75 0 0 1 0-1.085l5.5-5.25a.75.75 0 0 1 1.06.025Z" />
                    </svg>
                    Go back
                </a>
            </div>
        </div>

        {{-- Footer --}}
        <div class="fi-error-footer">
            <span>&copy; {{ date('Y') }} MLI · All rights reserved.</span>
        </div>
    </div>
</body>
</html>
