<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../../core/Session.php";
require_once __DIR__ . "/../../classes/AdminManager.php";

Session::start();
if (!Session::get('id') || Session::get('role') !== 'ADMIN') {
    header("Location: /airbnb-php-oop/Public/login.php");
    exit();
}

$users = AdminManager::allUsers();

$err = Session::get('errer');
$ok  = Session::get('succes');
Session::remove('errer');
Session::remove('succes');

function h($v){ return htmlspecialchars((string)$v); }
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Users • Admin • DarDark</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="icon" type="image/png" href="/airbnb-php-oop/Public/img/DarDarkLogo.png">
  <style>body{font-family:"Plus Jakarta Sans",system-ui,-apple-system,Segoe UI,Roboto,sans-serif}*{scrollbar-width:none}</style>
</head>

<body class="min-h-screen bg-zinc-950 text-zinc-100">
<div class="pointer-events-none fixed inset-0">
  <div class="absolute -top-40 left-1/2 h-[520px] w-[520px] -translate-x-1/2 rounded-full bg-rose-600/20 blur-3xl"></div>
  <div class="absolute bottom-0 right-0 h-[420px] w-[420px] rounded-full bg-red-500/10 blur-3xl"></div>
  <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,rgba(255,255,255,0.06),rgba(0,0,0,0)_55%)]"></div>
</div>

<div class="relative min-h-screen w-full">
  <div class="flex min-h-screen w-full">

    <aside class="hidden md:flex w-80 flex-col border-r border-white/10 bg-white/[0.03] backdrop-blur sticky top-0 h-screen">
      <div class="flex items-center gap-3 px-6 py-5 border-b border-white/10">
        <div class="h-10 w-10">
          <img src="/airbnb-php-oop/Public/img/DarDarkLogo.png" class="h-10 w-10 object-contain" alt="">
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
           class="flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-semibold bg-white/5 border border-white/10 hover:bg-white/10 transition">
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

    <main class="flex-1 px-4 py-6 md:px-10 md:py-10 space-y-6">
      <header class="rounded-3xl border border-white/10 bg-white/[0.03] p-6 backdrop-blur">
        <div class="text-sm text-white/70">Users</div>
        <h1 class="mt-1 text-2xl font-bold">Manage users</h1>
        <p class="mt-2 text-sm text-white/60">Activate/Deactivate users (admin control).</p>
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
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
          <?php foreach($users as $u): ?>
            <?php $active = ((int)($u['is_active'] ?? 1) === 1); ?>

            <div class="rounded-3xl border border-white/10 bg-black/20 p-5">
              <div class="flex items-start justify-between gap-3">
                <div class="min-w-0">
                  <div class="text-base font-bold truncate"><?= h($u['name'] ?? '') ?></div>
                  <div class="mt-1 text-sm text-white/60 truncate"><?= h($u['email'] ?? '') ?></div>
                </div>
                <span class="inline-flex rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs font-semibold text-white/70">
                  <?= h($u['role'] ?? '') ?>
                </span>
              </div>

              <div class="mt-3 text-sm text-white/70">
                Status:
                <span class="<?= $active ? 'text-emerald-200' : 'text-red-200' ?>">
                  <?= $active ? 'ACTIVE' : 'DISABLED' ?>
                </span>
              </div>

              <div class="mt-4 flex items-center gap-2">
                <form method="POST" action="/airbnb-php-oop/actions/admin/toggleUser.php"
                      onsubmit="return confirm('Change status for this user?');">
                  <input type="hidden" name="user_id" value="<?= (int)($u['id'] ?? 0) ?>">
                  <input type="hidden" name="is_active" value="<?= $active ? 0 : 1 ?>">

                  <button class="rounded-2xl px-4 py-2 text-xs font-bold border border-white/10 bg-white/5 hover:bg-white/10 transition">
                    <?= $active ? 'Disable' : 'Activate' ?>
                  </button>
                </form>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </section>
    </main>

  </div>
</div>
</body>
</html>
