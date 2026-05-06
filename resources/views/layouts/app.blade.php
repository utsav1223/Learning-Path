<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkillWeave</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#eef4ff',
                            100: '#d9e7ff',
                            500: '#315efb',
                            600: '#264ee8',
                            700: '#1f3fc0',
                            900: '#0f1b3d',
                        },
                        ink: '#111827',
                    },
                    boxShadow: {
                        soft: '0 24px 60px rgba(15, 23, 42, 0.12)',
                    },
                },
            },
        };
    </script>
    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Manrope', sans-serif;
            background:
                radial-gradient(circle at top left, rgba(49, 94, 251, 0.12), transparent 34%),
                linear-gradient(180deg, #f8fbff 0%, #ffffff 18%, #f8fafc 100%);
        }

        .reveal {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }

        .reveal.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        .reveal-delay-1 {
            transition-delay: 0.08s;
        }

        .reveal-delay-2 {
            transition-delay: 0.16s;
        }

        .reveal-delay-3 {
            transition-delay: 0.24s;
        }

        .floating-panel {
            animation: floatPanel 7s ease-in-out infinite;
        }

        .pulse-ring {
            animation: pulseRing 2.8s ease-in-out infinite;
        }

        .marquee-track {
            display: flex;
            width: max-content;
            animation: marqueeMove 28s linear infinite;
        }

        .marquee-track.reverse {
            animation-direction: reverse;
        }

        .faq-item[open] summary svg {
            transform: rotate(180deg);
        }

        @keyframes floatPanel {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        @keyframes pulseRing {
            0%, 100% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.08);
                opacity: 0.82;
            }
        }

        @keyframes marqueeMove {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(-50%);
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .reveal,
            .floating-panel,
            .pulse-ring,
            .marquee-track {
                animation: none !important;
                opacity: 1 !important;
                transform: none !important;
                transition: none !important;
            }
        }
    </style>
</head>
<body class="text-slate-800 antialiased">
    @include('components.navbar')

    <main>
        @yield('content')
    </main>

    @include('components.footer')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.15 });

            document.querySelectorAll('.reveal').forEach(function (element) {
                observer.observe(element);
            });

            const menuButton = document.querySelector('[data-mobile-menu-button]');
            const menu = document.querySelector('[data-mobile-menu]');
            const menuLinks = document.querySelectorAll('[data-mobile-menu-link]');

            if (menuButton && menu) {
                const openIcon = menuButton.querySelector('.menu-open-icon');
                const closeIcon = menuButton.querySelector('.menu-close-icon');

                const setMenuState = function (isOpen) {
                    menu.classList.toggle('hidden', !isOpen);
                    menuButton.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                    if (openIcon) {
                        openIcon.classList.toggle('hidden', isOpen);
                    }
                    if (closeIcon) {
                        closeIcon.classList.toggle('hidden', !isOpen);
                    }
                };

                setMenuState(false);

                menuButton.addEventListener('click', function () {
                    const isOpen = menuButton.getAttribute('aria-expanded') === 'true';
                    setMenuState(!isOpen);
                });

                menuLinks.forEach(function (link) {
                    link.addEventListener('click', function () {
                        setMenuState(false);
                    });
                });

                window.addEventListener('resize', function () {
                    if (window.innerWidth >= 640) {
                        setMenuState(false);
                    }
                });
            }
        });
    </script>
</body>
</html>
