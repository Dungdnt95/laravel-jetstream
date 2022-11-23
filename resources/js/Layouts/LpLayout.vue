<script setup>
import { Head } from '@inertiajs/inertia-vue3'
import SpinBeforeLoaded from '@/Components/Common/SpinBeforeLoaded.vue'
import Notyf from '@/Components/Common/Notyf.vue'
</script>

<template>
  <div>
    <Head :title="$page.props.title" />
    <SpinBeforeLoaded />
    <Notyf :data="$page.props.sessionAlert" />
    <AppSidebar />
    <div>
      <slot name="content" />
    </div>
  </div>
</template>
<style lang="scss">
@import './../../sass/lp.scss';
</style>
