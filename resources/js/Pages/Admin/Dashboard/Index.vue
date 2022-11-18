<script setup>
import { Head } from '@inertiajs/inertia-vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
</script>
<template>
  <Head :title="data.title" />

  <AdminLayout>
    <template #content> 11212 </template>
  </AdminLayout>
</template>
<script>
export default {
  data() {
    return {
      model: this.data.request,
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
    onSubmit() {
      $('.loading').removeClass('hidden')
      this.$refs.formData.submit()
    },
  },
}
</script>
