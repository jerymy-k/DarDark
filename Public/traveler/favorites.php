<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../../core/Session.php";
require_once __DIR__ . "/../../classes/User.php";
require_once __DIR__ . "/../../classes/Favorite.php";

Session::start();

if (!Session::get('id')) {
    header("Location: /airbnb-php-oop/Public/login.php");
    exit();
}

$userId = (int) Session::get('id');
$userInfo = User::getUserById($userId);
$travelerName = $userInfo['name'] ?? 'Traveler';

$err = Session::get("errer");
$ok  = Session::get("succes");
Session::remove("errer");
Session::remove("succes");

$favorites = Favorite::findUserFavorites($userId);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Favorites • Traveler • DarDark</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
            class="flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-semibold border border-white/10 bg-black/20 hover:bg-white/5 transition">
            <span>My Bookings</span><span class="text-xs text-white/40">Trips</span>
        </a>
        
        <a href="/airbnb-php-oop/Public/traveler/favorites.php"
        class="flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-semibold bg-white/5 border border-white/10 hover:bg-white/10 transition">
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

            <header class="rounded-3xl border border-white/10 bg-white/[0.03] p-6 backdrop-blur flex items-center justify-between">
                <div>
                    <div class="text-sm text-white/70">My Favorites</div>
                    <h1 class="mt-1 text-2xl font-bold tracking-tight">Saved rentals</h1>
                    <p class="mt-2 text-sm text-white/60">Hello <?= htmlspecialchars($travelerName) ?> — manage your favorite rentals.</p>
                </div>
                <a href="/airbnb-php-oop/Public/traveler/dashboard.php"
                   class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-white/80 hover:bg-white/10 transition">
                    Back
                </a>
            </header>

            <?php if($err): ?>
                <div class="rounded-2xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-200">
                    <?= htmlspecialchars($err) ?>
                </div>
            <?php endif; ?>

            <?php if($ok): ?>
                <div class="rounded-2xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
                    <?= htmlspecialchars($ok) ?>
                </div>
            <?php endif; ?>

            <section class="rounded-3xl border border-white/10 bg-white/[0.03] p-6 backdrop-blur">
                <div class="flex items-center justify-between gap-3 flex-wrap">
                    <h2 class="text-lg font-bold tracking-tight">Your favorites</h2>
                    <span class="text-xs text-white/50"><?= count($favorites) ?> total</span>
                </div>

                <div class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
                    <?php if(empty($favorites)): ?>
                        <div class="md:col-span-2 xl:col-span-3 rounded-2xl border border-white/10 bg-black/20 px-4 py-3 text-sm text-white/60">
                            You don’t have favorites yet.
                        </div>
                    <?php else: ?>
                        <?php foreach($favorites as $r): ?>
                            <div class="rounded-3xl border border-white/10 bg-black/20 p-5">
                                <img src="<?= htmlspecialchars($r['cover_path'] ?? '') ?>"
                                     class="h-40 w-full object-cover rounded-2xl mb-3 border border-white/10" />

                                <div class="font-bold truncate"><?= htmlspecialchars($r['title'] ?? '') ?></div>
                                <div class="text-sm text-white/60">
                                    <?= htmlspecialchars($r['city'] ?? '') ?> • <?= htmlspecialchars($r['price_per_night'] ?? '') ?> / night
                                </div>

                                <div class="mt-4 flex items-center justify-between gap-2">
                                    <a href="/airbnb-php-oop/Public/traveler/rental-details.php?id=<?= (int)($r['id'] ?? 0) ?>"
                                       class="rounded-xl bg-gradient-to-br from-rose-500 to-red-600 px-4 py-2 text-xs font-bold">
                                        View
                                    </a>

                                    <form method="POST" action="/airbnb-php-oop/actions/traveler/toggleFavorite.php">
                                        <input type="hidden" name="rental_id" value="<?= (int)($r['id'] ?? 0) ?>">
                                        <input type="hidden" name="back" value="/airbnb-php-oop/Public/traveler/favorites.php">
                                        <button class="rounded-xl border border-red-500/30 bg-red-500/10 px-4 py-2 text-xs font-bold text-red-200 hover:bg-red-500/15 transition">
                                            Remove
                                        </button>
                                    </form>
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
