<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../../core/Session.php";
require_once __DIR__ . "/../../classes/User.php";
require_once __DIR__ . "/../../classes/AdminManager.php";
require_once __DIR__ . "/../../classes/Statistics.php";

Session::start();
if (!Session::get('id') || Session::get('role') !== 'ADMIN') {
    header("Location: /airbnb-php-oop/Public/login.php");
    exit();
}

$adminId = (int) Session::get('id');
$adminInfo = User::getUserById($adminId);
$adminName = $adminInfo['name'] ?? 'Admin';

function h($v){ return htmlspecialchars((string)$v); }

// ✅ stats واحد فقط
$stats = AdminManager::stats();

// top rentals من Statistics (خليه)
$topRentals = Statistics::getTopRentals(5);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard • DarDark</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="/airbnb-php-oop/Public/img/DarDarkLogo.png">
    <style>
        body { font-family: "Plus Jakarta Sans", system-ui, -apple-system, Segoe UI, Roboto, sans-serif }
        * { scrollbar-width: none }
    </style>
</head>

<body class="min-h-screen bg-zinc-950 text-zinc-100">
<div class="relative min-h-screen w-full">
    <div class="flex min-h-screen w-full">

        <!-- SIDEBAR -->
        <aside class="hidden md:flex w-80 flex-col border-r border-white/10 bg-white/[0.03] backdrop-blur sticky top-0 h-screen">
            <div class="flex items-center gap-3 px-6 py-5 border-b border-white/10">
                <div class="h-10 w-10">
                    <img src="/airbnb-php-oop/Public/img/DarDarkLogo.png" class="h-10 w-10 object-contain" alt="DarDark">
                </div>
                <div>
                    <div class="text-sm text-white/70">DarDark</div>
                    <div class="text-base font-bold tracking-tight">Admin Space</div>
                </div>
            </div>

            <nav class="flex-1 px-4 py-5 space-y-2">
                <a href="/airbnb-php-oop/Public/admin/dashboard.php"
                   class="flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-semibold bg-white/5 border border-white/10 hover:bg-white/10 transition">
                    <span>Dashboard</span><span class="text-xs text-white/40">Home</span>
                </a>

                <a href="/airbnb-php-oop/Public/admin/users.php"
                   class="flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-semibold border border-white/10 bg-black/20 hover:bg-white/5 transition">
                    <span>Users</span><span class="text-xs text-white/40">Manage</span>
                </a>

                <a href="/airbnb-php-oop/Public/admin/rentals.php"
                   class="flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-semibold border border-white/10 bg-black/20 hover:bg-white/5 transition">
                    <span>Rentals</span><span class="text-xs text-white/40">Manage</span>
                </a>

                <a href="/airbnb-php-oop/Public/admin/bookings.php"
                   class="flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-semibold border border-white/10 bg-black/20 hover:bg-white/5 transition">
                    <span>Bookings</span><span class="text-xs text-white/40">Manage</span>
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
        <main class="flex-1 px-4 py-6 md:px-10 md:py-10 space-y-6">

            <header class="rounded-3xl border border-white/10 bg-white/[0.03] p-6">
                <div class="text-sm text-white/70">Admin Dashboard</div>
                <h1 class="mt-1 text-2xl font-bold">Welcome, <?= h($adminName) ?></h1>
                <p class="mt-2 text-sm text-white/60">Manage users, rentals and bookings.</p>
            </header>

            <!-- TOP ROW -->
            <section class="grid grid-cols-1 gap-6 lg:grid-cols-4">
                <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-6">
                    <div class="text-sm text-white/60">Users</div>
                    <div class="mt-2 text-3xl font-bold"><?= (int)$stats['users'] ?></div>
                    <div class="mt-2 text-xs text-white/50"><?= (int)$stats['activeUsers'] ?> active</div>
                    <a class="mt-4 inline-flex text-sm font-semibold text-white/80 hover:text-rose-200 underline underline-offset-4"
                       href="/airbnb-php-oop/Public/admin/users.php">Manage users</a>
                </div>

                <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-6">
                    <div class="text-sm text-white/60">Rentals</div>
                    <div class="mt-2 text-3xl font-bold"><?= (int)$stats['rentals'] ?></div>
                    <div class="mt-2 text-xs text-white/50"><?= (int)$stats['activeRentals'] ?> active</div>
                    <a class="mt-4 inline-flex text-sm font-semibold text-white/80 hover:text-rose-200 underline underline-offset-4"
                       href="/airbnb-php-oop/Public/admin/rentals.php">Manage rentals</a>
                </div>

                <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-6 lg:col-span-2">
                    <div class="text-sm text-white/60">Quick actions</div>
                    <div class="mt-4 flex flex-col gap-2">
                        <a href="/airbnb-php-oop/Public/admin/users.php"
                           class="rounded-2xl bg-gradient-to-br from-rose-500 to-red-600 px-4 py-3 text-sm font-bold text-center">
                            Manage users
                        </a>
                        <a href="/airbnb-php-oop/Public/admin/rentals.php"
                           class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-white/80 hover:bg-white/10 transition text-center">
                            Manage rentals
                        </a>
                    </div>
                </div>
            </section>

            <!-- STATS ROW -->
            <section class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="p-5 rounded-2xl bg-white/[0.03] border border-white/10">
                    <div class="text-sm text-white/60">Users</div>
                    <div class="text-3xl font-bold"><?= (int)$stats['users'] ?></div>
                </div>

                <div class="p-5 rounded-2xl bg-white/[0.03] border border-white/10">
                    <div class="text-sm text-white/60">Rentals</div>
                    <div class="text-3xl font-bold"><?= (int)$stats['rentals'] ?></div>
                </div>

                <div class="p-5 rounded-2xl bg-white/[0.03] border border-white/10">
                    <div class="text-sm text-white/60">Bookings</div>
                    <div class="text-3xl font-bold"><?= (int)$stats['bookings'] ?></div>
                </div>

                <div class="p-5 rounded-2xl bg-white/[0.03] border border-white/10">
                    <div class="text-sm text-white/60">Revenue</div>
                    <div class="text-3xl font-bold"><?= h($stats['revenue']) ?> MAD</div>
                </div>
            </section>

            <!-- TOP RENTALS -->
            <section class="mt-6 p-6 rounded-3xl bg-white/[0.03] border border-white/10">
                <h2 class="text-lg font-bold mb-4">Top Rentals</h2>

                <?php if(empty($topRentals)): ?>
                    <div class="text-sm text-white/60">No data yet.</div>
                <?php else: ?>
                    <?php foreach ($topRentals as $r): ?>
                        <div class="flex justify-between border-b border-white/10 py-2">
                            <span><?= h($r['title'] ?? '') ?></span>
                            <span class="font-semibold"><?= h($r['revenue'] ?? 0) ?> MAD</span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </section>

        </main>

    </div>
</div>
</body>
</html>
