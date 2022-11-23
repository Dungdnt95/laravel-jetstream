<script setup>
import { Head } from '@inertiajs/inertia-vue3'
import LpLayout from '@/Layouts/LpLayout.vue'
</script>
<template>
  <Head :title="data.title" />
  <LpLayout>
    <template #content> <div class="test">top</div></template>
  </LpLayout>
</template>
<script>
export default {
  data() {
    return {
      csrfToken: Laravel.csrfToken,
    }
  },
  mounted() {},
  props: {
    data: {
      type: Object,
    },
  },
  components: {},
  methods: {},
}
</script>
