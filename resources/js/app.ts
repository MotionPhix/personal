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

const appName = import.meta.env.VITE_APP_NAME || 'Ultrahots';

import { type IStaticMethods } from "preline/preline";

declare global {
  interface Window {
    HSStaticMethods: IStaticMethods;
  }
}

setTimeout(() => {
  window.HSStaticMethods.autoInit();
}, 100)

const lenis = new Lenis()

lenis.on('scroll', (e) => {
  console.log(e)
})

function raf(time: any) {
  lenis.raf(time)
  requestAnimationFrame(raf)
}

requestAnimationFrame(raf)

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob<DefineComponent>('./Pages/**/*.vue')),
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(ZiggyVue)
      .use(createPinia())
      .mount(el);
  },
  progress: {
    color: '#4B5563',
  },
});
