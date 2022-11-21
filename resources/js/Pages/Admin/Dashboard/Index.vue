<script setup>
import { Head } from '@inertiajs/inertia-vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
</script>
<template>
  <Head :title="data.title" />
  <AdminLayout>
    <template #content> </template>
  </AdminLayout>
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
  components: {
  },
  methods: {
  },
}
</script>
