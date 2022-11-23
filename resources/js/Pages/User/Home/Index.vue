<script setup>
import { Head } from '@inertiajs/inertia-vue3'
import UserLayout from '@/Layouts/UserLayout.vue'
</script>
<template>
  <Head :title="data.title" />
  <UserLayout>
    <template #content> <div class="test">top</div></template>
  </UserLayout>
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
