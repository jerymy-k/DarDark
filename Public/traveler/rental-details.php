<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . "/../../core/Session.php";
require_once __DIR__ . "/../../classes/Rental.php";

Session::start();

if (!Session::get('id')) {
    header("Location: /airbnb-php-oop/Public/login.php");
    exit();
}

$id = (int) $_GET['id'];
$rental = Rental::getById($id);

if (!$rental) {
    echo "Rental not found";
    exit();
}

function h($v)
{
    return htmlspecialchars((string) $v);
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($rental['title']) ?> • DarDark</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: "Plus Jakarta Sans", system-ui, -apple-system, Segoe UI, Roboto, sans-serif
        }

        * {
            scrollbar-width: none
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

    <main class="relative mx-auto max-w-6xl px-4 py-8 md:py-12">
        <div class="mb-6 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <a href="/airbnb-php-oop/Public/traveler/dashboard.php"
                class="inline-flex items-center gap-2 rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-white/80 hover:bg-white/10 transition">
                <span class="text-lg">←</span> Back
            </a>

            <div class="flex items-center gap-2">
                <span
                    class="inline-flex rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs font-semibold text-white/70">
                    ACTIVE
                </span>
            </div>
        </div>

        <section class="overflow-hidden rounded-3xl border border-white/10 bg-white/[0.03] backdrop-blur">
            <div class="grid grid-cols-1 lg:grid-cols-2">
                <div class="relative">
                    <img src="<?= h($rental['cover_path']) ?>" alt="Rental cover"
                        class="h-[320px] w-full object-cover lg:h-full">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent"></div>

                    <div class="absolute bottom-4 left-4 right-4">
                        <div class="flex flex-wrap items-center gap-2">
                            <span
                                class="inline-flex rounded-full border border-white/10 bg-black/40 px-3 py-1 text-xs font-semibold text-white/80">
                                <?= h($rental['city']) ?>
                            </span>
                            <span
                                class="inline-flex rounded-full border border-white/10 bg-black/40 px-3 py-1 text-xs font-semibold text-white/80">
                                Max guests: <?= h($rental['max_guests']) ?>
                            </span>
                            <span
                                class="inline-flex rounded-full border border-white/10 bg-black/40 px-3 py-1 text-xs font-semibold text-white/80">
                                <?= h($rental['price_per_night']) ?> / night
                            </span>
                        </div>
                    </div>
                </div>

                <div class="p-6 md:p-8">
                    <div class="flex flex-col gap-2">
                        <div class="text-sm text-white/60">Rental details</div>

                        <h1 class="text-2xl md:text-3xl font-bold tracking-tight">
                            <?= h($rental['title']) ?>
                        </h1>

                        <p class="text-sm text-white/70">
                            Hosted by <span class="font-semibold text-white/90"><?= h($rental['name']) ?></span>
                        </p>
                    </div>

                    <div class="mt-6 grid grid-cols-1 gap-3 sm:grid-cols-3">
                        <div class="rounded-2xl border border-white/10 bg-black/20 p-4">
                            <div class="text-xs text-white/60">City</div>
                            <div class="mt-1 text-sm font-semibold text-white/90"><?= h($rental['city']) ?></div>
                        </div>

                        <div class="rounded-2xl border border-white/10 bg-black/20 p-4">
                            <div class="text-xs text-white/60">Price / night</div>
                            <div class="mt-1 text-sm font-semibold text-white/90"><?= h($rental['price_per_night']) ?>
                            </div>
                        </div>

                        <div class="rounded-2xl border border-white/10 bg-black/20 p-4">
                            <div class="text-xs text-white/60">Guests</div>
                            <div class="mt-1 text-sm font-semibold text-white/90"><?= h($rental['max_guests']) ?></div>
                        </div>
                    </div>

                    <div class="mt-6 rounded-3xl border border-white/10 bg-black/20 p-5">
                        <div class="text-sm font-semibold text-white/90">Description</div>
                        <p class="mt-2 text-sm leading-6 text-white/70">
                            <?= h($rental['description']) ?>
                        </p>
                    </div>

                    <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-end">
                        <a href="/airbnb-php-oop/Public/traveler/dashboard.php"
                            class="rounded-2xl border border-white/10 bg-white/5 px-5 py-3 text-sm font-semibold text-white/80 hover:bg-white/10 transition text-center">
                            Back to list
                        </a>

                        <button type="button"
                            class="rounded-2xl bg-gradient-to-br from-rose-500 to-red-600 px-5 py-3 text-sm font-bold shadow-lg shadow-rose-500/20 hover:brightness-110 transition">
                            Reserve (later)
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <div class="mt-6 text-center text-xs text-white/40">
            DarDark • Safe & simple stays
        </div>
    </main>
    <?php
    $pricePerNight = (float) ($rental['price_per_night'] ?? 0);
    ?>

    <div class="mt-8 rounded-3xl border border-white/10 bg-white/[0.03] p-6">
        <h2 class="text-lg font-bold">Book this rental</h2>
        <p class="mt-1 text-sm text-white/60">Choose your dates. Total price is calculated automatically.</p>

        <form method="POST" action="/airbnb-php-oop/actions/traveler/createBooking.php"
            class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-3">
            <input type="hidden" name="rental_id" value="<?= (int) $rental['id'] ?>">
            <input type="hidden" name="price_per_night" value="<?= htmlspecialchars((string) $pricePerNight) ?>">

            <div>
                <label class="text-sm font-semibold">Start date</label>
                <input required type="date" name="start_date"
                    class="w-full mt-1 rounded-2xl border border-white/10 bg-black/30 px-4 py-3 text-sm text-white outline-none">
            </div>

            <div>
                <label class="text-sm font-semibold">End date</label>
                <input required type="date" name="end_date"
                    class="w-full mt-1 rounded-2xl border border-white/10 bg-black/30 px-4 py-3 text-sm text-white outline-none">
            </div>

            <div class="flex items-end">
                <button type="submit"
                    class="w-full rounded-2xl bg-gradient-to-br from-rose-500 to-red-600 px-6 py-3 text-sm font-bold">
                    Reserve
                </button>
            </div>
        </form>
    </div>

</body>

</html>