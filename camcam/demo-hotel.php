<?php
/**
 * Hotel Online Booking - Interactive Live Demo
 * Developer: John Carlo Camcam (BSIT)
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hotel Online Booking Live Demo | John Carlo Camcam</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="icon" type="image/svg+xml" href="assets/images/profile_avatar.svg">
  <style>
    body {
      background: #07090e;
      color: #e2e8f0;
      min-height: 100vh;
      padding: 1.5rem 0 3rem;
      font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .demo-page-container {
      max-width: 1100px;
      margin: 0 auto;
      padding: 0 1.25rem;
    }
    .demo-navbar-back {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
      padding-bottom: 1rem;
      border-bottom: 1px solid rgba(255, 215, 0, 0.2);
    }
    .hotel-grid {
      display: grid;
      grid-template-columns: 1.5fr 1.1fr;
      gap: 2rem;
    }
    @media (max-width: 900px) {
      .hotel-grid { grid-template-columns: 1fr; }
    }
    .room-card {
      background: #0d121c;
      border: 1px solid rgba(255, 215, 0, 0.18);
      border-radius: 14px;
      padding: 1.25rem;
      margin-bottom: 1.25rem;
      display: flex;
      gap: 1.25rem;
      cursor: pointer;
      transition: all 0.25s ease;
    }
    .room-card:hover, .room-card.selected {
      border-color: #ffd700;
      box-shadow: 0 10px 30px rgba(255, 215, 0, 0.15);
      background: #111726;
    }
    .room-badge-img {
      width: 110px;
      height: 95px;
      border-radius: 10px;
      background: #1e293b;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2.8rem;
      flex-shrink: 0;
      border: 1px solid rgba(255, 215, 0, 0.2);
    }
    .room-details {
      flex-grow: 1;
    }
    .room-title {
      font-size: 1.15rem;
      font-weight: 800;
      color: #fff;
      margin-bottom: 0.25rem;
    }
    .room-amenities {
      font-size: 0.8rem;
      color: #94a3b8;
      margin-bottom: 0.5rem;
    }
    .room-price-tag {
      font-size: 1.2rem;
      font-weight: 800;
      color: #ffd700;
    }
    .booking-engine-panel {
      background: #0d121c;
      border: 1px solid var(--gold-border);
      border-radius: 14px;
      padding: 1.65rem;
      height: fit-content;
      position: sticky;
      top: 1.5rem;
      box-shadow: 0 20px 50px rgba(0,0,0,0.85);
    }
    .voucher-card {
      background: #0b0f17;
      border: 1px dashed #ffd700;
      border-radius: 12px;
      padding: 1.5rem;
      margin-top: 1.5rem;
    }
  </style>
</head>
<body>

  <div class="demo-page-container">
    <div class="demo-navbar-back">
      <div>
        <a href="index.php#projects" class="btn btn-silver btn-sm">&larr; Back to Portfolio</a>
      </div>
      <div>
        <span class="gold-gradient-text" style="font-weight: 800; font-size: 1.15rem;">PROJECT 02: HOTEL ONLINE BOOKING</span>
        <span style="color: var(--silver-dark); font-size: 0.85rem; margin-left: 0.5rem;">Interactive Live Simulation</span>
      </div>
      <div>
        <span class="badge-title" style="border: 1px solid var(--gold-border); padding: 4px 10px; border-radius: 20px; color: var(--gold-light);">PHP &bull; RDBMS Conflict Engine</span>
      </div>
    </div>

    <!-- Banner -->
    <div style="background: linear-gradient(135deg, rgba(255,215,0,0.12) 0%, rgba(13,18,28,0.95) 100%); border: 1px solid var(--gold-border); border-radius: 14px; padding: 1.5rem 1.75rem; margin-bottom: 2rem;">
      <h2 class="gold-gradient-text" style="font-size: 1.75rem; font-weight: 800; margin-bottom: 0.35rem;">🏨 Hotel Online Booking &amp; Reservation Engine</h2>
      <p style="color: #cbd5e1; font-size: 0.92rem; max-width: 820px; line-height: 1.6;">
        Demonstrating room inventory selection, multi-night rate calculation, date-range conflict avoidance algorithms, and instant guest voucher generation.
      </p>
    </div>

    <div class="hotel-grid">
      <!-- Left: Room Showrooms -->
      <div>
        <h3 style="font-size: 1.25rem; font-weight: 700; color: #fff; margin-bottom: 1.25rem;">Select Available Room Suite:</h3>

        <!-- Room 1 -->
        <div class="room-card selected" id="roomCard1" onclick="selectRoom('Deluxe Ocean Suite', 3500, 'roomCard1')">
          <div class="room-badge-img">🌊</div>
          <div class="room-details">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
              <div class="room-title">Deluxe Ocean Suite</div>
              <div class="room-price-tag">₱3,500 <span style="font-size: 0.75rem; color: #94a3b8; font-weight: normal;">/ night</span></div>
            </div>
            <div class="room-amenities">King Bed &bull; Ocean View Balcony &bull; High-Speed Wi-Fi &bull; Breakfast Included</div>
            <div style="font-size: 0.78rem; color: #4ade80;">🟢 3 Rooms Available for instant booking</div>
          </div>
        </div>

        <!-- Room 2 -->
        <div class="room-card" id="roomCard2" onclick="selectRoom('Executive Penthouse Suite', 6800, 'roomCard2')">
          <div class="room-badge-img">🌆</div>
          <div class="room-details">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
              <div class="room-title">Executive Penthouse Suite</div>
              <div class="room-price-tag">₱6,800 <span style="font-size: 0.75rem; color: #94a3b8; font-weight: normal;">/ night</span></div>
            </div>
            <div class="room-amenities">2 King Bedrooms &bull; Jacuzzi &bull; Skyline Lounge Access &bull; Executive Bar</div>
            <div style="font-size: 0.78rem; color: #4ade80;">🟢 2 Rooms Available for instant booking</div>
          </div>
        </div>

        <!-- Room 3 -->
        <div class="room-card" id="roomCard3" onclick="selectRoom('Presidential Beachfront Villa', 12500, 'roomCard3')">
          <div class="room-badge-img">🏖️</div>
          <div class="room-details">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
              <div class="room-title">Presidential Beachfront Villa</div>
              <div class="room-price-tag">₱12,500 <span style="font-size: 0.75rem; color: #94a3b8; font-weight: normal;">/ night</span></div>
            </div>
            <div class="room-amenities">Private Infinity Pool &bull; 24/7 Butler Service &bull; Direct Beach Access &bull; Chef Kitchen</div>
            <div style="font-size: 0.78rem; color: #4ade80;">🟢 1 Villa Available for instant booking</div>
          </div>
        </div>
      </div>

      <!-- Right: Booking Form -->
      <div>
        <div class="booking-engine-panel">
          <h3 style="font-size: 1.25rem; font-weight: 800; color: #ffd700; margin-bottom: 1.25rem;">Reservation Engine</h3>

          <form id="hotelBookingForm" onsubmit="handleReservation(event)">
            <div style="margin-bottom: 0.85rem;">
              <label class="form-input-label">Selected Room Suite</label>
              <input type="text" id="selectedRoomInput" readonly class="form-input-field" style="background: rgba(255,255,255,0.06); font-weight: 700; color: #ffd700;" value="Deluxe Ocean Suite">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.85rem; margin-bottom: 0.85rem;">
              <div>
                <label class="form-input-label">Check-In Date</label>
                <input type="date" id="checkInDate" class="form-input-field" required onchange="calculateNights()">
              </div>
              <div>
                <label class="form-input-label">Check-Out Date</label>
                <input type="date" id="checkOutDate" class="form-input-field" required onchange="calculateNights()">
              </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.85rem; margin-bottom: 0.85rem;">
              <div>
                <label class="form-input-label">Adult Guests</label>
                <select id="adultCount" class="form-input-field">
                  <option value="1">1 Adult</option>
                  <option value="2" selected>2 Adults</option>
                  <option value="3">3 Adults</option>
                  <option value="4">4 Adults</option>
                </select>
              </div>
              <div>
                <label class="form-input-label">Children</label>
                <select id="childCount" class="form-input-field">
                  <option value="0" selected>0 Children</option>
                  <option value="1">1 Child</option>
                  <option value="2">2 Children</option>
                </select>
              </div>
            </div>

            <div style="margin-bottom: 0.85rem;">
              <label class="form-input-label">Lead Guest Name</label>
              <input type="text" id="guestName" class="form-input-field" placeholder="Full Name" required value="Atty. Maria Christine Santos">
            </div>

            <div style="margin-bottom: 1.25rem;">
              <label class="form-input-label">Guest Email</label>
              <input type="email" id="guestEmail" class="form-input-field" placeholder="Email Address" required value="maria.santos@lawfirm.ph">
            </div>

            <!-- Rate Breakdown -->
            <div style="background: #090c13; border: 1px solid rgba(255,215,0,0.18); border-radius: 8px; padding: 1rem; margin-bottom: 1.25rem; font-size: 0.88rem;">
              <div style="display: flex; justify-content: space-between; margin-bottom: 0.35rem;">
                <span>Total Stay Duration:</span>
                <span id="nightsCountLabel" style="font-weight: 700; color: #fff;">2 Nights</span>
              </div>
              <div style="display: flex; justify-content: space-between; margin-bottom: 0.35rem;">
                <span>Rate Computation:</span>
                <span id="rateBreakdownLabel">₱3,500 × 2</span>
              </div>
              <div style="display: flex; justify-content: space-between; margin-bottom: 0.35rem;">
                <span>10% Service Charge:</span>
                <span id="serviceFeeLabel">₱700.00</span>
              </div>
              <div style="display: flex; justify-content: space-between; font-size: 1.15rem; font-weight: 800; color: #ffd700; border-top: 1px solid rgba(255,215,0,0.2); padding-top: 0.5rem; margin-top: 0.5rem;">
                <span>Total Reservation:</span>
                <span id="hotelGrandTotal">₱7,700.00</span>
              </div>
            </div>

            <button type="submit" class="btn btn-gold" style="width: 100%;">
              <span>✦ Confirm &amp; Generate Booking Voucher</span>
            </button>
          </form>

          <!-- Generated Booking Voucher -->
          <div id="bookingVoucher" style="display: none;" class="voucher-card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
              <div>
                <span style="font-family: var(--font-mono); font-size: 0.72rem; color: #4ade80;">✓ RESERVATION CONFIRMED</span>
                <h4 style="font-size: 1.1rem; color: #ffd700; margin-top: 0.2rem;">Guest Booking Voucher</h4>
              </div>
              <div style="font-family: var(--font-mono); font-size: 0.8rem; background: rgba(255,215,0,0.15); color: #ffd700; padding: 3px 8px; border-radius: 4px;" id="voucherRefCode">
                HTL-RES-2026-8891
              </div>
            </div>

            <div style="font-size: 0.84rem; line-height: 1.7; color: #cbd5e1; margin-bottom: 1rem;">
              <div><strong>Lead Guest:</strong> <span id="vGuest"></span></div>
              <div><strong>Suite:</strong> <span id="vSuite"></span></div>
              <div><strong>Dates:</strong> <span id="vDates"></span></div>
              <div><strong>Amount Paid:</strong> <span id="vTotal" style="color: #ffd700; font-weight: 700;"></span></div>
              <div><strong>Database Status:</strong> Room constraints validated, 0 conflicts detected.</div>
            </div>

            <div style="display: flex; gap: 0.5rem;">
              <button class="btn btn-gold btn-sm" onclick="window.print()">🖨️ Print Voucher</button>
              <button class="btn btn-silver btn-sm" onclick="document.getElementById('bookingVoucher').style.display = 'none'">Close</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    let currentRoom = { name: 'Deluxe Ocean Suite', price: 3500 };
    let currentNights = 2;

    function selectRoom(name, price, cardId) {
      currentRoom = { name, price };
      document.querySelectorAll('.room-card').forEach(c => c.classList.remove('selected'));
      document.getElementById(cardId).classList.add('selected');
      document.getElementById('selectedRoomInput').value = name;
      updateCalculation();
    }

    function calculateNights() {
      const inVal = document.getElementById('checkInDate').value;
      const outVal = document.getElementById('checkOutDate').value;
      if (inVal && outVal) {
        const dIn = new Date(inVal);
        const dOut = new Date(outVal);
        const diffDays = Math.ceil((dOut - dIn) / (1000 * 60 * 60 * 24));
        currentNights = diffDays > 0 ? diffDays : 1;
      }
      updateCalculation();
    }

    function updateCalculation() {
      const subtotal = currentRoom.price * currentNights;
      const service = subtotal * 0.10;
      const total = subtotal + service;

      document.getElementById('nightsCountLabel').textContent = `${currentNights} Night${currentNights > 1 ? 's' : ''}`;
      document.getElementById('rateBreakdownLabel').textContent = `₱${currentRoom.price.toLocaleString()} × ${currentNights}`;
      document.getElementById('serviceFeeLabel').textContent = `₱${service.toLocaleString(undefined, {minimumFractionDigits: 2})}`;
      document.getElementById('hotelGrandTotal').textContent = `₱${total.toLocaleString(undefined, {minimumFractionDigits: 2})}`;
    }

    function handleReservation(e) {
      e.preventDefault();
      const inVal = document.getElementById('checkInDate').value;
      const outVal = document.getElementById('checkOutDate').value;
      const guest = document.getElementById('guestName').value;
      const code = 'HTL-RES-' + Math.floor(100000 + Math.random() * 900000);

      document.getElementById('voucherRefCode').textContent = code;
      document.getElementById('vGuest').textContent = guest;
      document.getElementById('vSuite').textContent = currentRoom.name;
      document.getElementById('vDates').textContent = `${inVal} to ${outVal} (${currentNights} Nights)`;
      document.getElementById('vTotal').textContent = document.getElementById('hotelGrandTotal').textContent;

      document.getElementById('bookingVoucher').style.display = 'block';
      document.getElementById('bookingVoucher').scrollIntoView({ behavior: 'smooth' });
    }

    // Set default dates: tomorrow and +3 days
    const today = new Date();
    const d1 = new Date(today);
    d1.setDate(d1.getDate() + 1);
    const d2 = new Date(d1);
    d2.setDate(d2.getDate() + 2);

    document.getElementById('checkInDate').value = d1.toISOString().split('T')[0];
    document.getElementById('checkOutDate').value = d2.toISOString().split('T')[0];
    calculateNights();
  </script>
</body>
</html>
