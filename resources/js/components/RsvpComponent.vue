<template>
  <div class="invitation-wrapper">
    
    <!-- ================= FLOWER GATE & FALLING PETALS ================= -->
    <flower-gate :open="isOpen" :name="name" @open="openInvitation" />
    <falling-petals v-if="isOpen" />

    <!-- ================= 1. CINEMATIC GATE SCREEN (BACKUP) ================= -->
    <div class="gate-screen" :class="{ 'gate-opened': isOpen }" style="display:none;">
      <div class="gate-left"></div>
      <div class="gate-right"></div>
      <div class="gate-box text-center">
        <div class="ornament-top">⚜️</div>
        <div class="gold-monogram">
          <span>D</span><span class="amp">&</span><span>B</span>
        </div>
        <p class="gate-subtitle">The Wedding Celebration of</p>
        <h1 class="gate-couple">Dwi Sulis & Baidowi</h1>
        <div class="guest-welcome-card my-4">
          <span class="d-block text-gold small uppercase tracking-widest">Kepada Yth:</span>
          <h3 class="guest-name-badge">{{ name || 'Tamu Undangan' }}</h3>
          <p class="small text-light-muted mb-0">Tanpa mengurangi rasa hormat, kami mengundang Anda.</p>
        </div>
        <button @click="openInvitation" class="btn-open-gold">
          <i class="fas fa-envelope-open-text mr-2"></i> Buka Undangan
        </button>
        <div class="ornament-bottom mt-3">⚜️</div>
      </div>
    </div>

    <!-- ================= 2. MAIN STAGE (PERPINDAHAN HALAMAN) ================= -->
    <div
      class="stage"
      v-show="isOpen"
      @touchstart.passive="onTouchStart"
      @touchend.passive="onTouchEnd"
    >
      <div class="stage-bg"></div>
      <div class="stage-frame"></div>

      <!-- Audio -->
      <button class="floating-audio-btn" @click="toggleMusic" :title="isPlaying ? 'Pause Music' : 'Play Music'">
        <i :class="isPlaying ? 'fas fa-compact-disc fa-spin' : 'fas fa-play'"></i>
        <span class="audio-label">{{ isPlaying ? 'Playing' : 'Play' }}</span>
      </button>

      <!-- Page indicator -->
      <div class="page-indicator">
        <span class="pi-current">{{ pad(current + 1) }}</span>
        <span class="pi-line"></span>
        <span class="pi-total">{{ pad(pages.length) }}</span>
      </div>

      <!-- Side arrows -->
      <button class="arrow arrow-prev" @click="prev" :disabled="current === 0" aria-label="Halaman sebelumnya">
        <i class="fas fa-chevron-left"></i>
      </button>
      <button class="arrow arrow-next" @click="next" :disabled="current === pages.length - 1" aria-label="Halaman berikutnya">
        <i class="fas fa-chevron-right"></i>
      </button>

      <!-- ===== PAGES ===== -->
      <transition :name="dir">

        <!-- 1. HOME -->
        <section v-if="current === 0" key="home" class="page">
          <div class="page-inner">
            <div class="hero-frame">
              <span class="corner c-tl"></span><span class="corner c-tr"></span>
              <span class="corner c-bl"></span><span class="corner c-br"></span>
              <div class="flourish">❦</div>
              <span class="eyebrow">The Wedding Of</span>
              <h1 class="hero-names">
                <span class="gold-foil">Dwi Sulis</span>
                <span class="hero-and">&</span>
                <span class="gold-foil">Baidowi</span>
              </h1>
              <div class="divider"><i></i><b>◆</b><i></i></div>
              <p class="hero-date">Sabtu, 25 November 2024</p>
              <p class="hero-hint">Geser layar atau tekan panah untuk melanjutkan</p>
              <div class="flourish flip">❦</div>
            </div>
          </div>
        </section>

        <!-- 2. SALAM -->
        <section v-else-if="current === 1" key="salam" class="page">
          <div class="page-inner">
            <div class="luxury-card narrow">
              <span class="corner c-tl"></span><span class="corner c-tr"></span>
              <span class="corner c-bl"></span><span class="corner c-br"></span>
              <h4 class="card-title">Ar-Rum Ayat 21</h4>
              <div class="divider"><i></i><b>◆</b><i></i></div>
              <p class="arabic-font">وَمِنْ آيَاتِهِ أَنْ خَلَقَ لَكُمْ مِنْ أَنْفُسِكُمْ أَزْوَاجًا لِتَسْكُنُوا إِلَيْهَا وَجَعَلَ بَيْنَكُمْ مَوَدَّةً وَرَحْمَةً</p>
              <p class="verse-translation">
                "Dan di antara tanda-tanda kebesaran-Nya ialah Dia menciptakan pasangan-pasangan untukmu dari jenismu sendiri, agar kamu merasa tenteram kepadanya, dan Dia menjadikan di antaramu rasa kasih dan sayang."
              </p>
            </div>
          </div>
        </section>

        <!-- 3. COUPLE -->
        <section v-else-if="current === 2" key="couple" class="page">
          <div class="page-inner">
            <span class="eyebrow">Mempelai Bahagia</span>
            <h2 class="section-heading">Menuju Ikatan Suci</h2>
            <div class="divider"><i></i><b>◆</b><i></i></div>

            <div class="couple-grid">
              <div class="luxury-card couple-profile">
                <span class="corner c-tl"></span><span class="corner c-tr"></span>
                <span class="corner c-bl"></span><span class="corner c-br"></span>
                <div class="couple-avatar-frame"><div class="avatar-placeholder">DS</div></div>
                <h3 class="couple-name">Dwi Sulis Setowati, S.Pd</h3>
                <p class="muted small">Putri Tercinta Dari</p>
                <p class="parents">Bpk. Sutarno & Ibu Siti Aminah</p>
              </div>

              <div class="heart-badge">&</div>

              <div class="luxury-card couple-profile">
                <span class="corner c-tl"></span><span class="corner c-tr"></span>
                <span class="corner c-bl"></span><span class="corner c-br"></span>
                <div class="couple-avatar-frame"><div class="avatar-placeholder">B</div></div>
                <h3 class="couple-name">Baidowi, S.Kom</h3>
                <p class="muted small">Putra Tercinta Dari</p>
                <p class="parents">Bpk. H. Ahmad & Ibu Hj. Khadijah</p>
              </div>
            </div>
          </div>
        </section>

        <!-- 4. AKAD & RESEPSI -->
        <section v-else-if="current === 3" key="akad" class="page">
          <div class="page-inner">
            <span class="eyebrow">Rangkaian Acara</span>
            <h2 class="section-heading">Waktu & Tempat</h2>
            <div class="divider"><i></i><b>◆</b><i></i></div>

            <div class="event-grid">
              <div class="luxury-card event-box">
                <span class="corner c-tl"></span><span class="corner c-tr"></span>
                <span class="corner c-bl"></span><span class="corner c-br"></span>
                <div class="event-icon"><i class="fas fa-ring"></i></div>
                <h3 class="card-title">Akad Nikah</h3>
                <p class="event-date">Sabtu, 25 November 2024</p>
                <p class="muted small">Pukul 08.00 WIB - Selesai</p>
                <hr class="gold-line">
                <p class="venue">Grand Ballroom Melati</p>
                <p class="muted extra-small">Jl. Melati Raya No. 88, Jakarta Selatan</p>
              </div>

              <div class="luxury-card event-box">
                <span class="corner c-tl"></span><span class="corner c-tr"></span>
                <span class="corner c-bl"></span><span class="corner c-br"></span>
                <div class="event-icon"><i class="fas fa-glass-cheers"></i></div>
                <h3 class="card-title">Resepsi</h3>
                <p class="event-date">Sabtu, 25 November 2024</p>
                <p class="muted small">Pukul 11.00 - 14.00 WIB</p>
                <hr class="gold-line">
                <p class="venue">Grand Ballroom Melati</p>
                <p class="muted extra-small">Jl. Melati Raya No. 88, Jakarta Selatan</p>
              </div>
            </div>

            <a href="https://maps.google.com" target="_blank" rel="noopener" class="btn-open-gold btn-link">
              <i class="fas fa-map-marked-alt mr-2"></i> Buka Petunjuk Lokasi (Google Maps)
            </a>
          </div>
        </section>

        <!-- 5. COUNTDOWN & STORY -->
        <section v-else-if="current === 4" key="story" class="page">
          <div class="page-inner">
            <span class="eyebrow">Menuju Hari Bahagia</span>
            <h2 class="section-heading">Countdown & Kisah Cinta</h2>
            <div class="divider"><i></i><b>◆</b><i></i></div>

            <div class="countdown">
              <div class="cd-cell"><strong>{{ pad(countdown.days) }}</strong><small>Hari</small></div>
              <div class="cd-cell"><strong>{{ pad(countdown.hours) }}</strong><small>Jam</small></div>
              <div class="cd-cell"><strong>{{ pad(countdown.minutes) }}</strong><small>Menit</small></div>
              <div class="cd-cell"><strong>{{ pad(countdown.seconds) }}</strong><small>Detik</small></div>
            </div>

            <div class="timeline">
              <div class="timeline-item">
                <span class="badge-gold">Januari 2022</span>
                <h5>Awal Pertama Bertemu</h5>
                <p class="muted small">Kami tak sengaja bertemu di sebuah acara seminar kewirausahaan di Jakarta.</p>
              </div>
              <div class="timeline-item">
                <span class="badge-gold">Desember 2023</span>
                <h5>Komitmen & Lamaran</h5>
                <p class="muted small">Menyatukan dua keluarga besar dalam ikatan pertunangan yang suci.</p>
              </div>
            </div>
          </div>
        </section>

        <!-- 6. RSVP -->
        <section v-else-if="current === 5" key="rsvp" class="page">
          <div class="page-inner top">
            <span class="eyebrow">Konfirmasi Kehadiran</span>
            <h2 class="section-heading">Buku Tamu & RSVP</h2>
            <div class="divider"><i></i><b>◆</b><i></i></div>

            <div class="rsvp-grid">
              <div class="luxury-card form-card">
                <span class="corner c-tl"></span><span class="corner c-tr"></span>
                <span class="corner c-bl"></span><span class="corner c-br"></span>
                <form @submit.prevent="handleSubmit">
                  <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" v-model="data.name" required class="luxury-input" placeholder="Masukkan nama Anda..." />
                  </div>
                  <div class="form-group">
                    <label>Konfirmasi Kehadiran</label>
                    <select v-model="data.attendance" required class="luxury-input">
                      <option value="Hadir">Ya, Saya Akan Hadir</option>
                      <option value="Tidak Hadir">Maaf, Berhalangan Hadir</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Ucapan & Doa Restu</label>
                    <textarea v-model="data.comment" rows="3" class="luxury-input" placeholder="Tuliskan ucapan dan doa terbaik..."></textarea>
                  </div>
                  <button type="submit" class="btn-open-gold btn-block" :disabled="loader">
                    {{ loader ? 'Mengirim Data...' : 'Kirim Konfirmasi RSVP' }}
                  </button>
                </form>
              </div>

              <div class="luxury-card comment-card">
                <span class="corner c-tl"></span><span class="corner c-tr"></span>
                <span class="corner c-bl"></span><span class="corner c-br"></span>
                <h4 class="card-title">Ucapan & Doa Tamu</h4>
                <div class="comment-stream">
                  <p v-if="!guest_books.length" class="muted small empty">Belum ada ucapan. Jadilah yang pertama memberi doa.</p>
                  <div v-for="gb in guest_books" :key="gb.id" class="comment-bubble">
                    <div class="cb-head">
                      <strong>{{ gb.name }}</strong>
                      <span class="badge-gold" :class="{ dim: gb.attendance !== 'Hadir' }">{{ gb.attendance }}</span>
                    </div>
                    <p class="muted small">{{ gb.comment }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- 7. THANKS -->
        <section v-else key="thanks" class="page">
          <div class="page-inner">
            <div class="luxury-card narrow thanks-card">
              <span class="corner c-tl"></span><span class="corner c-tr"></span>
              <span class="corner c-bl"></span><span class="corner c-br"></span>
              <div class="gold-monogram"><span>D</span><span class="amp">&</span><span>B</span></div>
              <h2 class="section-heading small-h">Terima Kasih</h2>
              <div class="divider"><i></i><b>◆</b><i></i></div>
              <p class="verse-translation">
                Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir memberikan doa restu kepada kedua mempelai.
              </p>
              <p class="closing">Kami yang berbahagia,</p>
              <h3 class="hero-names sign"><span class="gold-foil">Dwi Sulis & Baidowi</span></h3>
            </div>
          </div>
        </section>

      </transition>

      <!-- BOTTOM NAV -->
      <nav class="luxury-navbar">
        <button
          v-for="(p, i) in pages"
          :key="p.id"
          class="nav-item"
          :class="{ active: current === i }"
          @click="goTo(i)"
        >
          <i :class="p.icon"></i><span>{{ p.label }}</span>
        </button>
      </nav>
    </div>
  </div>
</template>

<script>
import FlowerGate from './FlowerGate.vue';
import FallingPetals from './FallingPetals.vue';

export default {
  name: 'RsvpComponent',
  components: { 
    FlowerGate, 
    FallingPetals 
  },
  props: {
    invitationId: { type: [Number, String], default: 1 },
    name: { type: String, default: '' },
    code: { type: String, default: '' }
  },
  data() {
    return {
      isOpen: false,
      loader: false,
      isPlaying: false,
      audio: null,

      // Halaman
      current: 0,
      dir: 'slide-next',
      touch: { x: 0, y: 0 },
      pages: [
        { id: 'home',   label: 'Home',  icon: 'fas fa-home' },
        { id: 'salam',  label: 'Salam', icon: 'fas fa-book-open' },
        { id: 'couple', label: 'Couple', icon: 'fas fa-heart' },
        { id: 'akad',   label: 'Acara', icon: 'fas fa-calendar-alt' },
        { id: 'story',  label: 'Story', icon: 'fas fa-hourglass-half' },
        { id: 'rsvp',   label: 'RSVP',  icon: 'fas fa-comments' },
        { id: 'thanks', label: 'Thanks', icon: 'fas fa-gift' }
      ],

      weddingDate: '2024-11-25T08:00:00+07:00',
      countdown: { days: 0, hours: 0, minutes: 0, seconds: 0 },
      timer: null,

      data: {
        name: this.name,
        attendance: 'Hadir',
        guest: 1,
        comment: ''
      },
      guest_books: []
    };
  },
  mounted() {
    this.audio = new Audio('https://www.bensound.com/bensound-music/bensound-romantic.mp3');
    this.audio.loop = true;
    this.fetchGuestBooks();
    this.tick();
    this.timer = setInterval(this.tick, 1000);
    window.addEventListener('keydown', this.onKey);
  },
  beforeDestroy() { this.cleanup(); },
  beforeUnmount() { this.cleanup(); },
  methods: {
    cleanup() {
      clearInterval(this.timer);
      window.removeEventListener('keydown', this.onKey);
      if (this.audio) this.audio.pause();
    },
    pad(n) { return String(n).padStart(2, '0'); },

    /* ---------- Gate ---------- */
    openInvitation() {
      this.isOpen = true;
      this.audio.play().catch(() => {});
      this.isPlaying = true;
    },
    toggleMusic() {
      if (this.isPlaying) {
        this.audio.pause();
      } else {
        this.audio.play().catch(() => {});
      }
      this.isPlaying = !this.isPlaying;
    },

    /* ---------- Perpindahan halaman ---------- */
    goTo(i) {
      if (i === this.current || i < 0 || i >= this.pages.length) return;
      this.dir = i > this.current ? 'slide-next' : 'slide-prev';
      this.$nextTick(() => { this.current = i; });
    },
    next() { this.goTo(this.current + 1); },
    prev() { this.goTo(this.current - 1); },
    onKey(e) {
      if (!this.isOpen) return;
      const tag = (e.target.tagName || '').toLowerCase();
      if (['input', 'textarea', 'select'].includes(tag)) return;
      if (e.key === 'ArrowRight') this.next();
      if (e.key === 'ArrowLeft') this.prev();
    },
    onTouchStart(e) {
      this.touch.x = e.changedTouches[0].clientX;
      this.touch.y = e.changedTouches[0].clientY;
    },
    onTouchEnd(e) {
      const dx = e.changedTouches[0].clientX - this.touch.x;
      const dy = e.changedTouches[0].clientY - this.touch.y;
      if (Math.abs(dx) > 70 && Math.abs(dx) > Math.abs(dy) * 1.5) {
        dx < 0 ? this.next() : this.prev();
      }
    },

    /* ---------- Countdown ---------- */
    tick() {
      const diff = Math.max(0, new Date(this.weddingDate).getTime() - Date.now());
      this.countdown = {
        days: Math.floor(diff / 86400000),
        hours: Math.floor((diff % 86400000) / 3600000),
        minutes: Math.floor((diff % 3600000) / 60000),
        seconds: Math.floor((diff % 60000) / 1000)
      };
    },

    /* ---------- API ---------- */
    fetchGuestBooks() {
      axios.get(`/api/guest-books/${this.invitationId}`).then(res => {
        this.guest_books = res.data.data.data || res.data.data || [];
      }).catch(() => {});
    },
    handleSubmit() {
      this.loader = true;
      axios.post(`/api/guest-books/${this.invitationId}`, this.data).then(() => {
        this.loader = false;
        alert('Terima kasih, konfirmasi kehadiran berhasil dikirim!');
        this.data.comment = '';
        this.fetchGuestBooks();
      }).catch(() => {
        this.loader = false;
        alert('Gagal mengirim data, silakan coba lagi.');
      });
    }
  }
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700&family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400&family=Playfair+Display:ital,wght@0,500;0,700;1,400&family=Pinyon+Script&family=Amiri:wght@700&family=Poppins:wght@300;400;500&display=swap');

.invitation-wrapper {
  --burgundy-900: #14030a;
  --burgundy-800: #2b090e;
  --burgundy-700: #4a0e17;
  --gold: #d4af37;
  --gold-light: #f3e5ab;
  --gold-deep: #9c7a1c;
  --ivory: #f7f0e1;
  --ivory-muted: #cdbfae;

  background: var(--burgundy-900);
  color: var(--ivory);
  font-family: 'Cormorant Garamond', serif;
  font-size: 17px;
  min-height: 100vh;
  position: relative;
  overflow: hidden;
}

.text-center { text-align: center; }
.my-4 { margin: 1.5rem 0; }
.mt-3 { margin-top: 1rem; }
.mb-0 { margin-bottom: 0; }
.mr-2 { margin-right: .5rem; }
.d-block { display: block; }
.small { font-size: .9rem; }
.extra-small { font-size: .8rem; }
.uppercase { text-transform: uppercase; }
.tracking-widest { letter-spacing: 3px; }
.text-gold { color: var(--gold) !important; }
.text-light-muted { color: #e0d0d2; }
.muted { color: var(--ivory-muted); }

.btn-open-gold {
  background: linear-gradient(135deg, #f3e5ab 0%, #D4AF37 55%, #b8932c 100%);
  color: #2b090e; border: none; font-weight: 600; font-family: 'Poppins', sans-serif;
  padding: 12px 35px; border-radius: 50px;
  box-shadow: 0 5px 20px rgba(212, 175, 55, 0.4);
  transition: .3s; cursor: pointer; text-decoration: none; display: inline-block;
}
.btn-open-gold:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(212, 175, 55, 0.6); color: #000; }
.btn-open-gold:disabled { opacity: .6; cursor: wait; transform: none; }
.btn-link { margin-top: 28px; font-size: .9rem; }
.btn-block { width: 100%; display: block; padding: 14px; }

.stage {
  position: fixed; inset: 0; z-index: 1;
  background: var(--burgundy-900);
  overflow: hidden;
}
.stage-bg {
  position: absolute; inset: 0;
  background:
    radial-gradient(ellipse at 50% 0%, rgba(212,175,55,.16), transparent 55%),
    radial-gradient(ellipse at 50% 100%, rgba(212,175,55,.10), transparent 50%),
    radial-gradient(circle at 20% 30%, #4a0e17 0%, transparent 60%),
    radial-gradient(circle at 80% 70%, #3a0a12 0%, transparent 60%),
    linear-gradient(160deg, #2b090e, #14030a);
}
.stage-bg::after {
  content: ''; position: absolute; inset: 0; opacity: .07;
  background-image:
    radial-gradient(circle at 50% 50%, var(--gold) 1px, transparent 1.5px),
    radial-gradient(circle at 0 0, var(--gold) 1px, transparent 1.5px);
  background-size: 34px 34px, 34px 34px;
  background-position: 0 0, 17px 17px;
}
.stage-frame {
  position: absolute; inset: 14px; pointer-events: none; z-index: 5;
  border: 1px solid rgba(212,175,55,.75);
  box-shadow: inset 0 0 0 5px transparent, inset 0 0 0 6px rgba(212,175,55,.3);
}

.page {
  position: absolute; inset: 0;
  overflow-y: auto; overflow-x: hidden;
  -webkit-overflow-scrolling: touch;
  scrollbar-width: none;
  z-index: 2;
}
.page::-webkit-scrollbar { display: none; }
.page-inner {
  min-height: 100%;
  max-width: 960px; margin: 0 auto;
  padding: 70px 28px 120px;
  display: flex; flex-direction: column; align-items: center; justify-content: center;
  text-align: center;
}
.page-inner.top { justify-content: flex-start; padding-top: 80px; }

.slide-next-enter-active, .slide-next-leave-active,
.slide-prev-enter-active, .slide-prev-leave-active {
  transition: transform .9s cubic-bezier(.77,0,.175,1), opacity .9s ease, filter .9s ease;
}
.slide-next-enter, .slide-next-enter-from { transform: translateX(14%) scale(.94); opacity: 0; filter: blur(8px); }
.slide-next-leave-to { transform: translateX(-14%) scale(1.04); opacity: 0; filter: blur(8px); }
.slide-prev-enter, .slide-prev-enter-from { transform: translateX(-14%) scale(.94); opacity: 0; filter: blur(8px); }
.slide-prev-leave-to { transform: translateX(14%) scale(1.04); opacity: 0; filter: blur(8px); }

.eyebrow {
  font-family: 'Cinzel', serif; font-size: .8rem; letter-spacing: 5px;
  text-transform: uppercase; color: var(--gold);
}
.section-heading {
  font-family: 'Playfair Display', serif; font-weight: 500;
  font-size: clamp(1.9rem, 5vw, 2.8rem);
  color: var(--ivory); margin: 10px 0 6px; line-height: 1.2;
}
.section-heading.small-h { font-size: 2rem; }
.card-title { font-family: 'Playfair Display', serif; color: var(--gold); font-weight: 500; font-size: 1.5rem; margin: 0 0 6px; }

.gold-foil {
  background: linear-gradient(100deg, #8a6a14 0%, #f3e5ab 25%, #d4af37 45%, #fff3c4 55%, #b8932c 75%, #f3e5ab 100%);
  background-size: 220% 100%;
  -webkit-background-clip: text; background-clip: text;
  -webkit-text-fill-color: transparent; color: transparent;
  animation: foil 6s ease-in-out infinite alternate;
}
@keyframes foil { from { background-position: 0% 50%; } to { background-position: 100% 50%; } }

.divider { display: flex; align-items: center; justify-content: center; gap: 12px; margin: 14px auto 26px; width: min(280px, 70%); }
.divider i { flex: 1; height: 1px; background: linear-gradient(90deg, transparent, var(--gold)); }
.divider i:last-child { background: linear-gradient(270deg, transparent, var(--gold)); }
.divider b { color: var(--gold); font-size: .7rem; font-weight: normal; }
.flourish { color: var(--gold); font-size: 2rem; opacity: .9; }
.flourish.flip { transform: rotate(180deg); }

.corner { position: absolute; width: 22px; height: 22px; border: 2px solid var(--gold); pointer-events: none; }
.c-tl { top: 8px; left: 8px; border-right: 0; border-bottom: 0; }
.c-tr { top: 8px; right: 8px; border-left: 0; border-bottom: 0; }
.c-bl { bottom: 8px; left: 8px; border-right: 0; border-top: 0; }
.c-br { bottom: 8px; right: 8px; border-left: 0; border-top: 0; }

.hero-frame {
  position: relative; width: 100%; max-width: 620px;
  padding: 52px 34px;
  background: linear-gradient(180deg, rgba(20,3,10,.72), rgba(43,9,14,.72));
  border: 1px solid rgba(212,175,55,.6);
  box-shadow: 0 25px 70px rgba(0,0,0,.6), inset 0 0 60px rgba(212,175,55,.06);
}
.hero-names { font-family: 'Pinyon Script', cursive; font-weight: 400; font-size: clamp(3.4rem, 12vw, 5.6rem); line-height: 1.05; margin: 18px 0 6px; display: flex; flex-direction: column; align-items: center; }
.hero-and { font-family: 'Playfair Display', serif; font-style: italic; font-size: 1.8rem; color: var(--ivory); margin: 2px 0; }
.hero-date { font-family: 'Cinzel', serif; letter-spacing: 3px; color: var(--gold-light); font-size: 1rem; margin: 0 0 18px; }
.hero-hint { font-size: .9rem; color: var(--ivory-muted); font-style: italic; margin: 0 0 12px; }

.luxury-card {
  position: relative;
  background: linear-gradient(160deg, rgba(74,14,23,.62), rgba(20,3,10,.82));
  border: 1px solid rgba(212,175,55,.65);
  box-shadow: 0 20px 50px rgba(0,0,0,.5), inset 0 0 0 5px rgba(20,3,10,.4), inset 0 0 0 6px rgba(212,175,55,.2);
  padding: 38px 30px; width: 100%;
  backdrop-filter: blur(4px);
}
.luxury-card.narrow { max-width: 680px; }

.arabic-font { font-family: 'Amiri', serif; font-size: 1.9rem; color: var(--gold-light); direction: rtl; line-height: 2.5rem; margin: 6px 0 18px; }
.verse-translation { font-size: 1.15rem; line-height: 1.75; font-style: italic; color: var(--ivory); margin: 0; }

.couple-grid { display: grid; grid-template-columns: 1fr; gap: 14px; align-items: center; width: 100%; }
.couple-avatar-frame {
  width: 124px; height: 124px; margin: 0 auto 16px; border-radius: 50%;
  border: 2px solid var(--gold); padding: 6px;
  display: flex; align-items: center; justify-content: center;
  background: radial-gradient(circle, #4a0e17, #14030a);
  box-shadow: 0 0 0 5px rgba(212,175,55,.18);
}
.avatar-placeholder { font-family: 'Cinzel', serif; font-size: 2rem; color: var(--gold); font-weight: 700; }
.couple-name { font-family: 'Playfair Display', serif; color: var(--gold); font-weight: 500; font-size: 1.4rem; margin: 0 0 6px; }
.parents { font-weight: 600; font-size: 1.1rem; margin: 0; }
.heart-badge { font-size: 2.6rem; font-family: 'Playfair Display', serif; font-style: italic; color: var(--gold); }

.event-grid { display: grid; grid-template-columns: 1fr; gap: 20px; width: 100%; }
.event-icon { font-size: 1.6rem; color: var(--gold); margin-bottom: 10px; }
.event-date { font-weight: 600; font-size: 1.15rem; margin: 4px 0; }
.venue { font-family: 'Playfair Display', serif; font-size: 1.05rem; margin: 0 0 4px; }
.gold-line { border: 0; border-top: 1px dashed rgba(212,175,55,.7); margin: 16px 0; }

.countdown { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; width: 100%; max-width: 560px; margin-bottom: 30px; }
.cd-cell {
  border: 1px solid rgba(212,175,55,.65); padding: 16px 4px;
  background: linear-gradient(160deg, rgba(74,14,23,.7), rgba(20,3,10,.85));
  box-shadow: inset 0 0 0 3px rgba(20,3,10,.5), inset 0 0 0 4px rgba(212,175,55,.2);
}
.cd-cell strong { display: block; font-family: 'Cinzel', serif; font-size: clamp(1.5rem, 6vw, 2.3rem); color: var(--gold); font-weight: 600; }
.cd-cell small { color: var(--ivory-muted); font-size: .85rem; letter-spacing: 2px; }

.timeline { width: 100%; max-width: 560px; text-align: left; border-left: 1px solid rgba(212,175,55,.6); padding-left: 24px; }
.timeline-item { position: relative; margin-bottom: 22px; }
.timeline-item::before { content: '◆'; position: absolute; left: -31px; top: 0; color: var(--gold); font-size: .7rem; background: var(--burgundy-900); padding: 2px 0; }
.timeline-item h5 { font-family: 'Playfair Display', serif; color: var(--gold); font-size: 1.2rem; font-weight: 500; margin: 8px 0 4px; }
.timeline-item p { margin: 0; line-height: 1.5; }
.badge-gold { display: inline-block; background: linear-gradient(135deg, #f3e5ab, #D4AF37); color: #1c0408; font-family: 'Poppins', sans-serif; font-size: .72rem; font-weight: 600; padding: 4px 12px; border-radius: 20px; }
.badge-gold.dim { background: #6b5a5d; color: #eee; }

.rsvp-grid { display: grid; grid-template-columns: 1fr; gap: 20px; width: 100%; text-align: left; }
.form-card, .comment-card { padding: 30px 26px; }
.form-group { margin-bottom: 16px; }
.form-group label { display: block; font-family: 'Cinzel', serif; font-size: .75rem; letter-spacing: 2px; color: var(--gold); margin-bottom: 6px; }
.luxury-input {
  width: 100%; box-sizing: border-box;
  border: 1px solid rgba(212,175,55,.7); border-radius: 0;
  padding: 12px; background: rgba(20,3,10,.65); color: var(--ivory);
  font-family: 'Cormorant Garamond', serif; font-size: 1.05rem;
}
.luxury-input::placeholder { color: #a08f8f; }
.luxury-input:focus { outline: none; border-color: var(--gold-light); box-shadow: 0 0 0 3px rgba(212,175,55,.2); background: rgba(43,9,14,.9); }
.luxury-input option { background: #2b090e; }

.comment-card .card-title { text-align: center; margin-bottom: 14px; }
.comment-stream { max-height: 320px; overflow-y: auto; padding-right: 4px; scrollbar-width: thin; scrollbar-color: var(--gold-deep) transparent; }
.comment-bubble { background: rgba(212,175,55,.07); border-left: 3px solid var(--gold); padding: 12px 14px; margin-bottom: 12px; }
.comment-bubble p { margin: 4px 0 0; line-height: 1.45; }
.cb-head { display: flex; justify-content: space-between; align-items: center; gap: 8px; }
.cb-head strong { color: var(--gold-light); font-family: 'Playfair Display', serif; font-weight: 500; }
.empty { text-align: center; font-style: italic; }

.thanks-card .gold-monogram { margin-bottom: 6px; }
.closing { margin: 26px 0 0; font-weight: 600; color: var(--gold-light); }
.hero-names.sign { font-size: clamp(2.4rem, 8vw, 3.4rem); margin: 4px 0 0; }

.floating-audio-btn {
  position: absolute; top: 28px; right: 28px; z-index: 20;
  background: rgba(43,9,14,.85); color: var(--gold);
  border: 1px solid var(--gold); padding: 8px 16px; border-radius: 30px;
  display: flex; align-items: center; gap: 8px; cursor: pointer;
  font-family: 'Poppins', sans-serif; font-size: .8rem;
  box-shadow: 0 5px 20px rgba(0,0,0,.6);
}
.page-indicator {
  position: absolute; top: 34px; left: 32px; z-index: 20;
  display: flex; align-items: center; gap: 10px;
  font-family: 'Cinzel', serif; color: var(--gold); font-size: .85rem; letter-spacing: 2px;
}
.pi-line { width: 34px; height: 1px; background: var(--gold); }
.pi-total { color: var(--ivory-muted); }

.arrow {
  position: absolute; top: 50%; transform: translateY(-50%); z-index: 20;
  width: 44px; height: 44px; border-radius: 50%;
  background: rgba(43,9,14,.8); color: var(--gold);
  border: 1px solid var(--gold); cursor: pointer;
  display: none; align-items: center; justify-content: center; transition: .3s;
}
.arrow:hover:not(:disabled) { background: var(--gold); color: #2b090e; }
.arrow:disabled { opacity: .25; cursor: default; }
.arrow-prev { left: 28px; }
.arrow-next { right: 28px; }

.luxury-navbar {
  position: absolute; bottom: 0; left: 50%; transform: translateX(-50%);
  width: 100%; max-width: 760px; z-index: 20;
  display: flex; justify-content: space-around;
  padding: 8px 6px calc(8px + env(safe-area-inset-bottom, 0px));
  background: linear-gradient(180deg, rgba(43,9,14,.92), #14030a);
  border-top: 1px solid var(--gold);
  box-shadow: 0 -8px 30px rgba(0,0,0,.6);
}
.nav-item {
  flex: 1; background: none; border: 0; cursor: pointer;
  color: #bfa461; font-family: 'Poppins', sans-serif; font-size: .62rem;
  display: flex; flex-direction: column; align-items: center; gap: 3px;
  padding: 6px 0; position: relative; transition: .3s;
}
.nav-item i { font-size: 1.05rem; }
.nav-item::after {
  content: ''; position: absolute; top: -9px; left: 50%; width: 0; height: 2px;
  background: var(--gold); transition: .4s; transform: translateX(-50%);
}
.nav-item:hover { color: var(--gold-light); }
.nav-item.active { color: #fff; }
.nav-item.active::after { width: 60%; }

@media (min-width: 768px) {
  .couple-grid { grid-template-columns: 1fr auto 1fr; }
  .event-grid { grid-template-columns: 1fr 1fr; }
  .rsvp-grid { grid-template-columns: 1fr 1fr; }
  .arrow { display: flex; }
  .page-inner { padding-left: 90px; padding-right: 90px; }
}
@media (max-width: 480px) {
  .page-inner { padding: 64px 20px 110px; }
  .stage-frame { inset: 8px; }
  .page-indicator { left: 22px; top: 26px; }
  .floating-audio-btn { right: 18px; top: 20px; }
}

@media (prefers-reduced-motion: reduce) {
  .gold-foil { animation: none; }
  .slide-next-enter-active, .slide-next-leave-active,
  .slide-prev-enter-active, .slide-prev-leave-active { transition: opacity .3s ease; }
  .slide-next-enter, .slide-next-enter-from, .slide-next-leave-to,
  .slide-prev-enter, .slide-prev-enter-from, .slide-prev-leave-to { transform: none; filter: none; }
}
</style>