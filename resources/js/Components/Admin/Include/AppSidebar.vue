
<template>
  <CSidebar
    position="fixed"
    :unfoldable="sidebarUnfoldable"
    :visible="sidebarVisible"
  >
    <CSidebarBrand>
      <CIcon
        custom-class-name="sidebar-brand-full"
        :icon="logoNegative"
        :height="35"
      />
      <CIcon
        custom-class-name="sidebar-brand-narrow"
        :icon="sygnet"
        :height="35"
      />
    </CSidebarBrand>
    <ul class="sidebar-nav">
      <li class="nav-item">
        <Link
          href="/admin/dashboard"
          class="nav-link"
          :class="{ active: $page.props.dashboardActive }"
        >
          <i class="nav-icon fa fa-home" aria-hidden="true"></i>
          ホーム
        </Link>
        <Link
          href="/admin/user"
          class="nav-link"
          :class="{ active: $page.props.userActive }"
        >
          <i class="nav-icon fa fa-users" aria-hidden="true"></i>
          ユーザー管理
        </Link>
        <Link
          href="/admin/product"
          class="nav-link"
          :class="{ active: $page.props.productActive }"
        >
          <i class="nav-icon fa fa-users" aria-hidden="true"></i>
         Product
        </Link>
      </li>
    </ul>
    <CSidebarToggler
      class="d-none d-lg-flex"
      @click="$store.commit('toggleUnfoldable')"
    />
  </CSidebar>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3'
import { computed } from 'vue'
import { useStore } from 'vuex'
// import { AppSidebarNav } from './AppSidebarNav'
import { logoNegative } from '@/assets/brand/logo-negative'
import { sygnet } from '@/assets/brand/sygnet'
export default {
  name: 'AppSidebar',
  components: {
    Link,
  },
  setup() {
    const store = useStore()
    return {
      logoNegative,
      sygnet,
      sidebarUnfoldable: computed(() => store.state.sidebarUnfoldable),
      sidebarVisible: computed(() => store.state.sidebarVisible),
    }
  },
}
</script>
