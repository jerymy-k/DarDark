<?php
require_once __DIR__ . "/../core/Session.php";
Session::start();

$errer = Session::get("errer");
$succes = Session::get("succes");

Session::remove("errer");
Session::remove("succes");
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login • DarDark</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap"
        rel="stylesheet">
    <link rel="icon" type="image/png" href="img/DarDarkLogo.png">
    <style>
        body {
            font-family: "Plus Jakarta Sans", system-ui, -apple-system, Segoe UI, Roboto, sans-serif;
        }

        * {
            scrollbar-width: none;
        }
    </style>
</head>

<body class="min-h-screen bg-zinc-950 text-zinc-100">
    <!-- Background -->
    <div class="pointer-events-none fixed inset-0">
        <div
            class="absolute -top-40 left-1/2 h-[420px] w-[420px] -translate-x-1/2 rounded-full bg-rose-600/20 blur-3xl">
        </div>
        <div class="absolute bottom-0 right-0 h-[360px] w-[360px] rounded-full bg-red-500/10 blur-3xl"></div>
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,rgba(255,255,255,0.06),rgba(0,0,0,0)_55%)]">
        </div>
    </div>

    <main class="relative mx-auto flex min-h-screen max-w-6xl items-center justify-center px-4 py-10">
        <div class="grid w-full grid-cols-1 items-stretch gap-8 md:grid-cols-2">
            <!-- Left: Brand -->
            <section
                class="hidden md:flex flex-col justify-between rounded-3xl border border-white/10 bg-white/[0.03] p-8 backdrop-blur">
                <div>
                    <!-- Brand -->
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10">
                            <img src="img/DarDarkLogo.png" alt="">
                        </div>
                        <div>
                            <p class="text-sm text-white/70">DarDark</p>
                            <h1 class="text-xl font-bold tracking-tight">
                                Welcome back
                            </h1>
                        </div>
                    </div>

                    <!-- Description -->
                    <p class="mt-6 text-sm leading-6 text-white/70">
                        Access your personal space to manage your stays, communicate with hosts or travelers,
                        and keep track of your bookings in one place.
                    </p>

                    <!-- Key benefits -->
                    <div class="mt-8 space-y-4">
                        <div class="flex items-start gap-3">
                            <span
                                class="mt-1 inline-flex h-6 w-6 items-center justify-center rounded-lg bg-white/5 text-white/80">🏡</span>
                            <div>
                                <p class="font-semibold">Manage your stays</p>
                                <p class="text-sm text-white/60">
                                    View upcoming and past bookings with all the details you need.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <span
                                class="mt-1 inline-flex h-6 w-6 items-center justify-center rounded-lg bg-white/5 text-white/80">❤️</span>
                            <div>
                                <p class="font-semibold">Your favorites</p>
                                <p class="text-sm text-white/60">
                                    Quickly access the places you’ve saved for later.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <span
                                class="mt-1 inline-flex h-6 w-6 items-center justify-center rounded-lg bg-white/5 text-white/80">💬</span>
                            <div>
                                <p class="font-semibold">Messages & communication</p>
                                <p class="text-sm text-white/60">
                                    Stay in touch with hosts or travelers through secure messaging.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer note -->
                <p class="text-xs text-white/50">
                    Log in to continue your experience on DarDark.
                </p>
            </section>

            <!-- Right: Form -->
            <section class="rounded-3xl border border-white/10 bg-white/[0.03] p-6 backdrop-blur md:p-8">
                <div class="md:hidden mb-6 flex items-center gap-3">
                    <div
                        class="h-10 w-10 rounded-2xl bg-gradient-to-br from-rose-500 to-red-600 shadow-lg shadow-rose-500/20">
                    </div>
                    <div>
                        <p class="text-sm text-white/70">DarDark</p>
                        <h1 class="text-xl font-bold tracking-tight">Welcome back</h1>
                    </div>
                </div>

                <p class="mb-6 text-sm text-white/60">
                    Sign in with your email and password.
                </p>

                <?php if ($errer): ?>
                    <div class="mb-5 rounded-2xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-200">
                        <?= htmlspecialchars($errer) ?>
                    </div>
                <?php endif; ?>

                <?php if ($succes): ?>
                    <div
                        class="mb-5 rounded-2xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
                        <?= htmlspecialchars($succes) ?>
                    </div>
                <?php endif; ?>


                <form class="space-y-5" method="POST" action="/airbnb-php-oop/actions/loginAction.php">
                    <!-- Email -->
                    <div>
                        <label for="email" class="mb-2 block text-sm font-semibold text-white/90">Email</label>
                        <input id="email" name="email" type="email" placeholder="you@example.com" required class="w-full rounded-2xl border border-white/10 bg-black/30 px-4 py-3 text-sm text-white placeholder:text-white/40 outline-none transition
                     focus:border-rose-500/40 focus:ring-4 focus:ring-rose-500/10" />
                        <p class="mt-2 hidden text-xs text-red-200" id="errEmail">Please enter a valid email.</p>
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <label for="password" class="block text-sm font-semibold text-white/90">Password</label>
                            <a href="#" class="text-xs text-white/60 underline underline-offset-4 hover:text-rose-200">
                                Forgot password?
                            </a>
                        </div>

                        <div class="relative">
                            <input id="password" name="password" type="password" placeholder="Enter your password"
                                required class="w-full rounded-2xl border border-white/10 bg-black/30 px-4 py-3 pr-12 text-sm text-white placeholder:text-white/40 outline-none transition
                       focus:border-rose-500/40 focus:ring-4 focus:ring-rose-500/10" />
                            <button type="button" id="togglePwd" class="absolute right-2 top-1/2 -translate-y-1/2 rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-xs text-white/80
                       hover:bg-white/10 transition" aria-label="Show password">
                                Show
                            </button>
                        </div>

                        <p class="mt-2 hidden text-xs text-red-200" id="errPassword">Password is required.</p>
                    </div>

                    <!-- Submit -->
                    <button type="submit"
                        class="group inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-gradient-to-br from-rose-500 to-red-600 px-4 py-3 text-sm font-bold
                   shadow-lg shadow-rose-500/20 transition hover:brightness-110 focus:outline-none focus:ring-4 focus:ring-rose-500/20">
                        Login
                        <span class="text-white/90 transition group-hover:translate-x-0.5">→</span>
                    </button>

                    <!-- Footer -->
                    <p class="text-center text-sm text-white/60">
                        Don’t have an account?
                        <a href="signup.php"
                            class="font-semibold text-white hover:text-rose-200 underline underline-offset-4">
                            Create one
                        </a>
                    </p>
                </form>
            </section>
        </div>
    </main>

    <script>
        // Toggle password visibility
        const pwd = document.getElementById('password');
        const toggle = document.getElementById('togglePwd');

        toggle.addEventListener('click', () => {
            const isHidden = pwd.type === 'password';
            pwd.type = isHidden ? 'text' : 'password';
            toggle.textContent = isHidden ? 'Hide' : 'Show';
            toggle.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
        });
    </script>
</body>

</html>