<script setup>
import { CContainer } from '@coreui/vue'
import adminFooter from '@/Components/Admin/Include/AppFooter.vue'
import AppHeader from '@/Components/Admin/Include/AppHeader.vue'
import AppSidebar from '@/Components/Admin/Include/AppSidebar.vue'
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
    <div class="wrapper d-flex flex-column min-vh-100 bg-light">
      <AppHeader />
      <div class="body flex-grow-1">
        <CContainer lg>
          <slot name="content" />
        </CContainer>
      </div>
      <adminFooter />
    </div>
  </div>
</template>
<style lang="scss">
@import './../../sass/admin.scss';
</style>
