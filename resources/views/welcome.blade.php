<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
            <div class="relative min-h-screen flex flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white">
                <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                    <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
                        <div class="flex lg:justify-center lg:col-start-2">
                            <svg class="h-12 w-auto text-white lg:h-16 lg:text-[#FF2D20]" viewBox="0 0 62 65" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M61.8548 14.6253C61.8778 14.7102 61.8895 14.7978 61.8897 14.8858V28.5618C61.8898 28.737 61.8434 28.9092 61.7554 29.0601C61.6674 29.211 61.5407 29.3354 61.3888 29.4206L49.1164 36.2527V45.9713C49.1164 46.1565 49.0654 46.3379 48.9692 46.4951C48.8731 46.6522 48.7356 46.7788 48.5723 46.8603L32.0422 55.1973L32.0415 55.1977C31.8757 55.2811 31.6912 55.325 31.5041 55.325C31.3169 55.325 31.1325 55.2811 30.9667 55.1977L30.9659 55.1973L14.4364 46.8603C14.2731 46.7788 14.1356 46.6522 14.0395 46.4951C13.9433 46.3379 13.8923 46.1565 13.8923 45.9713V36.2527L1.61932 29.4206C1.46738 29.3354 1.3407 29.211 1.25273 29.0601C1.16477 28.9092 1.11833 28.737 1.11845 28.5618V14.8858C1.11867 14.7978 1.13039 14.7102 1.15337 14.6253L6.37119 0.704204C6.4214 0.570498 6.50853 0.453303 6.62151 0.367123C6.73449 0.280943 6.8687 0.229344 7.00705 0.21884C7.02796 0.217277 7.04899 0.216515 7.07005 0.216553H55.9381C55.9592 0.216515 55.9802 0.217277 56.0011 0.21884C56.1395 0.229344 56.2737 0.280943 56.3867 0.367123C56.4996 0.453303 56.5867 0.570498 56.637 0.704204L61.8548 14.6253ZM4.87224 14.6401L10.3718 29.1913L10.3722 29.1923L23.4901 36.4897L23.4907 36.4901C23.6565 36.5735 23.8409 36.6174 24.028 36.6174C24.2152 36.6174 24.3996 36.5735 24.5654 36.4901L39.7523 28.847L39.7529 28.8467L54.1206 14.6401L51.9442 8.81302L31.5041 20.0436L11.064 8.81302L8.88764 14.6401H4.87224ZM56.4172 14.6401L42.0494 28.8466L42.3484 29.0125L47.0194 31.6042L58.1359 25.4213V14.6401H56.4172ZM31.5041 51.5273L46.8956 43.8315V38.6496L32.0422 46.0741L32.0415 46.0744C31.8757 46.1578 31.6912 46.2017 31.5041 46.2017C31.3169 46.2017 31.1325 46.1578 30.9667 46.0744L30.9659 46.0741L16.1126 38.6496V43.8315L31.5041 51.5273ZM13.8923 25.4213V33.6421L21.2642 37.7371L11.2384 32.1643L5.33404 16.5161L3.87224 14.6401H5.48514L13.8923 25.4213ZM51.9961 4.31655H11.0121L31.5041 15.5471L51.9961 4.31655Z" fill="currentColor"/></svg>
                        </div>
                        @if (Route::has('login'))
                            <nav class="-mx-3 flex flex-1 justify-end">
                                @auth
                                    <a
                                        href="{{ url('/dashboard') }}"
                                        class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                                    >
                                        Dashboard
                                    </a>
                                @else
                                    <a
                                        href="{{ route('login') }}"
                                        class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                                    >
                                        Log in
                                    </a>

                                    @if (Route::has('register'))
                                        <a
                                            href="{{ route('register') }}"
                                            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                                        >
                                            Register
                                        </a>
                                    @endif
                                @endauth
                            </nav>
                        @endif
                    </header>

                    <main class="mt-6">
                        <div class="grid gap-6 lg:grid-cols-2 lg:gap-8">
                            <div class="flex flex-col items-start gap-6 overflow-hidden rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 focus:outline-none focus-visible:ring-[#FF2D20] md:row-span-3 lg:p-10 dark:bg-zinc-900 dark:ring-zinc-800 dark:hover:text-white/70 dark:hover:ring-zinc-700 dark:focus-visible:ring-white">
                                <h2 class="text-xl font-semibold text-black dark:text-white">LaraBids Auction System</h2>
                                <p class="mt-4 text-sm/relaxed">
                                    Welcome to LaraBids, the premier online auction platform. Browse, bid, and win.
                                </p>
                            </div>
                        </div>
                    </main>

                    <footer class="py-16 text-center text-sm text-black dark:text-white/70">
                        Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
                    </footer>
                </div>
            </div>
        </div>
    </body>
</html>


