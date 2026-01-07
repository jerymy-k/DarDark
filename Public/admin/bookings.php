<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../../core/Session.php";
require_once __DIR__ . "/../../classes/Booking.php";
require_once __DIR__ . "/../../classes/User.php";

Session::start();

if (!Session::get('id') || Session::get('role') !== 'ADMIN') {
    header("Location: /airbnb-php-oop/Public/login.php");
    exit();
}

$adminId = (int) Session::get('id');
$adminInfo = User::getUserById($adminId);
$adminName = $adminInfo['name'] ?? 'Admin';

$bookings = Booking::findAll(); // admin method

$err = Session::get('errer');
$ok  = Session::get('succes');
Session::remove('errer');
Session::remove('succes');

function h($v){ return htmlspecialchars((string)$v); }
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Bookings • Admin • DarDark</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="icon" type="image/png" href="/airbnb-php-oop/Public/img/DarDarkLogo.png">
  <style>
    body { font-family: "Plus Jakarta Sans", system-ui, -apple-system, Segoe UI, Roboto, sans-serif }
    * { scrollbar-width: none }
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

      <!-- SIDEBAR -->
      <aside class="hidden md:flex w-80 flex-col border-r border-white/10 bg-white/[0.03] backdrop-blur sticky top-0 h-screen">
        <div class="flex items-center gap-3 px-6 py-5 border-b border-white/10">
          <div class="h-10 w-10">
            <img src="/airbnb-php-oop/Public/img/DarDarkLogo.png" alt="DarDark" class="h-10 w-10 object-contain" />
          </div>
          <div>
            <div class="text-sm text-white/70">DarDark</div>
            <div class="text-base font-bold tracking-tight">Admin Space</div>
          </div>
        </div>

        <nav class="flex-1 px-4 py-5 space-y-2">
          <a href="/airbnb-php-oop/Public/admin/dashboard.php"
             class="flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-semibold border border-white/10 bg-black/20 hover:bg-white/5 transition">
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
             class="flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-semibold bg-white/5 border border-white/10 hover:bg-white/10 transition">
            <span>Bookings</span><span class="text-xs text-white/40">Manage</span>
          </a>

          <a href="/airbnb-php-oop/Public/admin/admin-profile.php"
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

        <header class="flex flex-col gap-4 rounded-3xl border border-white/10 bg-white/[0.03] p-6 backdrop-blur md:flex-row md:items-center md:justify-between">
          <div>
            <div class="text-sm text-white/70">Admin • Bookings</div>
            <h1 class="mt-1 text-2xl font-bold tracking-tight">All bookings</h1>
            <p class="mt-2 text-sm text-white/60">Welcome <?= h($adminName) ?> — you can cancel any confirmed booking.</p>
          </div>

          <a href="/airbnb-php-oop/Public/admin/dashboard.php"
             class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-white/80 hover:bg-white/10 transition">
            Back to Dashboard
          </a>
        </header>

        <?php if($err): ?>
          <div class="rounded-2xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-200">
            <?= h($err) ?>
          </div>
        <?php endif; ?>

        <?php if($ok): ?>
          <div class="rounded-2xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
            <?= h($ok) ?>
          </div>
        <?php endif; ?>

        <section class="rounded-3xl border border-white/10 bg-white/[0.03] p-6 backdrop-blur">
          <div class="flex items-center justify-between gap-3 flex-wrap">
            <h2 class="text-lg font-bold tracking-tight">Bookings list</h2>
            <span class="text-xs text-white/50"><?= count($bookings) ?> total</span>
          </div>

          <div class="mt-5 grid grid-cols-1 gap-4">
            <?php if(empty($bookings)): ?>
              <div class="rounded-2xl border border-white/10 bg-black/20 px-4 py-3 text-sm text-white/60">
                No bookings yet.
              </div>
            <?php else: ?>
              <?php foreach($bookings as $b): ?>
                <div class="rounded-3xl border border-white/10 bg-black/20 p-5">
                  <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                    <div class="min-w-0">
                      <div class="flex items-center gap-2 flex-wrap">
                        <div class="text-base font-bold text-white/90 truncate">
                          <?= h($b['title'] ?? 'Rental') ?>
                        </div>
                        <span class="inline-flex rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs font-semibold text-white/70">
                          <?= h($b['status'] ?? '') ?>
                        </span>
                      </div>

                      <div class="mt-2 text-sm text-white/70">
                        <b>Dates:</b> <?= h($b['start_date'] ?? '') ?> → <?= h($b['end_date'] ?? '') ?>
                      </div>

                      <div class="mt-1 text-sm text-white/70">
                        <b>User:</b> <?= h($b['user_email'] ?? '') ?>
                      </div>

                      <div class="mt-1 text-sm text-white/70">
                        <b>Total:</b> <?= h($b['total_price'] ?? '') ?> MAD
                      </div>
                    </div>

                    <div class="flex items-center gap-2">
                      <?php if(($b['status'] ?? '') === 'CONFIRMED'): ?>
                        <form method="POST" action="/airbnb-php-oop/actions/admin/cancelBooking.php"
                              onsubmit="return confirm('Cancel this booking?');">
                          <input type="hidden" name="booking_id" value="<?= (int)($b['id'] ?? 0) ?>">
                          <button class="rounded-2xl border border-red-500/30 bg-red-500/10 px-4 py-2 text-xs font-bold text-red-200 hover:bg-red-500/15 transition">
                            Cancel
                          </button>
                        </form>
                      <?php else: ?>
                        <span class="text-xs text-white/40">No action</span>
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
