<div style="font-family:Arial,sans-serif; line-height:1.6">
  <h2>Booking Cancelled ❌</h2>

  <p>Hello <b><?= htmlspecialchars($traveler['name'] ?? '') ?></b>,</p>

  <p>Your booking has been cancelled.</p>

  <p><b>Rental:</b> <?= htmlspecialchars($rental['title'] ?? '') ?></p>

  <p><b>Dates:</b>
    <?= htmlspecialchars($booking['start_date'] ?? '') ?>
    →
    <?= htmlspecialchars($booking['end_date'] ?? '') ?>
  </p>

  <hr>
  <p style="color:#777;">DarDark</p>
</div>
