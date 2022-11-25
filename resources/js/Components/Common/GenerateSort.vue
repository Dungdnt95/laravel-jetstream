<script setup>
import { Link } from '@inertiajs/inertia-vue3'
</script>
<template>
  <CTableHeaderCell v-for="(item, index) in data" :key="index">
    <Link class="sort-link" :href="item.link"
      >{{ item.name }}
      <i class="fa" :class="item.iconDirection"></i>
    </Link>
  </CTableHeaderCell>
</template>

<script>
export default {
  components: {},
  props: ['data'],
  mounted() {},
  methods: {},
}
</script>
