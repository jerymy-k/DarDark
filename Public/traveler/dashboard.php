<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../../core/Session.php";
require_once __DIR__ . "/../../classes/Rental.php";
require_once __DIR__ . "/../../classes/User.php";

Session::start();

if (!Session::get('id')) {
    header("Location: /airbnb-php-oop/Public/login.php");
    exit();
}

if (Session::get('role') !== 'TRAVELER') {
    header("Location: /airbnb-php-oop/Public/login.php");
    exit();
}

$userId = (int) Session::get('id');
$userInfo = User::getUserById($userId);
$travelerName = $userInfo['name'];

$errer = Session::get("errer");
$succes = Session::get("succes");
Session::remove("errer");
Session::remove("succes");

$rentals = Rental::getAll();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Traveler Dashboard • DarDark</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="/airbnb-php-oop/Public/img/DarDarkLogo.png">
    <style>
        body { font-family: "Plus Jakarta Sans", system-ui, -apple-system, Segoe UI, Roboto, sans-serif; }
        * { scrollbar-width: none; }
    </style>
</head>

<body class="min-h-screen bg-zinc-950 text-zinc-100">
<div class="pointer-events-none fixed inset-0">
    <div class="absolute -top-40 left-1/2 h-[520px] w-[520px] -translate-x-1/2 rounded-full bg-rose-600/20 blur-3xl"></div>
    <div class="absolute bottom-0 right-0 h-[420px] w-[420px] rounded-full bg-red-500/10 blur-3xl"></div>
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,rgba(255,255,255,0.06),rgba(0,0,0,0)_55%)]"></div>
</div>

<div class="relative min-h-screen w-full">
    <div class="flex min-h-screen w-full">

        <aside class="hidden md:flex w-80 flex-col border-r border-white/10 bg-white/[0.03] backdrop-blur">
            <div class="flex items-center gap-3 px-6 py-5 border-b border-white/10">
                <div class="h-10 w-10">
                    <img src="/airbnb-php-oop/Public/img/DarDarkLogo.png" alt="DarDark" class="h-10 w-10 object-contain" />
                </div>
                <div>
                    <div class="text-sm text-white/70">DarDark</div>
                    <div class="text-base font-bold tracking-tight">Traveler Space</div>
                </div>
            </div>

            <nav class="flex-1 px-4 py-5 space-y-2">
                <a href="/airbnb-php-oop/Public/traveler/dashboard.php"
                   class="flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-semibold bg-white/5 border border-white/10 hover:bg-white/10 transition">
                    <span>Dashboard</span><span class="text-xs text-white/40">Home</span>
                </a>

                <a href="/airbnb-php-oop/Public/traveler/browse.php"
                   class="flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-semibold border border-white/10 bg-black/20 hover:bg-white/5 transition">
                    <span>Browse Rentals</span><span class="text-xs text-white/40">Explore</span>
                </a>

                <a href="/airbnb-php-oop/Public/traveler/my-bookings.php"
                   class="flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-semibold border border-white/10 bg-black/20 hover:bg-white/5 transition">
                    <span>My Bookings</span><span class="text-xs text-white/40">Trips</span>
                </a>

                <a href="/airbnb-php-oop/Public/traveler/favorites.php"
                   class="flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-semibold border border-white/10 bg-black/20 hover:bg-white/5 transition">
                    <span>Favorites</span><span class="text-xs text-white/40">Saved</span>
                </a>

                <a href="/airbnb-php-oop/Public/traveler/notifications.php"
                   class="flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-semibold border border-white/10 bg-black/20 hover:bg-white/5 transition">
                    <span>Notifications</span><span class="text-xs text-white/40">Updates</span>
                </a>

                <a href="/airbnb-php-oop/Public/traveler/traveler-profile.php"
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

        <main class="flex-1 min-h-screen w-full px-4 py-6 md:px-10 md:py-10 space-y-6">

            <header class="flex flex-col gap-4 rounded-3xl border border-white/10 bg-white/[0.03] p-6 backdrop-blur md:flex-row md:items-center md:justify-between">
                <div>
                    <div class="text-sm text-white/70">Traveler Dashboard</div>
                    <h1 class="mt-1 text-2xl font-bold tracking-tight">Welcome, <?= htmlspecialchars($travelerName) ?></h1>
                    <p class="mt-2 text-sm text-white/60">Browse all available rentals and open details in a popup.</p>
                </div>

                <div class="flex items-center gap-2">
                    <a href="/airbnb-php-oop/Public/traveler/browse.php"
                       class="rounded-2xl bg-gradient-to-br from-rose-500 to-red-600 px-4 py-3 text-sm font-bold shadow-lg shadow-rose-500/20 hover:brightness-110 transition">
                        Browse
                    </a>
                    <a href="/airbnb-php-oop/Public/traveler/my-bookings.php"
                       class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-white/80 hover:bg-white/10 transition">
                        My Bookings
                    </a>
                </div>
            </header>

            <?php if ($errer): ?>
                <div class="rounded-2xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-200">
                    <?= htmlspecialchars($errer) ?>
                </div>
            <?php endif; ?>

            <?php if ($succes): ?>
                <div class="rounded-2xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
                    <?= htmlspecialchars($succes) ?>
                </div>
            <?php endif; ?>

            <section class="rounded-3xl border border-white/10 bg-white/[0.03] p-6 backdrop-blur">
                <div class="flex items-center justify-between gap-3 flex-wrap">
                    <h2 class="text-lg font-bold tracking-tight">Available rentals</h2>
                    <span class="text-xs text-white/50"><?= count($rentals) ?> total</span>
                </div>

                <div class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
                    <?php if (empty($rentals)): ?>
                        <div class="md:col-span-2 xl:col-span-3 rounded-2xl border border-white/10 bg-black/20 px-4 py-3 text-sm text-white/60">
                            No rentals available right now.
                        </div>
                    <?php else: ?>
                        <?php foreach ($rentals as $r): ?>
                            <div class="rounded-3xl border border-white/10 bg-black/20 p-5">
                                <img src="<?= htmlspecialchars($r['cover_path']) ?>"
                                     alt="Rental image"
                                     class="mb-3 h-40 w-full rounded-2xl object-cover border border-white/10" />

                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <div class="text-base font-bold text-white/90 truncate">
                                            <?= htmlspecialchars($r['title']) ?>
                                        </div>
                                        <div class="mt-1 text-sm text-white/60">
                                            <?= htmlspecialchars($r['city']) ?>
                                            • <span class="text-white/80 font-semibold"><?= htmlspecialchars($r['price_per_night']) ?></span> / night
                                        </div>
                                    </div>

                                    <span class="inline-flex rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs font-semibold text-white/70">
                                        ACTIVE
                                    </span>
                                </div>

                                <div class="mt-3 text-sm text-white/70">
                                    Max guests: <?= htmlspecialchars($r['max_guests']) ?>
                                </div>

                                <div class="mt-2 text-sm text-white/70 line-clamp-3">
                                    <?= htmlspecialchars($r['description']) ?>
                                </div>

                                <div class="mt-4 flex items-center justify-between">
                                    <div class="text-xs text-white/40">
                                        <?= htmlspecialchars($r['created_at']) ?>
                                    </div>

                                    <button
                                        type="button"
                                        class="btnViewRental rounded-xl bg-gradient-to-br from-rose-500 to-red-600 px-4 py-2 text-xs font-bold shadow-lg shadow-rose-500/20 hover:brightness-110 transition"
                                        data-title="<?= htmlspecialchars($r['title']) ?>"
                                        data-city="<?= htmlspecialchars($r['city']) ?>"
                                        data-address="<?= htmlspecialchars($r['address']) ?>"
                                        data-price="<?= htmlspecialchars($r['price_per_night']) ?>"
                                        data-guests="<?= htmlspecialchars($r['max_guests']) ?>"
                                        data-desc="<?= htmlspecialchars($r['description']) ?>"
                                        data-cover="<?= htmlspecialchars($r['cover_path']) ?>"
                                        data-host="Host (later)"
                                        data-dates="Dates (later)"
                                    >
                                        View
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>

        </main>
    </div>
</div>

<div id="detailsModal" class="hidden fixed inset-0 z-50">
    <div id="modalOverlay" class="absolute inset-0 bg-black/70"></div>

    <div class="relative mx-auto flex min-h-screen max-w-4xl items-center justify-center px-4 py-10">
        <div class="w-full rounded-3xl border border-white/10 bg-zinc-950/90 backdrop-blur">
            <div class="max-h-[90vh] overflow-y-auto p-6 md:p-8">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 id="m_title" class="text-xl font-bold tracking-tight">Rental details</h2>
                        <p id="m_sub" class="mt-1 text-sm text-white/60"></p>
                    </div>
                    <button id="closeModal"
                            class="rounded-2xl border border-white/10 bg-white/5 px-3 py-2 text-sm text-white/80 hover:bg-white/10 transition">
                        ✕
                    </button>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">

                    <div class="rounded-3xl border border-white/10 bg-black/20 p-4">
                        <div class="overflow-hidden rounded-2xl border border-white/10 bg-white/5">
                            <img id="m_cover" src="" alt="Cover preview" class="h-64 w-full object-cover" />
                        </div>
                        <div id="m_coverHint" class="mt-2 text-xs text-white/50"></div>
                    </div>

                    <div class="rounded-3xl border border-white/10 bg-black/20 p-4 space-y-4">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="inline-flex rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs font-semibold text-white/70">
                                Price: <span id="m_price" class="ml-1 text-white/90"></span>/night
                            </span>
                            <span class="inline-flex rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs font-semibold text-white/70">
                                City: <span id="m_city" class="ml-1 text-white/90"></span>
                            </span>
                            <span class="inline-flex rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs font-semibold text-white/70">
                                Max guests: <span id="m_guests" class="ml-1 text-white/90"></span>
                            </span>
                        </div>

                        <div>
                            <div class="text-sm font-semibold text-white/90">Host</div>
                            <div id="m_host" class="mt-1 text-sm text-white/70">—</div>
                        </div>

                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <div class="text-sm font-semibold text-white/90">Available dates</div>
                            <div id="m_dates" class="mt-2 text-sm text-white/70">—</div>
                        </div>

                        <div>
                            <div class="text-sm font-semibold text-white/90">Description</div>
                            <p id="m_desc" class="mt-1 text-sm text-white/70 leading-6"></p>
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-2">
                            <button id="closeModal2"
                                    class="rounded-2xl border border-white/10 bg-white/5 px-5 py-3 text-sm font-semibold text-white/80 hover:bg-white/10 transition">
                                Close
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
const modal = document.getElementById('detailsModal');
const overlay = document.getElementById('modalOverlay');
const close1 = document.getElementById('closeModal');
const close2 = document.getElementById('closeModal2');

const m_title = document.getElementById('m_title');
const m_sub = document.getElementById('m_sub');
const m_cover = document.getElementById('m_cover');
const m_coverHint = document.getElementById('m_coverHint');
const m_price = document.getElementById('m_price');
const m_city = document.getElementById('m_city');
const m_guests = document.getElementById('m_guests');
const m_desc = document.getElementById('m_desc');
const m_host = document.getElementById('m_host');
const m_dates = document.getElementById('m_dates');

function openModal(data){
    m_title.textContent = data.title || 'Rental details';
    m_sub.textContent = (data.city || '') + (data.address ? (' • ' + data.address) : '');
    m_cover.src = data.cover || '';
    m_coverHint.textContent = data.cover ? '' : 'No image';
    m_price.textContent = data.price || '';
    m_city.textContent = data.city || '';
    m_guests.textContent = data.guests || '';
    m_desc.textContent = data.desc || '';
    m_host.textContent = data.host || '—';
    m_dates.textContent = data.dates || 'Dates (later)';
    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeModal(){
    modal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

overlay.addEventListener('click', closeModal);
close1.addEventListener('click', closeModal);
close2.addEventListener('click', closeModal);

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal();
});

document.querySelectorAll('.btnViewRental').forEach(btn => {
    btn.addEventListener('click', () => {
        openModal({
            title: btn.dataset.title,
            city: btn.dataset.city,
            address: btn.dataset.address,
            price: btn.dataset.price,
            guests: btn.dataset.guests,
            desc: btn.dataset.desc,
            cover: btn.dataset.cover,
            host: btn.dataset.host,
            dates: btn.dataset.dates
        });
    });
});
</script>

</body>
</html>
