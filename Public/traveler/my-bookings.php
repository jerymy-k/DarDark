<?php
require_once __DIR__ . "/../../core/Session.php";
require_once __DIR__ . "/../../classes/Booking.php";
require_once __DIR__ . "/../../classes/User.php";

Session::start();
if (!Session::get('id')) {
    header("Location: /airbnb-php-oop/Public/login.php");
    exit();
}

$userId = (int) Session::get('id');
$userInfo = User::getUserById($userId);
$travelerName = $userInfo['name'] ?? 'Traveler';

$items = Booking::findUserBookings($userId);

$err = Session::get('errer');
$ok = Session::get('succes');
Session::remove('errer');
Session::remove('succes');

function h($v)
{
    return htmlspecialchars((string) $v);
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>My Bookings • Traveler • DarDark</title>
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
    <!-- background glow -->
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

            <!-- SIDEBAR -->
            <aside
            class="hidden md:flex w-80 flex-col border-r border-white/10 bg-white/[0.03] backdrop-blur sticky top-0 h-screen">
            <div class="flex items-center gap-3 px-6 py-5 border-b border-white/10">
                <div class="h-10 w-10">
                    <img src="/airbnb-php-oop/Public/img/DarDarkLogo.png" alt="DarDark"
                        class="h-10 w-10 object-contain" />
                </div>
                <div>
                    <div class="text-sm text-white/70">DarDark</div>
                    <div class="text-base font-bold tracking-tight">Traveler Space</div>
                </div>
            </div>

            <nav class="flex-1 px-4 py-5 space-y-2">
                <a href="/airbnb-php-oop/Public/traveler/dashboard.php"
                class="flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-semibold border border-white/10 bg-black/20 hover:bg-white/5 transition">
                <span>Dashboard</span><span class="text-xs text-white/40">Home</span>
            </a>
            
            
            
            <a href="/airbnb-php-oop/Public/traveler/my-bookings.php"
            class="flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-semibold bg-white/5 border border-white/10 hover:bg-white/10 transition">
            <span>My Bookings</span><span class="text-xs text-white/40">Trips</span>
        </a>
        
        <a href="/airbnb-php-oop/Public/traveler/favorites.php"
        class="flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-semibold border border-white/10 bg-black/20 hover:bg-white/5 transition">
        <span>Favorites</span><span class="text-xs text-white/40">Saved</span>
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

            <!-- MAIN -->
            <main class="flex-1 min-h-screen w-full px-4 py-6 md:px-10 md:py-10 space-y-6">

                <header
                    class="flex flex-col gap-4 rounded-3xl border border-white/10 bg-white/[0.03] p-6 backdrop-blur md:flex-row md:items-center md:justify-between">
                    <div>
                        <div class="text-sm text-white/70">My Bookings</div>
                        <h1 class="mt-1 text-2xl font-bold tracking-tight">Your trips, <?= h($travelerName) ?></h1>
                        <p class="mt-2 text-sm text-white/60">Here you can review your bookings and cancel a confirmed
                            one.</p>
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="/airbnb-php-oop/Public/traveler/browse.php"
                            class="rounded-2xl bg-gradient-to-br from-rose-500 to-red-600 px-4 py-3 text-sm font-bold shadow-lg shadow-rose-500/20 hover:brightness-110 transition">
                            Browse Rentals
                        </a>
                        <a href="/airbnb-php-oop/Public/traveler/dashboard.php"
                            class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-white/80 hover:bg-white/10 transition">
                            Back
                        </a>
                    </div>
                </header>

                <?php if ($err): ?>
                    <div class="rounded-2xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-200">
                        <?= h($err) ?>
                    </div>
                <?php endif; ?>

                <?php if ($ok): ?>
                    <div
                        class="rounded-2xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
                        <?= h($ok) ?>
                    </div>
                <?php endif; ?>

                <section class="rounded-3xl border border-white/10 bg-white/[0.03] p-6 backdrop-blur">
                    <div class="flex items-center justify-between gap-3 flex-wrap">
                        <h2 class="text-lg font-bold tracking-tight">All bookings</h2>
                        <span class="text-xs text-white/50"><?= count($items) ?> total</span>
                    </div>

                    <div class="mt-5 grid grid-cols-1 gap-4">
                        <?php if (empty($items)): ?>
                            <div class="rounded-2xl border border-white/10 bg-black/20 px-4 py-3 text-sm text-white/60">
                                You don’t have any bookings yet. Go to <span class="text-white font-semibold">Browse
                                    Rentals</span> to reserve your first stay.
                            </div>
                        <?php else: ?>
                            <?php foreach ($items as $b): ?>
                                <div class="rounded-3xl border border-white/10 bg-black/20 p-5">
                                    <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                                        <div class="min-w-0">
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <div class="text-base font-bold text-white/90 truncate">
                                                    <?= h($b['title'] ?? '') ?></div>

                                                <span
                                                    class="inline-flex rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs font-semibold text-white/70">
                                                    <?= h($b['status'] ?? '') ?>
                                                </span>
                                            </div>

                                            <div class="mt-1 text-sm text-white/60"><?= h($b['city'] ?? '') ?></div>

                                            <div class="mt-4 grid grid-cols-1 gap-2 sm:grid-cols-3">
                                                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                                    <div class="text-xs text-white/60">Dates</div>
                                                    <div class="mt-1 text-sm font-semibold text-white/90">
                                                        <?= h($b['start_date'] ?? '') ?> → <?= h($b['end_date'] ?? '') ?>
                                                    </div>
                                                </div>

                                                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                                    <div class="text-xs text-white/60">Total</div>
                                                    <div class="mt-1 text-sm font-semibold text-white/90">
                                                        <?= h($b['total_price'] ?? '') ?>
                                                    </div>
                                                </div>

                                                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                                    <div class="text-xs text-white/60">Booking ID</div>
                                                    <div class="mt-1 text-sm font-semibold text-white/90">
                                                        #<?= (int) ($b['id'] ?? 0) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="/airbnb-php-oop/actions/traveler/downloadReceipt.php?id=<?= (int) $b['id'] ?>"
                                            class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-white/80 hover:bg-white/10 transition">
                                            Télécharger Reçu PDF
                                        </a>
                                        <div class="flex items-center gap-2 md:justify-end">
                                            <?php if (($b['status'] ?? '') === 'CONFIRMED'): ?>
                                                <form method="POST" action="/airbnb-php-oop/actions/traveler/cancelBooking.php"
                                                    onsubmit="return confirm('Cancel this booking?');">
                                                    <input type="hidden" name="booking_id" value="<?= (int) ($b['id'] ?? 0) ?>">
                                                    <button
                                                        class="rounded-2xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm font-semibold text-red-200 hover:bg-red-500/15 transition">
                                                        Cancel
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <span class="text-xs text-white/50">You can cancel only confirmed bookings.</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </section>

            </main>
        </div>
    </div>
</body>

</html>