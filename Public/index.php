<?php
declare(strict_types=1);

require_once __DIR__ . "/../core/Session.php";
require_once __DIR__ . "/../classes/Rental.php";

Session::start();

// connecté ?
$isLogged = (bool) Session::get('id');

// critères (GET)
$criteria = [
    'city'      => $_GET['city'] ?? '',
    'min_price' => $_GET['min_price'] ?? '',
    'max_price' => $_GET['max_price'] ?? '',
    'guests'    => $_GET['guests'] ?? '',
];

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
if ($page < 1) $page = 1;

// rentals
$rentals = Rental::search($criteria, $page);

// helper pour URL de retour
$currentUrl = $_SERVER['REQUEST_URI'] ?? '/airbnb-php-oop/Public/index.php';

// query pagination
$query = $_GET;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>DarDark • Rentals</title>

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

    <!-- TOP BAR -->
    <header class="border-b border-white/10 bg-white/[0.03] backdrop-blur sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between gap-4">
            <a href="/airbnb-php-oop/Public/index.php" class="flex items-center gap-3">
                <img src="/airbnb-php-oop/Public/img/DarDarkLogo.png" class="h-9 w-9 object-contain" alt="DarDark">
                <div>
                    <div class="text-sm text-white/70">DarDark</div>
                    <div class="text-base font-bold tracking-tight">Explore rentals</div>
                </div>
            </a>

            <div class="flex items-center gap-2">
                <?php if ($isLogged): ?>
                    <a href="/airbnb-php-oop/Public/traveler/dashboard.php"
                       class="rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm font-semibold hover:bg-white/10 transition">
                        My Dashboard
                    </a>
                    <a href="/airbnb-php-oop/actions/logout.php"
                       class="rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm font-semibold text-white/80 hover:bg-white/10 transition">
                        Logout
                    </a>
                <?php else: ?>
                    <a href="/airbnb-php-oop/Public/login.php?back=<?= urlencode($currentUrl) ?>"
                       class="rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm font-semibold hover:bg-white/10 transition">
                        Login
                    </a>
                    <a href="/airbnb-php-oop/Public/signup.php?back=<?= urlencode($currentUrl) ?>"
                       class="rounded-xl bg-gradient-to-br from-rose-500 to-red-600 px-4 py-2 text-sm font-bold">
                        Sign up
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-6 py-8 space-y-8">

        <!-- HERO -->
        <section class="rounded-3xl border border-white/10 bg-white/[0.03] p-6">
            <h1 class="text-2xl font-bold">Find your next stay</h1>
            <p class="text-white/60 text-sm mt-1">
                Browse rentals freely. To book or favorite, you’ll need an account.
            </p>

            <!-- SEARCH FORM -->
            <form class="grid grid-cols-1 md:grid-cols-5 gap-4 mt-5">
                <input name="city" placeholder="City" value="<?= htmlspecialchars($criteria['city']) ?>"
                       class="rounded-xl bg-black/30 border border-white/10 px-4 py-3 outline-none">

                <input type="number" name="min_price" placeholder="Min price" value="<?= htmlspecialchars($criteria['min_price']) ?>"
                       class="rounded-xl bg-black/30 border border-white/10 px-4 py-3 outline-none">

                <input type="number" name="max_price" placeholder="Max price" value="<?= htmlspecialchars($criteria['max_price']) ?>"
                       class="rounded-xl bg-black/30 border border-white/10 px-4 py-3 outline-none">

                <input type="number" name="guests" placeholder="Guests" value="<?= htmlspecialchars($criteria['guests']) ?>"
                       class="rounded-xl bg-black/30 border border-white/10 px-4 py-3 outline-none">

                <button class="rounded-xl bg-rose-600 px-4 py-3 font-bold hover:opacity-90 transition">
                    Search
                </button>
            </form>
        </section>

        <!-- RENTALS -->
        <section>
            <h2 class="font-bold mb-4">Available rentals</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                <?php if (empty($rentals)): ?>
                    <p class="text-white/60">No rentals found.</p>
                <?php else: ?>
                    <?php foreach ($rentals as $r): ?>
                        <div class="rounded-3xl border border-white/10 bg-black/20 p-5">
                            <img src="<?= htmlspecialchars($r['cover_path']) ?>"
                                 class="h-40 w-full object-cover rounded-xl mb-3" alt="cover">

                            <h3 class="font-bold"><?= htmlspecialchars($r['title']) ?></h3>
                            <p class="text-sm text-white/60">
                                <?= htmlspecialchars($r['city']) ?> • <?= htmlspecialchars($r['price_per_night']) ?> / night
                            </p>

                            <div class="mt-4 flex items-center justify-between gap-3">
                                <!-- VIEW: public ok -->
                                <a href="/airbnb-php-oop/Public/rental-details.php?id=<?= (int)$r['id'] ?>"
                                   class="rounded-xl bg-gradient-to-br from-rose-500 to-red-600 px-4 py-2 text-xs font-bold">
                                    View
                                </a>

                                <!-- FAVORITE: require login -->
                                <?php if ($isLogged): ?>
                                    <form method="POST" action="/airbnb-php-oop/actions/traveler/toggleFavorite.php">
                                        <input type="hidden" name="rental_id" value="<?= (int)$r['id'] ?>">
                                        <input type="hidden" name="back" value="<?= htmlspecialchars($currentUrl) ?>">
                                        <button class="rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-xs font-bold hover:bg-white/10 transition">
                                            ❤️ Favorite
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <a href="/airbnb-php-oop/Public/login.php?back=<?= urlencode($currentUrl) ?>"
                                       class="rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-xs font-bold hover:bg-white/10 transition">
                                        ❤️ Favorite
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- PAGINATION -->
            <div class="flex justify-center gap-4 mt-8">
                <?php if ($page > 1): ?>
                    <?php $query['page'] = $page - 1; ?>
                    <a href="?<?= http_build_query($query) ?>"
                       class="px-4 py-2 rounded-xl border border-white/10 bg-white/5 hover:bg-white/10 transition">
                        Prev
                    </a>
                <?php endif; ?>

                <?php $query['page'] = $page + 1; ?>
                <a href="?<?= http_build_query($query) ?>"
                   class="px-4 py-2 rounded-xl border border-white/10 bg-white/5 hover:bg-white/10 transition">
                    Next
                </a>
            </div>
        </section>

    </main>

</body>
</html>
