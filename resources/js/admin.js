import CoreuiVue from '@coreui/vue'
import store from './store/index.js'
import CIcon from '@coreui/icons-vue'
import { iconsSet as icons } from '@/assets/icons'
import { configure, defineRule } from 'vee-validate'
configure({
  validateOnBlur: false,
  validateOnChange: false,
  validateOnInput: true,
  validateOnModelUpdate: false,
})
defineRule('password_rule', (value) => {
  return /^[A-Za-z0-9]*$/i.test(value)
})
defineRule('telephone', (value) => {
  return (
    /^0(\d-\d{4}-\d{4})+$/i.test(value.trim()) ||
    /^0(\d{3}-\d{2}-\d{4})+$/i.test(value.trim()) ||
    /^(070|080|090|050)(-\d{4}-\d{4})+$/i.test(value.trim()) ||
    /^0(\d{2}-\d{3}-\d{4})+$/i.test(value.trim()) ||
    /^0(\d{9,10})+$/i.test(value.trim())
  )
})
defineRule('kata', (value) => {
  return /^([ァ-ン]|ー)*$/i.test(value)
})

import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/inertia-vue3'
import { InertiaProgress } from '@inertiajs/progress'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m'
import Notyf from './components/common/notyf.vue'
const appName =
  window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel'

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name) =>
    resolvePageComponent(
      `./Pages/${name}.vue`,
      import.meta.glob('./Pages/**/*.vue'),
    ),
  setup({ el, app, props, plugin }) {
    return createApp({ render: () => h(app, props) })
      .use(plugin)
      .use(CoreuiVue)
      .use(store)
      .provide('icons', icons)
      .component('CIcon', CIcon)
      .component('notyf', Notyf)
      .use(ZiggyVue, Notyf)
      .mount(el)
  },
})

InertiaProgress.init({ color: '#4B5563' })
