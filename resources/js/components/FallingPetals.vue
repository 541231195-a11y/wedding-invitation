<template>
  <div class="petals" aria-hidden="true">
    <span v-for="(p, i) in items" :key="i" class="petal"
          :style="{ left: p.l + '%', width: p.s + 'px', height: p.s * 1.5 + 'px', animationDuration: p.dur + 's', animationDelay: p.dl + 's' }"></span>
  </div>
</template>

<script>
export default {
  name: 'FallingPetals',
  data() {
    return {
      items: Array.from({ length: 16 }, (_, i) => ({
        l: (i * 37) % 100,
        s: 9 + ((i * 7) % 9),
        dur: 11 + ((i * 5) % 9),
        dl: -((i * 3.1) % 16)
      }))
    };
  }
};
</script>

<style scoped>
.petals { position: absolute; inset: 0; z-index: 4; pointer-events: none; overflow: hidden; }
.petal {
  position: absolute; top: -40px; opacity: 0;
  border-radius: 0 100% 0 100%;
  background: linear-gradient(135deg, #fff3c4, #d4af37 60%, #8f1d34);
  animation: fall linear infinite;
}
@keyframes fall {
  0% { opacity: 0; transform: translate(0, 0) rotate(0); }
  10% { opacity: .75; }
  50% { transform: translate(40px, 55vh) rotate(200deg); }
  90% { opacity: .6; }
  100% { opacity: 0; transform: translate(-30px, 108vh) rotate(420deg); }
}
@media (prefers-reduced-motion: reduce) { .petal { animation: none; opacity: 0; } }
</style>