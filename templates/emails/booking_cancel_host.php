<div style="font-family:Arial,sans-serif; line-height:1.6">
  <h2>Booking Cancelled ❌</h2>

  <p>Hello <b><?= htmlspecialchars($host['name'] ?? '') ?></b>,</p>

  <p>A booking has been cancelled.</p>

  <p><b>Rental:</b> <?= htmlspecialchars($rental['title'] ?? '') ?></p>

  <p><b>Traveler:</b>
    <?= htmlspecialchars($traveler['name'] ?? '') ?>
    (<?= htmlspecialchars($traveler['email'] ?? '') ?>)
  </p>

  <p><b>Dates:</b>
    <?= htmlspecialchars($booking['start_date'] ?? '') ?>
    →
    <?= htmlspecialchars($booking['end_date'] ?? '') ?>
  </p>

  <hr>
  <p style="color:#777;">DarDark</p>
</div>
