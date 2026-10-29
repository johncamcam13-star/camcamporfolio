<?php
/**
 * Arduino Smart Water Dispenser - Interactive Hardware & IoT Live Demo
 * Developer: John Carlo Camcam (BSIT)
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Arduino Water Dispenser Live Demo | IoT Hardware | John Carlo Camcam</title>
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
    .iot-grid {
      display: grid;
      grid-template-columns: 1.3fr 1fr;
      gap: 2rem;
    }
    @media (max-width: 900px) {
      .iot-grid { grid-template-columns: 1fr; }
    }
    .hardware-rig-card {
      background: #090d14;
      border: 1px solid var(--gold-border);
      border-radius: 16px;
      padding: 2rem;
      box-shadow: 0 25px 60px rgba(0,0,0,0.9);
      position: relative;
    }
    /* 16x2 LCD Simulation */
    .lcd-screen-box {
      background: #0a1f33;
      border: 4px solid #1e293b;
      border-radius: 8px;
      padding: 1rem 1.25rem;
      font-family: 'JetBrains Mono', monospace;
      color: #38bdf8;
      box-shadow: inset 0 0 15px rgba(56, 189, 248, 0.35), 0 0 15px rgba(0,0,0,0.8);
      margin-bottom: 1.75rem;
    }
    .lcd-line {
      font-size: 1.15rem;
      letter-spacing: 2px;
      font-weight: 700;
      min-height: 1.5rem;
      text-shadow: 0 0 6px rgba(56, 189, 248, 0.6);
    }
    /* Dispenser Spout & Glass animation */
    .dispenser-visual-stage {
      background: #06080d;
      border: 1px solid rgba(255, 255, 255, 0.08);
      border-radius: 12px;
      padding: 2rem 1.5rem;
      display: flex;
      flex-direction: column;
      align-items: center;
      position: relative;
      min-height: 280px;
      overflow: hidden;
      margin-bottom: 1.5rem;
    }
    .spout-nozzle {
      width: 24px;
      height: 40px;
      background: linear-gradient(180deg, #94a3b8 0%, #475569 100%);
      border-radius: 4px 4px 0 0;
      position: relative;
    }
    .ultrasonic-sensor-graphic {
      display: flex;
      gap: 12px;
      margin-bottom: 10px;
      padding: 4px 10px;
      background: #1e3a5f;
      border-radius: 6px;
      border: 1px solid #38bdf8;
    }
    .us-eye {
      width: 20px;
      height: 20px;
      border-radius: 50%;
      background: #0f172a;
      border: 2px solid #94a3b8;
    }
    .water-stream {
      width: 6px;
      height: 0;
      background: linear-gradient(180deg, rgba(56,189,248,0.8) 0%, rgba(14,165,233,1) 100%);
      border-radius: 3px;
      transition: height 0.3s ease;
      box-shadow: 0 0 10px rgba(56, 189, 248, 0.8);
    }
    .water-stream.flowing {
      height: 110px;
      animation: waterFlowAnim 0.2s infinite alternate;
    }
    @keyframes waterFlowAnim {
      from { opacity: 0.85; width: 6px; }
      to { opacity: 1; width: 7px; }
    }
    .glass-container {
      width: 70px;
      height: 95px;
      border: 3px solid rgba(255, 255, 255, 0.4);
      border-top: none;
      border-radius: 0 0 12px 12px;
      position: relative;
      background: rgba(255, 255, 255, 0.04);
      overflow: hidden;
      transition: transform 0.4s ease;
      margin-top: 10px;
    }
    .glass-liquid {
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      height: 0%;
      background: linear-gradient(180deg, #38bdf8 0%, #0284c7 100%);
      transition: height 0.2s linear;
      border-radius: 0 0 9px 9px;
    }
    /* Serial Monitor */
    .serial-monitor-panel {
      background: #05070a;
      border: 1px solid rgba(255, 215, 0, 0.2);
      border-radius: 12px;
      padding: 1.25rem;
      font-family: 'JetBrains Mono', monospace;
      font-size: 0.78rem;
      color: #cbd5e1;
      height: 480px;
      display: flex;
      flex-direction: column;
    }
    .serial-log-window {
      background: #000;
      padding: 0.85rem;
      border-radius: 6px;
      border: 1px solid #1e293b;
      overflow-y: auto;
      flex-grow: 1;
      line-height: 1.6;
      margin-top: 0.75rem;
      color: #4ade80;
    }
    .relay-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 4px 10px;
      border-radius: 6px;
      font-family: var(--font-mono);
      font-size: 0.75rem;
      font-weight: 700;
    }
    .relay-badge.off {
      background: rgba(239, 68, 68, 0.15);
      border: 1px solid #ef4444;
      color: #ef4444;
    }
    .relay-badge.on {
      background: rgba(74, 222, 128, 0.15);
      border: 1px solid #4ade80;
      color: #4ade80;
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
        <span class="gold-gradient-text" style="font-weight: 800; font-size: 1.15rem;">PROJECT 03: ARDUINO SMART WATER DISPENSER</span>
        <span style="color: var(--silver-dark); font-size: 0.85rem; margin-left: 0.5rem;">Hardware Simulator</span>
      </div>
      <div>
        <span class="badge-title" style="border: 1px solid var(--gold-border); padding: 4px 10px; border-radius: 20px; color: var(--gold-light);">C++ &bull; IoT &bull; MySQL Telemetry</span>
      </div>
    </div>

    <!-- Banner -->
    <div style="background: linear-gradient(135deg, rgba(255,215,0,0.12) 0%, rgba(13,18,28,0.95) 100%); border: 1px solid var(--gold-border); border-radius: 14px; padding: 1.5rem 1.75rem; margin-bottom: 2rem;">
      <h2 class="gold-gradient-text" style="font-size: 1.75rem; font-weight: 800; margin-bottom: 0.35rem;">🤖 Arduino Smart Contactless Water Dispenser</h2>
      <p style="color: #cbd5e1; font-size: 0.92rem; max-width: 820px; line-height: 1.6;">
        Microcontroller hardware simulation integrating HC-SR04 ultrasonic distance sensing, calibrated volumetric relay dispensing, 16x2 alphanumeric LCD telemetry, and MySQL database logging.
      </p>
    </div>

    <div class="iot-grid">
      <!-- Left: Interactive Hardware Rig -->
      <div>
        <div class="hardware-rig-card">
          <!-- 16x2 LCD Simulation -->
          <div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
              <span style="font-family: var(--font-mono); font-size: 0.72rem; color: #94a3b8;">HD44780 16x2 I2C LCD DISPLAY</span>
              <span class="relay-badge off" id="relayStatusBadge">● RELAY: DEACTIVATED</span>
            </div>

            <div class="lcd-screen-box">
              <div class="lcd-line" id="lcdLine1">SYSTEM: READY</div>
              <div class="lcd-line" id="lcdLine2">DIST: 22.0CM</div>
            </div>
          </div>

          <!-- Dispenser Physical Rig -->
          <div class="dispenser-visual-stage">
            <div class="ultrasonic-sensor-graphic" title="HC-SR04 Ultrasonic Distance Sensor">
              <div class="us-eye" title="Transmitter (TRIG)"></div>
              <div class="us-eye" title="Receiver (ECHO)"></div>
            </div>

            <div class="spout-nozzle"></div>
            <div class="water-stream" id="waterStream"></div>
            
            <div class="glass-container" id="glassContainer">
              <div class="glass-liquid" id="glassLiquid"></div>
            </div>

            <div style="margin-top: 1rem; font-family: var(--font-mono); font-size: 0.8rem; color: #94a3b8;">
              Proximity to Sensor: <strong id="currentDistLabel" style="color: #ffd700;">22.0 cm</strong>
            </div>
          </div>

          <!-- Distance Slider -->
          <div style="margin-bottom: 1.5rem;">
            <div style="display: flex; justify-content: space-between; font-size: 0.85rem; margin-bottom: 0.4rem;">
              <span>Simulate Cup Distance (Ultrasonic Echo):</span>
              <span style="color: #4ade80;">Active Threshold: &lt; 5.0 cm</span>
            </div>
            <input type="range" id="distSlider" min="2" max="25" value="22" step="0.5" style="width: 100%; accent-color: #ffd700;" oninput="handleSliderChange(this.value)">
          </div>

          <!-- Quick Test Triggers -->
          <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.75rem;">
            <button class="btn btn-gold btn-sm" onclick="triggerPreset(250)">
              <span>🥛 250mL Glass</span>
            </button>
            <button class="btn btn-gold btn-sm" onclick="triggerPreset(500)">
              <span>🍼 500mL Bottle</span>
            </button>
            <button class="btn btn-silver btn-sm" onclick="emergencyStop()">
              <span>🛑 Stop / Remove</span>
            </button>
          </div>
        </div>
      </div>

      <!-- Right: Serial Telemetry & Database Stream -->
      <div>
        <div class="serial-monitor-panel">
          <div style="display: flex; justify-content: space-between; align-items: center;">
            <strong style="color: #ffd700;">📟 COM3 Serial Monitor (9600 Baud)</strong>
            <button class="btn btn-silver btn-sm" style="padding: 2px 8px; font-size: 0.72rem;" onclick="clearLog()">Clear</button>
          </div>
          
          <div class="serial-log-window" id="serialLogs">
            <div>[0.000] Arduino Uno Boot: Initializing GPIO Pins...</div>
            <div>[0.012] PIN 9 (TRIG) -> OUTPUT, PIN 10 (ECHO) -> INPUT</div>
            <div>[0.024] PIN 7 (RELAY) -> OUTPUT (INITIAL: LOW)</div>
            <div>[0.035] LCD 16x2 initialized at I2C Addr 0x27</div>
            <div>[0.050] Contactless Dispenser loop running at 50Hz</div>
            <div>[0.100] Awaiting container proximity &lt; 5.0 cm...</div>
          </div>

          <div style="margin-top: 0.85rem; padding-top: 0.75rem; border-top: 1px solid rgba(255,255,255,0.08); font-size: 0.76rem; color: #94a3b8;">
            <div><strong>Active Components:</strong> Arduino Uno R3, HC-SR04, Songle 5V Relay, 12V DC Solenoid, 16x2 I2C Display, PHP Telemetry API.</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    let isDispensing = false;
    let dispenseTimer = null;
    let targetMl = 250;
    let pouredMl = 0;

    function log(msg) {
      const box = document.getElementById('serialLogs');
      const time = (performance.now() / 1000).toFixed(3);
      const div = document.createElement('div');
      div.textContent = `[${time}] ${msg}`;
      box.appendChild(div);
      box.scrollTop = box.scrollHeight;
    }

    function clearLog() {
      document.getElementById('serialLogs').innerHTML = '';
      log('Serial Monitor Cleared.');
    }

    function handleSliderChange(val) {
      const dist = parseFloat(val);
      document.getElementById('currentDistLabel').textContent = dist.toFixed(1) + ' cm';
      document.getElementById('lcdLine2').textContent = 'DIST: ' + dist.toFixed(1) + 'CM';

      if (dist <= 5.0 && !isDispensing) {
        startDispensing(250);
      } else if (dist > 5.0 && isDispensing) {
        emergencyStop();
      }
    }

    function triggerPreset(ml) {
      targetMl = ml;
      document.getElementById('distSlider').value = 4.0;
      handleSliderChange(4.0);
    }

    function startDispensing(ml) {
      if (isDispensing) return;
      isDispensing = true;
      targetMl = ml;
      pouredMl = 0;

      // Update Visuals
      document.getElementById('relayStatusBadge').className = 'relay-badge on';
      document.getElementById('relayStatusBadge').textContent = '● RELAY: ACTIVE (SOLENOID OPEN)';
      document.getElementById('waterStream').classList.add('flowing');
      document.getElementById('lcdLine1').textContent = 'POURING: ' + targetMl + 'ML';

      log(`HC-SR04: Container Detected at 4.0cm (< 5.0cm THRESHOLD)`);
      log(`RELAY PIN 7 -> HIGH (12V Solenoid Valve Energized)`);
      log(`Dispensing target: ${targetMl} mL at 50 mL/sec rate...`);

      dispenseTimer = setInterval(() => {
        pouredMl += 25;
        const pct = Math.min(100, (pouredMl / targetMl) * 100);
        document.getElementById('glassLiquid').style.height = pct + '%';
        document.getElementById('lcdLine2').textContent = `DISPENSED: ${pouredMl}ML`;

        if (pouredMl >= targetMl) {
          finishDispensing();
        }
      }, 200);
    }

    function finishDispensing() {
      clearInterval(dispenseTimer);
      isDispensing = false;

      document.getElementById('waterStream').classList.remove('flowing');
      document.getElementById('relayStatusBadge').className = 'relay-badge off';
      document.getElementById('relayStatusBadge').textContent = '● RELAY: DEACTIVATED';
      document.getElementById('lcdLine1').textContent = 'DONE: TAKE CUP';
      document.getElementById('lcdLine2').textContent = `TOTAL: ${pouredMl}ML OK`;

      log(`Volumetric cutoff reached: ${pouredMl} mL.`);
      log(`RELAY PIN 7 -> LOW (12V Solenoid Valve Closed).`);
      log(`Telemetry Payload: { device_id: 'ARDUINO-01', dispensed_ml: ${pouredMl}, status: 'SUCCESS' }`);
      log(`HTTP POST to MySQL table 'dispenser_telemetry' -> RECORD #88 INSERTED (Status: 200 OK)`);
    }

    function emergencyStop() {
      if (dispenseTimer) clearInterval(dispenseTimer);
      isDispensing = false;
      document.getElementById('waterStream').classList.remove('flowing');
      document.getElementById('relayStatusBadge').className = 'relay-badge off';
      document.getElementById('relayStatusBadge').textContent = '● RELAY: DEACTIVATED';
      document.getElementById('lcdLine1').textContent = 'CONTAINER OUT';
      document.getElementById('lcdLine2').textContent = 'STANDBY...';
      document.getElementById('glassLiquid').style.height = '0%';
      document.getElementById('distSlider').value = 22;
      document.getElementById('currentDistLabel').textContent = '22.0 cm';

      log(`[SAFETY SHUTDOWN] Container removed. Relay Pin 7 set LOW immediately.`);
    }
  </script>
</body>
</html>
