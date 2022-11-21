<script setup>
import { Link } from '@inertiajs/inertia-vue3'
</script>
<template>
  <CBreadcrumb class="d-md-down-none me-auto mb-0">
    <li class="breadcrumb-item active">
      <Link href="/">ホーム</Link>
    </li>
    <li class="breadcrumb-item" :class="{active: item.url ? true : false}" v-for="(item, index) in $page.props.breadcrumbs"
      :key="index">
      <Link :href="item.url">{{ item.name }}</Link>
    </li>
    <!-- <CBreadcrumbItem
      v-for="(item, index) in $page.props.breadcrumbs"
      :key="index"
      :href="item.url ? item.url : ''"
      :active="item.url ? true : false"
    >
      {{ item.name }}
    </CBreadcrumbItem> -->
  </CBreadcrumb>
</template>

<script>
export default {
  name: 'AppBreadcrumb',
  created() {},
}
</script>
