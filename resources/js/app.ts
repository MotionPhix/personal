import './bootstrap';
import 'maz-ui/styles'
import 'lenis/dist/lenis.css'
import '../css/app.css';

import { createApp, h, DefineComponent, onMounted } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import "preline/preline";
import Lenis from 'lenis'

import { createPinia } from 'pinia'

import { ModalRoot } from '@inertiaui/modal-vue';

const appName = import.meta.env.VITE_APP_NAME || 'Ultrahots';

const lenis = new Lenis()

lenis.on('scroll', (e) => {})

function raf(time: any) {
  lenis.raf(time)
  requestAnimationFrame(raf)
}

requestAnimationFrame(raf)

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob<DefineComponent>('./Pages/**/*.vue')),
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(ModalRoot, () => h(App, props)) })
      .use(plugin)
      .use(ZiggyVue)
      .use(createPinia())
      .mount(el);
  },
  progress: {
    color: '#4B5563',
  },
});
