<div style="font-family:Arial,sans-serif; line-height:1.6">
  <h2>Booking Confirmed ✅</h2>

  <p>Hello <b><?= htmlspecialchars($traveler['name'] ?? '') ?></b>,</p>

  <p>Your booking has been successfully confirmed.</p>

  <p><b>Rental:</b> <?= htmlspecialchars($rental['title'] ?? '') ?></p>
  <p><b>City:</b> <?= htmlspecialchars($rental['city'] ?? '') ?></p>

  <p><b>Dates:</b>
    <?= htmlspecialchars($booking['start_date'] ?? '') ?>
    →
    <?= htmlspecialchars($booking['end_date'] ?? '') ?>
  </p>

  <p><b>Total price:</b> <?= htmlspecialchars($booking['total_price'] ?? '') ?></p>

  <hr>
  <p style="color:#777;">DarDark • Enjoy your stay 🏡</p>
</div>
