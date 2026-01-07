<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../../core/Session.php";
require_once __DIR__ . "/../../core/Database.php";
require_once __DIR__ . "/../../classes/User.php";

Session::start();

if (!Session::get('id')) {
    header("Location: /airbnb-php-oop/Public/login.php");
    exit();
}

if (Session::get('role') !== 'HOST') {
    header("Location: /airbnb-php-oop/Public/login.php");
    exit();
}

$hostId = (int) Session::get('id');
$conn = Database::getInstance()->getConnection();

$userInfo = User::getUserById($hostId);
$hostName = $userInfo['name'] ?? 'Host';

$errer = Session::get("errer");
$succes = Session::get("succes");
Session::remove("errer");
Session::remove("succes");

$stmt = $conn->prepare("SELECT COUNT(*) FROM rentals WHERE host_id = ?");
$stmt->execute([$hostId]);
$totalProperties = (int) $stmt->fetchColumn();

$stmt = $conn->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0");
// $stmt->execute([$hostId]);
$unreadNotifications = (int) $stmt->fetchColumn();

$stmt = $conn->prepare("SELECT id, title, city, price_per_night, status, cover_path, created_at
                        FROM rentals
                        WHERE host_id = ?
                        ORDER BY created_at DESC
                        LIMIT 6");
$stmt->execute([$hostId]);
$latestProperties = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $conn->prepare("SELECT id, message, is_read, created_at
                        FROM notifications
                        WHERE user_id = ?
                        ORDER BY created_at DESC
                        LIMIT 5");
// $stmt->execute([$hostId]);
$latestNotifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Host Dashboard • DarDark</title>
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
                        class="flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-semibold bg-white/5 border border-white/10 hover:bg-white/10 transition">
                        <span>Dashboard</span><span class="text-xs text-white/40">Home</span>
                    </a>

                    <a href="/airbnb-php-oop/Public/host/add-property.php"
                        class="flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-semibold border border-white/10 bg-black/20 hover:bg-white/5 transition">
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

            <main class="flex-1 min-h-screen w-full px-4 py-6 md:px-10 md:py-10 space-y-6">

                <header
                    class="flex flex-col gap-4 rounded-3xl border border-white/10 bg-white/[0.03] p-6 backdrop-blur md:flex-row md:items-center md:justify-between">
                    <div>
                        <div class="text-sm text-white/70">Host Dashboard</div>
                        <h1 class="mt-1 text-2xl font-bold tracking-tight">Welcome, <?= htmlspecialchars($hostName) ?>
                        </h1>
                        <p class="mt-2 text-sm text-white/60">Manage your properties and view the latest notifications.
                        </p>
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="/airbnb-php-oop/Public/host/add-property.php"
                            class="rounded-2xl bg-gradient-to-br from-rose-500 to-red-600 px-4 py-3 text-sm font-bold shadow-lg shadow-rose-500/20 hover:brightness-110 transition">
                            Add Property
                        </a>
                        <a href="/airbnb-php-oop/Public/host/host-profile.php"
                            class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-white/80 hover:bg-white/10 transition">
                            My Profile
                        </a>
                    </div>
                </header>

                <?php if ($errer): ?>
                    <div class="rounded-2xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-200">
                        <?= htmlspecialchars($errer) ?>
                    </div>
                <?php endif; ?>

                <?php if ($succes): ?>
                    <div
                        class="rounded-2xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
                        <?= htmlspecialchars($succes) ?>
                    </div>
                <?php endif; ?>

                <section class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                    <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-6 backdrop-blur">
                        <div class="text-sm text-white/60">Total Properties</div>
                        <div class="mt-2 text-3xl font-bold"><?= $totalProperties ?></div>
                        <a href="/airbnb-php-oop/Public/host/my-properties.php"
                            class="mt-4 inline-flex text-sm font-semibold text-white/80 hover:text-rose-200 underline underline-offset-4">
                            View my properties
                        </a>
                    </div>

                    <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-6 backdrop-blur">
                        <div class="text-sm text-white/60">Unread Notifications</div>
                        <div class="mt-2 text-3xl font-bold"><?= $unreadNotifications ?></div>
                        <a href="/airbnb-php-oop/Public/host/notifications.php"
                            class="mt-4 inline-flex text-sm font-semibold text-white/80 hover:text-rose-200 underline underline-offset-4">
                            View notifications
                        </a>
                    </div>

                    <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-6 backdrop-blur">
                        <div class="text-sm text-white/60">Quick Actions</div>
                        <div class="mt-4 flex flex-col gap-2">
                            <a href="/airbnb-php-oop/Public/host/add-property.php"
                                class="rounded-2xl bg-gradient-to-br from-rose-500 to-red-600 px-4 py-3 text-sm font-bold shadow-lg shadow-rose-500/20 hover:brightness-110 transition text-center">
                                Add Property
                            </a>
                            <a href="/airbnb-php-oop/Public/host/my-properties.php"
                                class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-white/80 hover:bg-white/10 transition text-center">
                                Manage Properties
                            </a>
                        </div>
                    </div>
                </section>

                <section class="grid grid-cols-1 gap-6 xl:grid-cols-2">
                    <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-6 backdrop-blur">
                        <div class="flex items-center justify-between gap-3">
                            <h2 class="text-lg font-bold tracking-tight">Latest notifications</h2>
                            <a href="/airbnb-php-oop/Public/host/notifications.php"
                                class="text-sm font-semibold text-white/70 hover:text-rose-200 underline underline-offset-4">
                                See all
                            </a>
                        </div>

                        <div class="mt-4 space-y-3">
                            <?php if (empty($latestNotifications)): ?>
                                <div class="rounded-2xl border border-white/10 bg-black/20 px-4 py-3 text-sm text-white/60">
                                    No notifications yet.
                                </div>
                            <?php else: ?>
                                <?php foreach ($latestNotifications as $n): ?>
                                    <div class="rounded-2xl border border-white/10 bg-black/20 px-4 py-3">
                                        <div class="flex items-center justify-between gap-3">
                                            <div class="text-sm font-semibold">
                                                <?= htmlspecialchars($n['message'] ?? '') ?>
                                            </div>
                                            <div class="text-xs text-white/50">
                                                <?= htmlspecialchars($n['created_at'] ?? '') ?>
                                            </div>
                                        </div>
                                        <div class="mt-1 text-xs text-white/60">
                                            Status: <?= ((int) ($n['is_read'] ?? 0) === 1) ? 'Read' : 'Unread' ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-6 backdrop-blur">
                        <div class="flex items-center justify-between gap-3">
                            <h2 class="text-lg font-bold tracking-tight">Latest properties</h2>
                            <a href="/airbnb-php-oop/Public/host/my-properties.php"
                                class="text-sm font-semibold text-white/70 hover:text-rose-200 underline underline-offset-4">
                                Manage
                            </a>
                        </div>

                        <div class="mt-4 grid grid-cols-1 gap-3">
                            <?php if (empty($latestProperties)): ?>
                                <div class="rounded-2xl border border-white/10 bg-black/20 px-4 py-3 text-sm text-white/60">
                                    You don’t have any properties yet.
                                </div>
                            <?php else: ?>
                                <?php foreach ($latestProperties as $p): ?>
                                    <div class="rounded-2xl border border-white/10 bg-black/20 p-4">
                                        <div class="flex items-start justify-between gap-3">
                                            <div class="min-w-0">
                                                <div class="text-sm font-bold truncate">
                                                    <?= htmlspecialchars($p['title'] ?? '') ?></div>
                                                <div class="mt-1 text-xs text-white/60">
                                                    <?= htmlspecialchars($p['city'] ?? '') ?>
                                                    • <?= htmlspecialchars($p['price_per_night'] ?? '') ?> / night
                                                </div>
                                                <div class="mt-1 text-xs text-white/50">
                                                    <?= htmlspecialchars($p['status'] ?? 'ACTIVE') ?>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2 shrink-0">
                                                <a href="/airbnb-php-oop/Public/host/my-properties.php"
                                                    class="rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-xs font-semibold text-white/80 hover:bg-white/10 transition">
                                                    Open
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <div class="mt-4">
                            <a href="/airbnb-php-oop/Public/host/add-property.php"
                                class="inline-flex w-full items-center justify-center rounded-2xl bg-gradient-to-br from-rose-500 to-red-600 px-4 py-3 text-sm font-bold shadow-lg shadow-rose-500/20 hover:brightness-110 transition">
                                Add another property
                            </a>
                        </div>
                    </div>
                </section>

            </main>
        </div>
    </div>
</body>

</html>