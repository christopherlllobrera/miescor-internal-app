@props([
    'minimal' => false,
])

<header class="fixed w-full top-0 z-50 transition-transform duration-300 bg-white shadow-md">
    <nav class="px-4 md:px-6 py-3 relative">
        <div class="flex justify-between items-center mx-auto max-w-screen-4xl relative">
            {{-- Left: Logo --}}
            <a href="/" rel="noreferrer" class="flex items-center z-10">
                <img src="{{ URL('images/logo/miescor_light_mode.png') }}" class="h-10 sm:h-12 lg:h-14" alt="Miescor Logo" />
            </a>

            @unless($minimal)
                {{-- Center: Navigation Links (Desktop) --}}
                <div class="absolute left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2 hidden lg:block">
                    <ul class="flex items-center space-x-1 md:space-x-2 lg:space-x-8 font-medium">
                        <li><a href="/#departments" wire:navigate rel="noreferrer" class="inline-block px-3 py-2 text-gray-900 hover:text-orange-600 transition-colors">Departments</a></li>
                        <li><a href="/blog" wire:navigate rel="noreferrer" class="inline-block px-3 py-2 text-gray-900 hover:text-orange-600 transition-colors">News</a></li>
                        <li><a href="/#events" wire:navigate rel="noreferrer" class="inline-block px-3 py-2 text-gray-900 hover:text-orange-600 transition-colors">Events</a></li>
                    </ul>
                </div>

                {{-- Right: Button and Mobile Menu Button --}}
                <div class="flex items-center space-x-3 z-10">
                    {{-- Integrated App Button (Desktop only) --}}
                    <a href="/services" wire:navigate rel="noreferrer" class="hidden lg:inline-block px-4 py-2 bg-orange-600 text-white font-medium rounded-lg hover:bg-orange-700 transition-colors shadow-sm">
                        App
                    </a>

                    {{-- Mobile Menu Button --}}
                    <button id="mobile-menu-button" type="button" class="lg:hidden inline-flex items-center p-2 text-gray-700 rounded-lg hover:bg-gray-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-orange-600" aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <svg id="menu-icon" class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <svg id="close-icon" class="hidden w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
            @endunless
        </div>

        @unless($minimal)
            {{-- Mobile Menu --}}
            <div id="mobile-menu" class="hidden lg:hidden w-full absolute left-0 bg-white shadow-lg z-20">
                <div class="px-4 py-3 space-y-1 max-w-screen-4xl mx-auto">
                    <a href="/#departments" wire:navigate rel="noreferrer" class="block py-3 px-4 text-gray-900 hover:text-white hover:bg-orange-600 focus:bg-orange-600 rounded-lg font-medium">Departments</a>
                    <a href="/blog" wire:navigate rel="noreferrer" class="block py-3 px-4 text-gray-900 hover:text-white hover:bg-orange-600 focus:bg-orange-600 rounded-lg font-medium">News</a>
                    <a href="/#events" wire:navigate rel="noreferrer" class="block py-3 px-4 text-gray-900 hover:text-white hover:bg-orange-600 focus:bg-orange-600 rounded-lg font-medium">Events</a>
                    <a href="/integrated-app" wire:navigate rel="noreferrer" class="block py-3 px-4 text-gray-900 hover:text-white hover:bg-orange-600 focus:bg-orange-600 rounded-lg font-medium">App</a>
                </div>
            </div>
        @endunless
    </nav>
</header>

@unless($minimal)
<script>
    // Hide header on scroll down, show on scroll up
    let lastScroll = 0;
    const navbar = document.querySelector('header');
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    const menuIcon = document.getElementById('menu-icon');
    const closeIcon = document.getElementById('close-icon');

    // Mobile menu toggle functionality
    mobileMenuButton.addEventListener('click', () => {
        const expanded = mobileMenuButton.getAttribute('aria-expanded') === 'true';
        mobileMenuButton.setAttribute('aria-expanded', !expanded);
        mobileMenu.classList.toggle('hidden');
        menuIcon.classList.toggle('hidden');
        closeIcon.classList.toggle('hidden');

        // Prevent hiding header when menu is open
        if (!expanded) {
            navbar.classList.remove('-translate-y-full');
        }
    });

    window.addEventListener('scroll', () => {
        // Don't hide header if mobile menu is open
        if (mobileMenuButton.getAttribute('aria-expanded') === 'true') {
            return;
        }

        const currentScroll = window.pageYOffset;

        if (currentScroll <= 0) {
            navbar.classList.remove('-translate-y-full');
            return;
        }

        if (currentScroll > lastScroll && !navbar.classList.contains('-translate-y-full') && currentScroll > 100) {
            navbar.classList.add('-translate-y-full');
        } else if (currentScroll < lastScroll && navbar.classList.contains('-translate-y-full')) {
            navbar.classList.remove('-translate-y-full');
        }

        lastScroll = currentScroll;
    });

    // Close mobile menu on window resize if screen becomes large
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 1024 && mobileMenuButton.getAttribute('aria-expanded') === 'true') {
            mobileMenu.classList.add('hidden');
            menuIcon.classList.remove('hidden');
            closeIcon.classList.add('hidden');
            mobileMenuButton.setAttribute('aria-expanded', 'false');
        }
    });
</script>
@endunless
