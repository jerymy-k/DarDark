<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../../core/Session.php";
require_once __DIR__ . "/../../classes/Rental.php";
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
$userInfo = User::getUserById($hostId);
$hostName = $userInfo['name'];
$errer = Session::get("errer");
$succes = Session::get("succes");
Session::remove("errer");
Session::remove("succes");

$properties = Rental::getAllByHost($hostId);
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Properties • Host • DarDark</title>
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
                    class="flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-semibold border border-white/10 bg-black/20 hover:bg-white/5 transition">
                    <span>Dashboard</span><span class="text-xs text-white/40">Home</span>
                </a>
                
                <a href="/airbnb-php-oop/Public/host/add-property.php"
                class="flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-semibold border border-white/10 bg-black/20 hover:bg-white/5 transition">
                <span>Add Property</span><span class="text-xs text-white/40">Create</span>
            </a>
            
            <a href="/airbnb-php-oop/Public/host/my-properties.php"
            class="flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-semibold bg-white/5 border border-white/10 hover:bg-white/10 transition">
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
                        <div class="text-sm text-white/70">My Properties</div>
                        <h1 class="mt-1 text-2xl font-bold tracking-tight">Manage your listings</h1>
                        <p class="mt-2 text-sm text-white/60">Hello <?= htmlspecialchars($hostName) ?> — you can edit or
                            delete your properties here.</p>
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="/airbnb-php-oop/Public/host/add-property.php"
                            class="rounded-2xl bg-gradient-to-br from-rose-500 to-red-600 px-4 py-3 text-sm font-bold shadow-lg shadow-rose-500/20 hover:brightness-110 transition">
                            Add Property
                        </a>
                        <a href="/airbnb-php-oop/Public/host/dashboard.php"
                            class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-white/80 hover:bg-white/10 transition">
                            Back
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

                <section class="rounded-3xl border border-white/10 bg-white/[0.03] p-6 backdrop-blur">
                    <div class="flex items-center justify-between gap-3 flex-wrap">
                        <h2 class="text-lg font-bold tracking-tight">All my properties</h2>
                        <span class="text-xs text-white/50"><?= count($properties) ?> total</span>
                    </div>

                    <div class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
                        <?php if (empty($properties)): ?>
                            <div
                                class="md:col-span-2 xl:col-span-3 rounded-2xl border border-white/10 bg-black/20 px-4 py-3 text-sm text-white/60">
                                You don’t have any properties yet. Click <span class="text-white font-semibold">Add
                                    Property</span> to create your first one.
                            </div>
                        <?php else: ?>
                            <?php foreach ($properties as $p): ?>
                                <div class="rounded-3xl border border-white/10 bg-black/20 p-5">

                                    <img src="<?= htmlspecialchars($p['cover_path']) ?>" alt="Property image"
                                        class="mb-3 h-40 w-full rounded-2xl object-cover border border-white/10" />
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <div class="text-base font-bold text-white/90">
                                                <?= htmlspecialchars($p['title'] ?? '') ?>
                                            </div>
                                            <div class="mt-1 text-sm text-white/60">
                                                <?= htmlspecialchars($p['city'] ?? '') ?>
                                                • <span class="text-white/80 font-semibold">
                                                    <?= htmlspecialchars($p['price_per_night'] ?? '') ?>
                                                </span> / night
                                            </div>
                                        </div>

                                        <span
                                            class="inline-flex rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs font-semibold text-white/70">
                                            <?= htmlspecialchars($p['status']) ?>
                                        </span>
                                    </div>

                                    <div class="mt-3 text-sm text-white/70">
                                        Max guests: <?= htmlspecialchars($p['max_guests']) ?>
                                    </div>

                                    <div class="mt-2 text-sm text-white/70 line-clamp-3">
                                        <?= htmlspecialchars($p['description']) ?>
                                    </div>

                                    <div class="mt-4 flex items-center justify-between">
                                        <div class="text-xs text-white/40">
                                            <?= htmlspecialchars($p['created_at']) ?>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <button type="button"
                                                class="btnEdit rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-xs font-semibold text-white/80 hover:bg-white/10 transition"
                                                data-id="<?= (int) $p['id'] ?>"
                                                data-title="<?= htmlspecialchars($p['title'], ENT_QUOTES) ?>"
                                                data-city="<?= htmlspecialchars($p['city'], ENT_QUOTES) ?>"
                                                data-address="<?= htmlspecialchars($p['address'] ?? '', ENT_QUOTES) ?>"
                                                data-price="<?= htmlspecialchars($p['price_per_night'], ENT_QUOTES) ?>"
                                                data-guests="<?= htmlspecialchars($p['max_guests'], ENT_QUOTES) ?>"
                                                data-status="<?= htmlspecialchars($p['status'], ENT_QUOTES) ?>"
                                                data-description="<?= htmlspecialchars($p['description'], ENT_QUOTES) ?>"
                                                data-cover="<?= htmlspecialchars($p['cover_path'] ?? '', ENT_QUOTES) ?>">
                                                Edit
                                            </button>

                                            <form method="POST" action="/airbnb-php-oop/actions/host/deleteProperty.php"
                                                onsubmit="return confirm('Delete this property?');">
                                                <input type="hidden" name="id" value="<?= (int) $p['id'] ?>">
                                                <button type="submit"
                                                    class="rounded-xl border border-red-500/30 bg-red-500/10 px-3 py-2 text-xs font-semibold text-red-200 hover:bg-red-500/15 transition">
                                                    Delete
                                                </button>
                                            </form>
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
    <div id="editModal" class="hidden fixed inset-0 z-50">
  <div class="absolute inset-0 bg-black/70"></div>

  <div class="relative mx-auto min-h-screen max-w-3xl px-4 py-10 overflow-y-auto">
    <div class="w-full rounded-3xl border border-white/10 bg-zinc-950/90 backdrop-blur p-6 md:p-8">

      <div class="flex items-start justify-between gap-4">
        <div>
          <h2 class="text-xl font-bold tracking-tight">Edit Property</h2>
          <p class="mt-1 text-sm text-white/60">Update your listing information.</p>
        </div>
        <button type="button" id="closeModal"
          class="rounded-2xl border border-white/10 bg-white/5 px-3 py-2 text-sm text-white/80 hover:bg-white/10 transition">
          ✕
        </button>
      </div>

      <form id="editForm" class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2 max-h-[75vh] overflow-y-auto pr-1"
            method="POST" action="/airbnb-php-oop/actions/host/updateProperty.php">

        <input type="hidden" name="id" id="edit_id">

        <div class="md:col-span-2">
          <div class="rounded-3xl border border-white/10 bg-black/20 p-4">
            <div class="text-sm font-semibold text-white/90">Preview</div>

            <div class="mt-3 overflow-hidden rounded-2xl border border-white/10 bg-white/5">
              <img id="coverPreview" src="" alt="Cover preview" class="h-56 w-full object-cover" />
            </div>

            <div id="coverHint" class="mt-2 text-xs text-white/50"></div>
          </div>
        </div>

        <div class="md:col-span-2">
          <label class="mb-2 block text-sm font-semibold text-white/90">Cover Image URL</label>
          <input name="cover_path" id="edit_cover" type="url" placeholder="https://..."
            class="w-full rounded-2xl border border-white/10 bg-black/30 px-4 py-3 text-sm text-white outline-none transition focus:border-rose-500/40 focus:ring-4 focus:ring-rose-500/10" />
        </div>

        <div class="md:col-span-2">
          <label class="mb-2 block text-sm font-semibold text-white/90">Title</label>
          <input name="title" id="edit_title" required
            class="w-full rounded-2xl border border-white/10 bg-black/30 px-4 py-3 text-sm text-white outline-none transition focus:border-rose-500/40 focus:ring-4 focus:ring-rose-500/10" />
        </div>

        <div>
          <label class="mb-2 block text-sm font-semibold text-white/90">City</label>
          <input name="city" id="edit_city" required
            class="w-full rounded-2xl border border-white/10 bg-black/30 px-4 py-3 text-sm text-white outline-none transition focus:border-rose-500/40 focus:ring-4 focus:ring-rose-500/10" />
        </div>

        <div>
          <label class="mb-2 block text-sm font-semibold text-white/90">Address (optional)</label>
          <input name="address" id="edit_address"
            class="w-full rounded-2xl border border-white/10 bg-black/30 px-4 py-3 text-sm text-white outline-none transition focus:border-rose-500/40 focus:ring-4 focus:ring-rose-500/10" />
        </div>

        <div>
          <label class="mb-2 block text-sm font-semibold text-white/90">Price per night</label>
          <input name="price_per_night" id="edit_price" type="number" min="0" step="1" required
            class="w-full rounded-2xl border border-white/10 bg-black/30 px-4 py-3 text-sm text-white outline-none transition focus:border-rose-500/40 focus:ring-4 focus:ring-rose-500/10" />
        </div>

        <div>
          <label class="mb-2 block text-sm font-semibold text-white/90">Max guests</label>
          <input name="max_guests" id="edit_guests" type="number" min="1" step="1" required
            class="w-full rounded-2xl border border-white/10 bg-black/30 px-4 py-3 text-sm text-white outline-none transition focus:border-rose-500/40 focus:ring-4 focus:ring-rose-500/10" />
        </div>

        <div>
          <label class="mb-2 block text-sm font-semibold text-white/90">Status</label>
          <select name="status" id="edit_status" required
            class="w-full appearance-none rounded-2xl border border-white/10 bg-black/30 px-4 py-3 text-sm text-white outline-none transition focus:border-rose-500/40 focus:ring-4 focus:ring-rose-500/10">
            <option value="ACTIVE">ACTIVE</option>
            <option value="PAUSED">PAUSED</option>
          </select>
        </div>

        <div class="md:col-span-2">
          <label class="mb-2 block text-sm font-semibold text-white/90">Description</label>
          <textarea name="description" id="edit_description" rows="4" required
            class="w-full rounded-2xl border border-white/10 bg-black/30 px-4 py-3 text-sm text-white outline-none transition focus:border-rose-500/40 focus:ring-4 focus:ring-rose-500/10"></textarea>
        </div>

        <div class="md:col-span-2 flex items-center justify-end gap-3 pt-2">
          <button type="button" id="cancelModal"
            class="rounded-2xl border border-white/10 bg-white/5 px-5 py-3 text-sm font-semibold text-white/80 hover:bg-white/10 transition">
            Cancel
          </button>
          <button type="submit"
            class="rounded-2xl bg-gradient-to-br from-rose-500 to-red-600 px-5 py-3 text-sm font-bold shadow-lg shadow-rose-500/20 hover:brightness-110 transition">
            Save changes
          </button>
        </div>

      </form>
    </div>
  </div>
</div>

    <script>
        const modal = document.getElementById('editModal');
        const closeBtn = document.getElementById('closeModal');
        const cancelBtn = document.getElementById('cancelModal');
        const preview = document.getElementById('coverPreview');
        const coverInput = document.getElementById('edit_cover');
        const hint = document.getElementById('coverHint');

        const setPreview = (url) => {
            const v = (url || '').trim();
            if (!v) {
                preview.src = '';
                preview.classList.add('hidden');
                hint.textContent = 'No image URL';
                return;
            }
            preview.src = v;
            preview.classList.remove('hidden');
            hint.textContent = v;
        };

        const openModal = () => {
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        };

        const closeModal = () => {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        };

        document.querySelectorAll('.btnEdit').forEach(btn => {
            btn.addEventListener('click', () => {
                document.getElementById('edit_id').value = btn.dataset.id;
                document.getElementById('edit_title').value = btn.dataset.title || '';
                document.getElementById('edit_city').value = btn.dataset.city || '';
                document.getElementById('edit_address').value = btn.dataset.address || '';
                document.getElementById('edit_price').value = btn.dataset.price || '';
                document.getElementById('edit_guests').value = btn.dataset.guests || '';
                document.getElementById('edit_status').value = btn.dataset.status || 'ACTIVE';
                document.getElementById('edit_description').value = btn.dataset.description || '';
                coverInput.value = btn.dataset.cover || '';

                setPreview(coverInput.value);
                openModal();
            });
        });

        coverInput.addEventListener('input', () => setPreview(coverInput.value));

        closeBtn.addEventListener('click', closeModal);
        cancelBtn.addEventListener('click', closeModal);

        modal.addEventListener('click', (e) => {
            if (e.target === modal.firstElementChild) closeModal();
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal();
        });
    </script>

</body>

</html>