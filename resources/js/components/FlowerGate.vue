<template>
  <div class="fg" :class="{ opened: open }">
    <!-- Doors with swaying vines + small flowers -->
    <div v-for="s in ['l', 'r']" :key="s" class="door" :class="'door-' + s">
      <div class="vine" :style="{ animationDelay: s === 'r' ? '-2s' : '0s' }">
        <svg class="stem" viewBox="0 0 120 600" preserveAspectRatio="none">
          <path d="M112 0C40 120 130 220 60 320S120 500 70 600" />
          <ellipse v-for="l in leaves" :key="l.y" :cx="l.x" :cy="l.y" rx="22" ry="8" :transform="`rotate(${l.r} ${l.x} ${l.y})`" />
        </svg>
        <svg v-for="(m, i) in minis" :key="i" class="mini" viewBox="-30 -30 60 60"
             :style="{ top: m.top + '%', right: m.right + 'px', width: m.size + 'px', animationDelay: i * 0.4 + 's' }">
          <g v-for="n in 6" :key="n" :transform="`rotate(${(n - 1) * 60})`">
            <path d="M0 0C-8-6-9-20 0-26C9-20 8-6 0 0Z" fill="url(#fg-g1)" />
          </g>
          <circle r="4" fill="#d4af37" />
        </svg>
      </div>
    </div>

    <!-- Centre: big blooming flower + invitation card -->
    <div class="center">
      <svg class="bloom" viewBox="-100 -100 200 200" aria-hidden="true">
        <defs>
          <linearGradient id="fg-g0" x1="0" y1="0" x2="0" y2="-1"><stop offset="0" stop-color="#8f1d34" /><stop offset=".55" stop-color="#d4af37" /><stop offset="1" stop-color="#f3e5ab" /></linearGradient>
          <linearGradient id="fg-g1" x1="0" y1="0" x2="0" y2="-1"><stop offset="0" stop-color="#b8932c" /><stop offset="1" stop-color="#fff3c4" /></linearGradient>
          <linearGradient id="fg-g2" x1="0" y1="0" x2="0" y2="-1"><stop offset="0" stop-color="#f3e5ab" /><stop offset="1" stop-color="#fffaf0" /></linearGradient>
        </defs>
        <g v-for="(r, ri) in rings" :key="ri" class="ring" :style="{ animationDirection: ri % 2 ? 'reverse' : 'normal' }">
          <g v-for="n in r.n" :key="n" :transform="`rotate(${(n - 1) * 360 / r.n + r.off})`">
            <path class="petal" d="M0 0C-16-14-18-46 0-64C18-46 16-14 0 0Z"
                  :fill="`url(#fg-g${r.g})`" :style="{ '--s': r.s, '--d': (3 - ri) * 0.12 + 's' }" />
          </g>
        </g>
        <circle r="7" fill="#d4af37" stroke="#fff3c4" stroke-width="1" />
      </svg>

      <div class="gate-box text-center">
        <div class="gold-monogram"><span>D</span><span class="amp">&amp;</span><span>B</span></div>
        <p class="gate-subtitle">The Wedding Celebration of</p>
        <h1 class="gate-couple">Dwi Sulis &amp; Baidowi</h1>
        <div class="guest-card">
          <span class="to">Kepada Yth:</span>
          <h3 class="guest-name">{{ name || 'Tamu Undangan' }}</h3>
          <p>Tanpa mengurangi rasa hormat, kami mengundang Anda.</p>
        </div>
        <button class="btn-open" @click="$emit('open')">Buka Undangan</button>
      </div>
    </div>

    <!-- Petal burst when the flower blooms -->
    <span v-for="(b, i) in burst" :key="'b' + i" class="burst"
          :style="{ '--x': b.x + 'px', '--y': b.y + 'px', '--r': b.r + 'deg', '--d': b.d + 's' }"></span>
  </div>
</template>

<script>
export default {
  name: 'FlowerGate',
  props: { open: { type: Boolean, default: false }, name: { type: String, default: '' } },
  data() {
    const rings = [
      { n: 8, off: 0, s: 1, g: 0 },
      { n: 7, off: 20, s: 0.78, g: 1 },
      { n: 6, off: 10, s: 0.56, g: 2 },
      { n: 5, off: 30, s: 0.34, g: 2 }
    ];
    const leaves = [
      { x: 80, y: 90, r: -30 }, { x: 95, y: 200, r: 25 }, { x: 55, y: 330, r: -25 },
      { x: 90, y: 440, r: 30 }, { x: 70, y: 540, r: -20 }
    ];
    const minis = [
      { top: 14, right: -24, size: 70 }, { top: 42, right: -34, size: 88 }, { top: 72, right: -22, size: 64 }
    ];
    const burst = Array.from({ length: 22 }, (_, i) => {
      const a = (i / 22) * Math.PI * 2;
      const dist = 150 + ((i * 53) % 190);
      return { x: Math.round(Math.cos(a) * dist), y: Math.round(Math.sin(a) * dist * 0.8) - 40, r: (i * 47) % 360, d: 0.5 + (i % 6) * 0.07 };
    });
    return { rings, leaves, minis, burst };
  }
};
</script>

<style scoped>
.fg {
  position: fixed; inset: 0; z-index: 99999; overflow: hidden;
  background: radial-gradient(circle, #4a0e17 0%, #170306 100%);
  font-family: 'Poppins', sans-serif;
  display: flex; justify-content: center; align-items: center;
  transition: opacity 1s ease 2.4s, visibility 0s linear 3.4s;
}
.fg.opened { opacity: 0; visibility: hidden; pointer-events: none; background: transparent; }

.door {
  position: absolute; top: 0; width: 50%; height: 100%;
  background: linear-gradient(135deg, #4a0e17, #1c0408);
  transition: transform 1.5s cubic-bezier(.77, 0, .175, 1) 1.1s;
}
.door-l { left: 0; border-right: 2px solid #d4af37; }
.door-r { right: 0; border-left: 2px solid #d4af37; }
.opened .door-l { transform: translateX(-100%); }
.opened .door-r { transform: translateX(100%); }
.vine { position: absolute; top: 0; bottom: 0; right: 0; width: 140px; transform-origin: 100% 0; animation: sway 6s ease-in-out infinite alternate; }
.door-r .vine { right: auto; left: 0; transform: scaleX(-1); transform-origin: 0 0; }
.stem { position: absolute; inset: 0; width: 100%; height: 100%; overflow: visible; }
.stem path { fill: none; stroke: #b8932c; stroke-width: 2.5; }
.stem ellipse { fill: #6b7a2e; opacity: .85; }
.mini { position: absolute; animation: spin 18s linear infinite; }

.center { position: relative; z-index: 10; width: 90%; max-width: 500px; display: flex; flex-direction: column; align-items: center; }
.bloom { width: 240px; height: 240px; margin-bottom: -96px; position: relative; z-index: 11; overflow: visible; filter: drop-shadow(0 8px 18px rgba(0, 0, 0, .55)); }
.fg:not(.opened) .bloom { animation: breathe 3.2s ease-in-out infinite; }
.opened .bloom { transform: scale(1.6); opacity: 0; transition: transform 1.6s ease 1s, opacity 1s ease 1.4s; }
.ring { animation: spin 40s linear infinite; transform-origin: 0 0; }
.petal {
  transform-origin: 0 0; stroke: rgba(255, 243, 196, .55); stroke-width: .8;
  transform: scale(calc(var(--s) * .55), calc(var(--s) * .4));
  transition: transform 1.4s cubic-bezier(.2, 1.3, .4, 1) var(--d);
}
.opened .petal { transform: scale(calc(var(--s) * 1.18), calc(var(--s) * 1.22)); }

.gate-box {
  width: 100%; box-sizing: border-box; padding: 110px 36px 36px;
  background: rgba(43, 9, 14, .88); border: 2px solid #d4af37; border-radius: 15px;
  box-shadow: 0 15px 50px rgba(0, 0, 0, .8), inset 0 0 0 6px rgba(43, 9, 14, .85), inset 0 0 0 7px rgba(212, 175, 55, .45);
  transition: opacity .6s ease, transform .6s ease;
}
.opened .gate-box { opacity: 0; transform: translateY(20px) scale(.96); }
.text-center { text-align: center; }
.gold-monogram { font-family: 'Cinzel', serif; font-size: 2.2rem; color: #d4af37; letter-spacing: 5px; }
.gold-monogram .amp { font-style: italic; font-family: 'Playfair Display', serif; }
.gate-subtitle { color: #f3e5ab; font-size: .85rem; text-transform: uppercase; letter-spacing: 2px; margin: 6px 0; }
.gate-couple { font-family: 'Playfair Display', serif; color: #fff; font-size: 2rem; margin: 0 0 18px; }
.guest-card { background: rgba(212, 175, 55, .1); border: 1px dashed #d4af37; padding: 14px; border-radius: 10px; margin-bottom: 22px; }
.guest-card .to { color: #d4af37; font-size: .85rem; letter-spacing: 3px; text-transform: uppercase; }
.guest-name { font-family: 'Playfair Display', serif; color: #d4af37; font-size: 1.7rem; margin: 5px 0; }
.guest-card p { color: #e0d0d2; font-size: .85rem; margin: 0; }
.btn-open {
  background: linear-gradient(135deg, #f3e5ab, #d4af37 55%, #b8932c); color: #2b090e; border: 0;
  font: 600 1rem 'Poppins', sans-serif; padding: 12px 36px; border-radius: 50px; cursor: pointer;
  box-shadow: 0 5px 20px rgba(212, 175, 55, .4); transition: .3s;
}
.btn-open:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(212, 175, 55, .6); }

.burst {
  position: absolute; left: 50%; top: 38%; width: 18px; height: 28px; margin: -14px 0 0 -9px; z-index: 12;
  border-radius: 0 100% 0 100%; background: linear-gradient(135deg, #fff3c4, #d4af37 60%, #8f1d34); opacity: 0; pointer-events: none;
}
.opened .burst { animation: burst 2.6s ease-out var(--d) forwards; }

@keyframes burst {
  0% { opacity: 0; transform: translate(0, 0) scale(.2) rotate(0); }
  15% { opacity: 1; }
  55% { transform: translate(var(--x), var(--y)) scale(1.1) rotate(var(--r)); }
  100% { opacity: 0; transform: translate(calc(var(--x) * 1.15), calc(var(--y) + 280px)) scale(.9) rotate(calc(var(--r) * 2)); }
}
@keyframes breathe { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.06); } }
@keyframes spin { to { transform: rotate(360deg); } }
@keyframes sway { from { rotate: -1.6deg; } to { rotate: 1.6deg; } }
</style>