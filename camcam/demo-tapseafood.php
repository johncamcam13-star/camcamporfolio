<?php
/**
 * Tapseafood: Online Food Ordering - Interactive Live Demo
 * Developer: John Carlo Camcam (BSIT)
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tapseafood Live Demo | Online Food Ordering | John Carlo Camcam</title>
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
    .demo-layout-grid {
      display: grid;
      grid-template-columns: 1.6fr 1.1fr;
      gap: 2rem;
    }
    @media (max-width: 900px) {
      .demo-layout-grid { grid-template-columns: 1fr; }
    }
    .menu-items-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
      gap: 1.25rem;
    }
    .menu-card {
      background: #0d121c;
      border: 1px solid rgba(255, 215, 0, 0.18);
      border-radius: 12px;
      padding: 1.25rem;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      transition: all 0.25s ease;
    }
    .menu-card:hover {
      border-color: #ffd700;
      transform: translateY(-4px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.7);
    }
    .item-emoji {
      font-size: 2.5rem;
      margin-bottom: 0.5rem;
      display: block;
    }
    .item-title {
      font-size: 1.1rem;
      font-weight: 700;
      color: #ffffff;
      margin-bottom: 0.25rem;
    }
    .item-desc {
      font-size: 0.82rem;
      color: #94a3b8;
      margin-bottom: 0.85rem;
      line-height: 1.4;
    }
    .item-price {
      font-size: 1.15rem;
      font-weight: 800;
      color: #ffd700;
      margin-bottom: 0.85rem;
    }
    .cart-summary-panel {
      background: #0d121c;
      border: 1px solid var(--gold-border);
      border-radius: 14px;
      padding: 1.5rem;
      height: fit-content;
      position: sticky;
      top: 1.5rem;
      box-shadow: 0 20px 50px rgba(0,0,0,0.85);
    }
    .cart-item-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0.65rem 0;
      border-bottom: 1px solid rgba(255, 255, 255, 0.08);
      font-size: 0.9rem;
    }
    .cart-qty-btn {
      background: rgba(255, 255, 255, 0.1);
      border: 1px solid var(--gold-border);
      color: #fff;
      width: 24px;
      height: 24px;
      border-radius: 4px;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
    }
    .order-tracker-step {
      display: flex;
      gap: 1rem;
      align-items: center;
      margin-bottom: 1rem;
      opacity: 0.4;
      transition: all 0.3s ease;
    }
    .order-tracker-step.active {
      opacity: 1;
      transform: scale(1.02);
    }
    .step-dot {
      width: 28px;
      height: 28px;
      border-radius: 50%;
      background: #1e293b;
      border: 2px solid #475569;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.8rem;
      font-weight: 700;
    }
    .order-tracker-step.active .step-dot {
      background: #ffd700;
      color: #0b0f17;
      border-color: #fff;
      box-shadow: 0 0 12px rgba(255, 215, 0, 0.5);
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
        <span class="gold-gradient-text" style="font-weight: 800; font-size: 1.15rem;">PROJECT 01: TAPSEAFOOD</span>
        <span style="color: var(--silver-dark); font-size: 0.85rem; margin-left: 0.5rem;">Interactive Live Simulation</span>
      </div>
      <div>
        <span class="badge-title" style="border: 1px solid var(--gold-border); padding: 4px 10px; border-radius: 20px; color: var(--gold-light);">PHP &bull; MySQL Order Pipeline</span>
      </div>
    </div>

    <!-- Banner -->
    <div style="background: linear-gradient(135deg, rgba(255,215,0,0.12) 0%, rgba(13,18,28,0.95) 100%); border: 1px solid var(--gold-border); border-radius: 14px; padding: 1.5rem 1.75rem; margin-bottom: 2rem;">
      <h2 class="gold-gradient-text" style="font-size: 1.75rem; font-weight: 800; margin-bottom: 0.35rem;">🦐 Tapseafood: Online Seafood Ordering Engine</h2>
      <p style="color: #cbd5e1; font-size: 0.92rem; max-width: 820px; line-height: 1.6;">
        Demonstrating session cart computation, dynamic weight pricing, automated VAT and delivery calculation, and live MySQL order fulfillment tracking workflow.
      </p>
    </div>

    <div class="demo-layout-grid">
      <!-- Menu Catalog -->
      <div>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
          <h3 style="font-size: 1.25rem; font-weight: 700; color: #fff;">Fresh Seafood Menu (Daily Catch)</h3>
          <span style="font-size: 0.82rem; color: #94a3b8;">5 Items in Catalog</span>
        </div>

        <div class="menu-items-grid">
          <!-- Item 1 -->
          <div class="menu-card">
            <div>
              <span class="item-emoji">🦀</span>
              <div class="item-title">Mud Crabs (Alimango)</div>
              <div class="item-desc">Live wild-caught extra large crabs, fresh from Roxas City seafood market.</div>
            </div>
            <div>
              <div class="item-price">₱1,250 <span style="font-size: 0.75rem; color: #94a3b8; font-weight: normal;">/ kg</span></div>
              <button class="btn btn-gold btn-sm" style="width: 100%;" onclick="addToCart('Mud Crabs (Alimango)', 1250)">
                <span>+ Add to Cart</span>
              </button>
            </div>
          </div>

          <!-- Item 2 -->
          <div class="menu-card">
            <div>
              <span class="item-emoji">🦐</span>
              <div class="item-title">Jumbo Tiger Prawns (Sugpo)</div>
              <div class="item-desc">Premium quality black tiger prawns with sweet, succulent firm meat.</div>
            </div>
            <div>
              <div class="item-price">₱850 <span style="font-size: 0.75rem; color: #94a3b8; font-weight: normal;">/ kg</span></div>
              <button class="btn btn-gold btn-sm" style="width: 100%;" onclick="addToCart('Jumbo Tiger Prawns', 850)">
                <span>+ Add to Cart</span>
              </button>
            </div>
          </div>

          <!-- Item 3 -->
          <div class="menu-card">
            <div>
              <span class="item-emoji">🐟</span>
              <div class="item-title">Norwegian Salmon Fillet</div>
              <div class="item-desc">Fresh sashimi-grade whole pink salmon cuts rich in natural Omega-3.</div>
            </div>
            <div>
              <div class="item-price">₱980 <span style="font-size: 0.75rem; color: #94a3b8; font-weight: normal;">/ kg</span></div>
              <button class="btn btn-gold btn-sm" style="width: 100%;" onclick="addToCart('Norwegian Salmon Fillet', 980)">
                <span>+ Add to Cart</span>
              </button>
            </div>
          </div>

          <!-- Item 4 -->
          <div class="menu-card">
            <div>
              <span class="item-emoji">🦑</span>
              <div class="item-title">Fresh Calamari Squid (Pusit)</div>
              <div class="item-desc">Tender fresh ocean squid perfect for grilling or authentic fried calamari.</div>
            </div>
            <div>
              <div class="item-price">₱460 <span style="font-size: 0.75rem; color: #94a3b8; font-weight: normal;">/ kg</span></div>
              <button class="btn btn-gold btn-sm" style="width: 100%;" onclick="addToCart('Fresh Calamari Squid', 460)">
                <span>+ Add to Cart</span>
              </button>
            </div>
          </div>

          <!-- Item 5 -->
          <div class="menu-card">
            <div>
              <span class="item-emoji">🦞</span>
              <div class="item-title">Pacific Rock Lobster</div>
              <div class="item-desc">Freshly harvested sweet rock spiny lobsters with tender rich white meat.</div>
            </div>
            <div>
              <div class="item-price">₱1,650 <span style="font-size: 0.75rem; color: #94a3b8; font-weight: normal;">/ kg</span></div>
              <button class="btn btn-gold btn-sm" style="width: 100%;" onclick="addToCart('Pacific Rock Lobster', 1650)">
                <span>+ Add to Cart</span>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Right: Cart & Order Workflow -->
      <div>
        <div class="cart-summary-panel">
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h3 style="font-size: 1.15rem; font-weight: 700; color: #ffd700;">🛒 Session Cart (PHP)</h3>
            <span id="cartCountBadge" style="background: rgba(255,215,0,0.15); border: 1px solid var(--gold-border); color: #ffd700; padding: 2px 8px; border-radius: 12px; font-size: 0.75rem; font-weight: 700;">0 items</span>
          </div>

          <div id="cartItemsList" style="min-height: 120px; max-height: 220px; overflow-y: auto; margin-bottom: 1rem;">
            <div style="text-align: center; color: #64748b; padding: 2rem 0; font-size: 0.88rem;">
              Your seafood basket is empty.<br>Click <strong>+ Add to Cart</strong> on any item to begin!
            </div>
          </div>

          <!-- Calculation Breakdown -->
          <div style="border-top: 1px solid rgba(255,255,255,0.08); padding-top: 0.75rem; font-size: 0.85rem; color: #94a3b8;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.35rem;">
              <span>Subtotal:</span>
              <span id="subtotalVal">₱0.00</span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.35rem;">
              <span>12% VAT:</span>
              <span id="vatVal">₱0.00</span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.65rem;">
              <span>Standard Delivery Fee:</span>
              <span id="deliveryVal">₱65.00</span>
            </div>
            <div style="display: flex; justify-content: space-between; font-size: 1.15rem; font-weight: 800; color: #ffd700; border-top: 1px solid rgba(255,215,0,0.2); padding-top: 0.5rem; margin-bottom: 1rem;">
              <span>Total Amount:</span>
              <span id="grandTotalVal">₱0.00</span>
            </div>
          </div>

          <!-- Checkout Form -->
          <form id="checkoutForm" onsubmit="handlePlaceOrder(event)">
            <div style="margin-bottom: 0.75rem;">
              <input type="text" id="custName" placeholder="Recipient Full Name" required class="form-input-field" style="padding: 0.55rem 0.85rem; font-size: 0.85rem;" value="Engr. Alex Reyes">
            </div>
            <div style="margin-bottom: 0.75rem;">
              <input type="text" id="custAddress" placeholder="Delivery Address" required class="form-input-field" style="padding: 0.55rem 0.85rem; font-size: 0.85rem;" value="Tower 2, Bonifacio Global City, Taguig">
            </div>
            <div style="margin-bottom: 1rem;">
              <select class="form-input-field" style="padding: 0.55rem 0.85rem; font-size: 0.85rem;">
                <option value="cod">Cash on Delivery (COD)</option>
                <option value="gcash">GCash Online Payment</option>
              </select>
            </div>

            <button type="submit" id="placeOrderBtn" class="btn btn-gold" style="width: 100%;" disabled>
              <span>✦ Place Seafood Order</span>
            </button>
          </form>

          <!-- Live Order Status Tracker (Appears upon ordering) -->
          <div id="orderTrackerSection" style="display: none; margin-top: 1.5rem; padding-top: 1.25rem; border-top: 1px solid rgba(255,215,0,0.25);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
              <h4 style="font-size: 0.95rem; color: #ffd700; margin: 0;">📦 Live Order Tracking</h4>
              <span id="orderRefTag" style="font-family: var(--font-mono); font-size: 0.75rem; color: #fff; background: rgba(255,255,255,0.1); padding: 2px 6px; border-radius: 4px;">#TS-2026-001</span>
            </div>

            <div class="order-tracker-step active" id="step1">
              <div class="step-dot">1</div>
              <div>
                <strong style="font-size: 0.85rem; display: block;">Order Confirmed &amp; Logged</strong>
                <span style="font-size: 0.74rem; color: #94a3b8;">Recorded to MySQL database</span>
              </div>
            </div>

            <div class="order-tracker-step" id="step2">
              <div class="step-dot">2</div>
              <div>
                <strong style="font-size: 0.85rem; display: block;">Kitchen Preparing &amp; Packaging</strong>
                <span style="font-size: 0.74rem; color: #94a3b8;">Fresh catch weighed and iced</span>
              </div>
            </div>

            <div class="order-tracker-step" id="step3">
              <div class="step-dot">3</div>
              <div>
                <strong style="font-size: 0.85rem; display: block;">Out for Delivery</strong>
                <span style="font-size: 0.74rem; color: #94a3b8;">Rider en route to delivery address</span>
              </div>
            </div>

            <div class="order-tracker-step" id="step4">
              <div class="step-dot">4</div>
              <div>
                <strong style="font-size: 0.85rem; display: block;">Delivered Successfully</strong>
                <span style="font-size: 0.74rem; color: #4ade80;">Enjoy your fresh seafood!</span>
              </div>
            </div>

            <button class="btn btn-silver btn-sm" style="width: 100%; margin-top: 0.5rem;" onclick="resetDemo()">
              <span>↺ Reset Demo Simulation</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    const cart = {};

    function addToCart(name, price) {
      if (cart[name]) {
        cart[name].qty += 1;
      } else {
        cart[name] = { price: price, qty: 1 };
      }
      renderCart();
    }

    function changeQty(name, delta) {
      if (!cart[name]) return;
      cart[name].qty += delta;
      if (cart[name].qty <= 0) delete cart[name];
      renderCart();
    }

    function renderCart() {
      const listEl = document.getElementById('cartItemsList');
      const badgeEl = document.getElementById('cartCountBadge');
      const subtotalEl = document.getElementById('subtotalVal');
      const vatEl = document.getElementById('vatVal');
      const grandTotalEl = document.getElementById('grandTotalVal');
      const btnEl = document.getElementById('placeOrderBtn');

      const items = Object.keys(cart);
      if (items.length === 0) {
        listEl.innerHTML = '<div style="text-align: center; color: #64748b; padding: 2rem 0; font-size: 0.88rem;">Your seafood basket is empty.<br>Click <strong>+ Add to Cart</strong> to begin!</div>';
        badgeEl.textContent = '0 items';
        subtotalEl.textContent = '₱0.00';
        vatEl.textContent = '₱0.00';
        grandTotalEl.textContent = '₱0.00';
        btnEl.disabled = true;
        return;
      }

      let subtotal = 0;
      let totalQty = 0;
      let html = '';

      items.forEach(name => {
        const item = cart[name];
        const lineTotal = item.price * item.qty;
        subtotal += lineTotal;
        totalQty += item.qty;

        html += `
          <div class="cart-item-row">
            <div>
              <strong style="color: #fff; display: block;">${name}</strong>
              <span style="font-size: 0.78rem; color: #94a3b8;">₱${item.price.toLocaleString()} × ${item.qty} kg</span>
            </div>
            <div style="display: flex; align-items: center; gap: 0.4rem;">
              <button type="button" class="cart-qty-btn" onclick="changeQty('${name}', -1)">-</button>
              <span style="font-weight: 700; min-width: 18px; text-align: center;">${item.qty}</span>
              <button type="button" class="cart-qty-btn" onclick="changeQty('${name}', 1)">+</button>
              <span style="font-weight: 700; color: #ffd700; margin-left: 0.35rem;">₱${lineTotal.toLocaleString()}</span>
            </div>
          </div>
        `;
      });

      listEl.innerHTML = html;
      badgeEl.textContent = `${totalQty} kg (${items.length} items)`;

      const vat = subtotal * 0.12;
      const delivery = 65;
      const grandTotal = subtotal + vat + delivery;

      subtotalEl.textContent = '₱' + subtotal.toLocaleString(undefined, {minimumFractionDigits: 2});
      vatEl.textContent = '₱' + vat.toLocaleString(undefined, {minimumFractionDigits: 2});
      grandTotalEl.textContent = '₱' + grandTotal.toLocaleString(undefined, {minimumFractionDigits: 2});
      btnEl.disabled = false;
    }

    function handlePlaceOrder(e) {
      e.preventDefault();
      const tracker = document.getElementById('orderTrackerSection');
      const refTag = document.getElementById('orderRefTag');
      const randomRef = 'TS-' + Math.floor(1000 + Math.random() * 9000) + '-MNL';
      refTag.textContent = '#' + randomRef;
      tracker.style.display = 'block';

      // Animate steps
      const s1 = document.getElementById('step1');
      const s2 = document.getElementById('step2');
      const s3 = document.getElementById('step3');
      const s4 = document.getElementById('step4');

      s1.classList.add('active');
      s2.classList.remove('active');
      s3.classList.remove('active');
      s4.classList.remove('active');

      setTimeout(() => s2.classList.add('active'), 1400);
      setTimeout(() => s3.classList.add('active'), 2800);
      setTimeout(() => s4.classList.add('active'), 4200);

      document.getElementById('placeOrderBtn').disabled = true;
    }

    function resetDemo() {
      for (const k in cart) delete cart[k];
      renderCart();
      document.getElementById('orderTrackerSection').style.display = 'none';
    }

    // Prepopulate 1 sample item so demo is instantly alive
    addToCart('Jumbo Tiger Prawns', 850);
  </script>
</body>
</html>
