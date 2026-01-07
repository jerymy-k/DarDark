<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . "/../../core/Database.php";
require_once __DIR__ . "/../../core/Session.php";

Session::start();

if (!Session::get('id')) {
    header("Location: /airbnb-php-oop/Public/login.php");
    exit();
}

if (Session::get('role') !== 'HOST') {
    header("Location: /airbnb-php-oop/Public/login.php");
    exit();
}
$userId = Session::get('id');
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
    <title>Add Property • Host • DarDark</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap"
        rel="stylesheet">
    <link rel="icon" type="image/png" href="/airbnb-php-oop/Public/img/DarDarkLogo.png">
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
    <div class="pointer-events-none fixed inset-0">
        <div
            class="absolute -top-40 left-1/2 h-[520px] w-[520px] -translate-x-1/2 rounded-full bg-rose-600/20 blur-3xl">
        </div>
        <div class="absolute bottom-0 right-0 h-[420px] w-[420px] rounded-full bg-red-500/10 blur-3xl"></div>
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,rgba(255,255,255,0.06),rgba(0,0,0,0)_55%)]">
        </div>
    </div>

    <div class="relative min-h-screen w-full">
        <div class="flex min-h-screen w-full">

            <aside
                class="hidden md:flex w-80 flex-col border-r border-white/10 bg-white/[0.03] backdrop-blur sticky top-0 h-screen">
                <div class="flex items-center gap-3 px-6 py-5 border-b border-white/10">
                    <div class="h-10 w-10">
                        <img src="/airbnb-php-oop/Public/img/DarDarkLogo.png" alt="DarDark"
                            class="h-10 w-10 object-contain" />
                    </div>
                    <div>
                        <div class="text-sm text-white/70">DarDark</div>
                        <div class="text-base font-bold tracking-tight">Host Space</div>
                    </div>
                </div>

                <nav class="flex-1 px-4 py-5 space-y-2">
                    <a href="/airbnb-php-oop/Public/host/dashboard.php"
                    class="flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-semibold border border-white/10 bg-black/20 hover:bg-white/5 transition">
                    <span>Dashboard</span><span class="text-xs text-white/40">Home</span>
                </a>
                
                <a href="/airbnb-php-oop/Public/host/add-property.php"
                class="flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-semibold bg-white/5 border border-white/10 hover:bg-white/10 transition">
                        <span>Add Property</span><span class="text-xs text-white/40">Create</span>
                    </a>

                    <a href="/airbnb-php-oop/Public/host/my-properties.php"
                        class="flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-semibold border border-white/10 bg-black/20 hover:bg-white/5 transition">
                        <span>My Properties</span><span class="text-xs text-white/40">Manage</span>
                    </a>



                    <a href="/airbnb-php-oop/Public/host/host-profile.php"
                        class="flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-semibold border border-white/10 bg-black/20 hover:bg-white/5 transition">
                        <span>My Profile</span><span class="text-xs text-white/40">Account</span>
                    </a>
                </nav>

                <div class="px-6 py-5 border-t border-white/10">
                    <a href="/airbnb-php-oop/actions/logout.php"
                        class="inline-flex w-full items-center justify-center rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-white/80 hover:bg-white/10 transition">
                        Logout
                    </a>
                </div>
            </aside>

            <main class="flex-1 min-h-screen w-full px-4 py-6 md:px-10 md:py-10">
                <header
                    class="flex flex-col gap-4 rounded-3xl border border-white/10 bg-white/[0.03] p-6 backdrop-blur md:flex-row md:items-center md:justify-between">
                    <div>
                        <div class="text-sm text-white/70">Add Property</div>
                        <h1 class="mt-1 text-2xl font-bold tracking-tight">Create a new listing</h1>
                        <p class="mt-2 text-sm text-white/60">Fill the details of your property. You can edit it later.
                        </p>
                    </div>

                    <a href="/airbnb-php-oop/Public/host/my-properties.php"
                        class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-white/80 hover:bg-white/10 transition">
                        View My Properties
                    </a>
                </header>
                <?php if ($errer): ?>
                    <div class="mt-6 rounded-2xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-200">
                        <?= htmlspecialchars($errer) ?>
                    </div>
                <?php endif; ?>

                <?php if ($succes): ?>
                    <div
                        class="mt-6 rounded-2xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
                        <?= htmlspecialchars($succes) ?>
                    </div>
                <?php endif; ?>
                <section class="mt-6 rounded-3xl border border-white/10 bg-white/[0.03] p-6 backdrop-blur">
                    <form class="grid grid-cols-1 gap-4 md:grid-cols-2" method="POST"
                        action="/airbnb-php-oop/actions/host/newProperty.php">

                        <div class="md:col-span-2">
                            <label class="mb-2 block text-sm font-semibold text-white/90">Title</label>
                            <input name="title" required placeholder="e.g. Cozy Apartment in City Center"
                                class="w-full rounded-2xl border border-white/10 bg-black/30 px-4 py-3 text-sm text-white placeholder:text-white/40 outline-none transition focus:border-rose-500/40 focus:ring-4 focus:ring-rose-500/10">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-white/90">City</label>
                            <input name="city" required placeholder="e.g. Casablanca"
                                class="w-full rounded-2xl border border-white/10 bg-black/30 px-4 py-3 text-sm text-white placeholder:text-white/40 outline-none transition focus:border-rose-500/40 focus:ring-4 focus:ring-rose-500/10">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-white/90">Guests</label>
                            <input name="guests" type="number" min="1" step="1" value="1" required
                                class="w-full rounded-2xl border border-white/10 bg-black/30 px-4 py-3 text-sm text-white outline-none transition focus:border-rose-500/40 focus:ring-4 focus:ring-rose-500/10">
                        </div>

                        <div class="md:col-span-2">
                            <label class="mb-2 block text-sm font-semibold text-white/90">Address</label>
                            <input name="address" placeholder="Street, neighborhood..." required
                                class="w-full rounded-2xl border border-white/10 bg-black/30 px-4 py-3 text-sm text-white placeholder:text-white/40 outline-none transition focus:border-rose-500/40 focus:ring-4 focus:ring-rose-500/10">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-white/90">Price per night</label>
                            <input name="price_per_night" type="number" min="0" step="1" required placeholder="e.g. 450"
                                class="w-full rounded-2xl border border-white/10 bg-black/30 px-4 py-3 text-sm text-white placeholder:text-white/40 outline-none transition focus:border-rose-500/40 focus:ring-4 focus:ring-rose-500/10">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-white/90">Cover image URL</label>
                            <input name="cover_image" type="url" placeholder="https://..." required
                                class="w-full rounded-2xl border border-white/10 bg-black/30 px-4 py-3 text-sm text-white placeholder:text-white/40 outline-none transition focus:border-rose-500/40 focus:ring-4 focus:ring-rose-500/10">
                        </div>

                        <div class="md:col-span-2">
                            <label class="mb-2 block text-sm font-semibold text-white/90">Description</label>
                            <textarea name="description" rows="5" required placeholder="Describe your place..."
                                class="w-full rounded-2xl border border-white/10 bg-black/30 px-4 py-3 text-sm text-white placeholder:text-white/40 outline-none transition focus:border-rose-500/40 focus:ring-4 focus:ring-rose-500/10"></textarea>
                        </div>

                        <div class="md:col-span-2 flex items-center justify-end gap-3 pt-2">
                            <a href="/airbnb-php-oop/Public/host/dashboard.php"
                                class="rounded-2xl border border-white/10 bg-white/5 px-5 py-3 text-sm font-semibold text-white/80 hover:bg-white/10 transition">
                                Cancel
                            </a>

                            <button type="submit"
                                class="rounded-2xl bg-gradient-to-br from-rose-500 to-red-600 px-5 py-3 text-sm font-bold shadow-lg shadow-rose-500/20 hover:brightness-110 transition">
                                Create property
                            </button>
                        </div>

                    </form>
                </section>
            </main>

        </div>
    </div>
</body>

</html>