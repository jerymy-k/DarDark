<?php
require_once __DIR__ . "/../../core/Session.php";
require_once __DIR__ . "/../../classes/Rental.php";
require_once __DIR__ . "/../../classes/User.php";

Session::start();

if (!Session::get('id')) {
    header("Location: /airbnb-php-oop/Public/login.php");
    exit();
}

$userId = (int) Session::get('id');
$user = User::getUserById($userId);
$travelerName = $user['name'];

$criteria = [
    'city' => $_GET['city'] ?? '',
    'min_price' => $_GET['min_price'] ?? '',
    'max_price' => $_GET['max_price'] ?? '',
    'guests' => $_GET['guests'] ?? '',
];

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
if ($page < 1)
    $page = 1;

$rentals = Rental::search($criteria, $page);
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

    <div class="flex min-h-screen">

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
                    class="flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-semibold bg-white/5 border border-white/10 hover:bg-white/10 transition">
                    <span>Dashboard</span><span class="text-xs text-white/40">Home</span>
                </a>



                <a href="/airbnb-php-oop/Public/traveler/my-bookings.php"
                    class="flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-semibold border border-white/10 bg-black/20 hover:bg-white/5 transition">
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
        <main class="flex-1 p-8 space-y-8">

            <!-- HEADER -->
            <header>
                <h1 class="text-2xl font-bold">Welcome, <?= htmlspecialchars($travelerName) ?></h1>
                <p class="text-white/60 text-sm">Search and explore rentals</p>
            </header>

            <!-- SEARCH FORM -->
            <section class="rounded-3xl border border-white/10 bg-white/[0.03] p-6">
                <h2 class="font-bold mb-4">Search rentals</h2>

                <form class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <input name="city" placeholder="City" value="<?= htmlspecialchars($criteria['city']) ?>"
                        class="rounded-xl bg-black/30 border border-white/10 px-4 py-3">

                    <input type="number" name="min_price" placeholder="Min price"
                        value="<?= htmlspecialchars($criteria['min_price']) ?>"
                        class="rounded-xl bg-black/30 border border-white/10 px-4 py-3">

                    <input type="number" name="max_price" placeholder="Max price"
                        value="<?= htmlspecialchars($criteria['max_price']) ?>"
                        class="rounded-xl bg-black/30 border border-white/10 px-4 py-3">

                    <input type="number" name="guests" placeholder="Guests"
                        value="<?= htmlspecialchars($criteria['guests']) ?>"
                        class="rounded-xl bg-black/30 border border-white/10 px-4 py-3">

                    <button class="rounded-xl bg-rose-600 px-4 py-3 font-bold">
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
                                    class="h-40 w-full object-cover rounded-xl mb-3">

                                <h3 class="font-bold"><?= htmlspecialchars($r['title']) ?></h3>
                                <p class="text-sm text-white/60">
                                    <?= htmlspecialchars($r['city']) ?> •
                                    <?= htmlspecialchars($r['price_per_night']) ?> / night
                                </p>

                                
                                <div class="mt-4 flex items-center justify-between">
                                    <a href="/airbnb-php-oop/Public/traveler/rental-details.php?id=<?= (int) $r['id'] ?>"
                                        class="rounded-xl bg-gradient-to-br from-rose-500 to-red-600 px-4 py-2 text-xs font-bold">
                                        View
                                    </a>

                                    <form method="POST" action="/airbnb-php-oop/actions/traveler/toggleFavorite.php">
                                        <input type="hidden" name="rental_id" value="<?= (int) $r['id'] ?>">
                                        <input type="hidden" name="back" value="/airbnb-php-oop/Public/traveler/dashboard.php">
                                        <button
                                            class="rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-xs font-bold hover:bg-white/10 transition">
                                            ❤️ Favorite
                                        </button>
                                    </form>
                                </div>

                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>

            <?php
            $query = $_GET;
            ?>

            <div class="flex justify-center gap-4 mt-6">

                <?php if ($page > 1): ?>
                    <?php $query['page'] = $page - 1; ?>
                    <a href="?<?= http_build_query($query) ?>"
                        class="px-4 py-2 rounded-xl border border-white/10 bg-white/5">
                        Prev
                    </a>
                <?php endif; ?>

                <?php $query['page'] = $page + 1; ?>
                <a href="?<?= http_build_query($query) ?>"
                    class="px-4 py-2 rounded-xl border border-white/10 bg-white/5">
                    Next
                </a>

            </div>


        </main>
    </div>

</body>

</html>