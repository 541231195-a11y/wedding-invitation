import './bootstrap';
import { createApp } from 'vue';

// Import komponen RSVP yang sudah kamu buat
import RsvpComponent from './components/RsvpComponent.vue';

/**
 * Membuat instance aplikasi Vue 3 dan mendaftarkan komponen
 */
const app = createApp({});

// Daftarkan komponen dengan nama <rsvp-component>
app.component('rsvp-component', RsvpComponent);

/**
 * Hubungkan aplikasi Vue ke elemen HTML dengan id="app"
 */
app.mount('#app');