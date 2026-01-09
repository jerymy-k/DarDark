<?php
declare(strict_types=1);

require_once __DIR__ . "/../core/Session.php";
require_once __DIR__ . "/../classes/Rental.php";

Session::start();

$isLogged = (bool) Session::get('id');

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    header("Location: /airbnb-php-oop/Public/index.php");
    exit();
}

// Récupérer les infos du rental (adapte selon ta méthode)
$rental = Rental::getById($id);
if (!$rental) {
    header("Location: /airbnb-php-oop/Public/index.php");
    exit();
}

$currentUrl = $_SERVER['REQUEST_URI'] ?? '/airbnb-php-oop/Public/index.php';

function h($v): string {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= h($rental['title'] ?? 'Rental details') ?> • DarDark</title>

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

<header class="border-b border-white/10 bg-white/[0.03] backdrop-blur sticky top-0 z-50">
    <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between gap-4">
        <a href="/airbnb-php-oop/Public/index.php" class="flex items-center gap-3">
            <img src="/airbnb-php-oop/Public/img/DarDarkLogo.png" class="h-9 w-9 object-contain" alt="DarDark">
            <div>
                <div class="text-sm text-white/70">DarDark</div>
                <div class="text-base font-bold tracking-tight">Rental details</div>
            </div>
        </a>

        <div class="flex items-center gap-2">
            <?php if ($isLogged): ?>
                <a href="/airbnb-php-oop/Public/traveler/dashboard.php"
                   class="rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm font-semibold hover:bg-white/10 transition">
                    Dashboard
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

    <!-- Cover -->
    <section class="rounded-3xl border border-white/10 bg-black/20 overflow-hidden">
        <img src="<?= h($rental['cover_path'] ?? '') ?>"
             class="h-[340px] w-full object-cover" alt="cover">

        <div class="p-6 space-y-2">
            <h1 class="text-2xl font-bold"><?= h($rental['title'] ?? '') ?></h1>
            <p class="text-white/70 text-sm">
                <?= h($rental['city'] ?? '') ?> • <?= h($rental['price_per_night'] ?? '') ?> / night
            </p>
        </div>
    </section>

    <!-- Info + actions -->
    <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Details -->
        <div class="lg:col-span-2 rounded-3xl border border-white/10 bg-white/[0.03] p-6 space-y-4">
            <h2 class="font-bold text-lg">About this rental</h2>

            <p class="text-white/70 leading-relaxed">
                <?= nl2br(h($rental['description'] ?? 'No description.')) ?>
            </p>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 pt-2">
                <div class="rounded-2xl border border-white/10 bg-black/20 p-4">
                    <div class="text-xs text-white/50">Guests</div>
                    <div class="font-bold"><?= h($rental['max_guests'] ?? '-') ?></div>
                </div>

                <div class="rounded-2xl border border-white/10 bg-black/20 p-4">
                    <div class="text-xs text-white/50">Bedrooms</div>
                    <div class="font-bold"><?= h($rental['bedrooms'] ?? '-') ?></div>
                </div>

                <div class="rounded-2xl border border-white/10 bg-black/20 p-4">
                    <div class="text-xs text-white/50">Bathrooms</div>
                    <div class="font-bold"><?= h($rental['bathrooms'] ?? '-') ?></div>
                </div>

                <div class="rounded-2xl border border-white/10 bg-black/20 p-4">
                    <div class="text-xs text-white/50">City</div>
                    <div class="font-bold"><?= h($rental['city'] ?? '-') ?></div>
                </div>
            </div>
        </div>

        <!-- Actions card -->
        <aside class="rounded-3xl border border-white/10 bg-white/[0.03] p-6 space-y-4 h-fit">
            <div>
                <div class="text-white/60 text-sm">Price</div>
                <div class="text-2xl font-bold"><?= h($rental['price_per_night'] ?? '') ?> <span class="text-sm text-white/60">/ night</span></div>
            </div>

            <!-- Favorite -->
            <?php if ($isLogged): ?>
                <form method="POST" action="/airbnb-php-oop/actions/traveler/toggleFavorite.php" class="w-full">
                    <input type="hidden" name="rental_id" value="<?= (int)$rental['id'] ?>">
                    <input type="hidden" name="back" value="<?= h($currentUrl) ?>">
                    <button class="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-bold hover:bg-white/10 transition">
                        ❤️ Add to favorites
                    </button>
                </form>
            <?php else: ?>
                <a href="/airbnb-php-oop/Public/login.php?back=<?= urlencode($currentUrl) ?>"
                   class="block text-center w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-bold hover:bg-white/10 transition">
                    ❤️ Add to favorites
                </a>
                <p class="text-xs text-white/50">Login required to favorite.</p>
            <?php endif; ?>

            <!-- Booking (exemple) -->
            <?php if ($isLogged): ?>
                <a href="/airbnb-php-oop/Public/traveler/booking.php?rental_id=<?= (int)$rental['id'] ?>"
                   class="block text-center w-full rounded-2xl bg-gradient-to-br from-rose-500 to-red-600 px-4 py-3 text-sm font-bold hover:opacity-90 transition">
                    Book now
                </a>
            <?php else: ?>
                <a href="/airbnb-php-oop/Public/login.php?back=<?= urlencode($currentUrl) ?>"
                   class="block text-center w-full rounded-2xl bg-gradient-to-br from-rose-500 to-red-600 px-4 py-3 text-sm font-bold hover:opacity-90 transition">
                    Book now
                </a>
                <p class="text-xs text-white/50">Login required to book.</p>
            <?php endif; ?>

            <a href="/airbnb-php-oop/Public/index.php"
               class="block text-center w-full rounded-2xl border border-white/10 bg-black/20 px-4 py-3 text-sm font-semibold hover:bg-white/5 transition">
                ← Back to rentals
            </a>
        </aside>
    </section>

</main>

</body>
</html>
