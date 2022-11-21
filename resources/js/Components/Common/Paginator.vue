<script setup>
import { Link } from '@inertiajs/inertia-vue3'
</script>
<template>
  <nav class="musashi-pagination">
    <span class="total-item-pagination"
      >{{ paginator.firstItem }} - {{ paginator.end }} / 全
      {{ paginator.total }}件</span
    >
    <ul class="pagination">
      <template v-if="!paginator.onFirstPage">
        <li class="page-item">
          <Link
            class="page-link"
            :href="paginator.url[0]"
            rel="prev"
            aria-label="@lang('pagination.previous')"
            >{{ '<<' }}</Link
          >
        </li>
        <li class="page-item">
          <Link
            class="page-link"
            :href="paginator.previousPageUrl"
            rel="prev"
            aria-label="@lang('pagination.previous')"
            >{{ '<' }}</Link
          >
        </li>
        </template>
        <template v-for="(url, index) in paginator.url" :key="index">
          <template v-if="paginator.currentPage == 1">
            <template v-if="index + 1 <= paginator.currentPage + 4">
              <li
                class="page-item active"
                v-if="index + 1 == paginator.currentPage"
                aria-current="page"
              >
                <span class="page-link">{{ index + 1 }}</span>
              </li>
              <li class="page-item" v-else>
                <Link class="page-link" :href="paginator.url[index]">{{
                  index + 1
                }}</Link>
              </li>
            </template>
          </template>
          <template v-else-if="paginator.currentPage == 2">
            <template v-if="index + 1 <= paginator.currentPage + 3">
              <li
                class="page-item active"
                v-if="index + 1 == paginator.currentPage"
                aria-current="page"
              >
                <span class="page-link">{{ index + 1 }}</span>
              </li>
              <li class="page-item" v-else>
                <Link class="page-link" :href="paginator.url[index]">{{
                  index + 1
                }}</Link>
              </li>
            </template>
          </template>
          <template v-else-if="paginator.currentPage == paginator.lastPage">
            <template v-if="index + 1 >= paginator.currentPage - 4">
              <li
                class="page-item active"
                v-if="index + 1 == paginator.currentPage"
                aria-current="page"
              >
                <span class="page-link">{{ index + 1 }}</span>
              </li>
              <li class="page-item" v-else>
                <Link class="page-link" :href="paginator.url[index]">{{
                  index + 1
                }}</Link>
              </li>
            </template>
          </template>
          <template v-else-if="paginator.currentPage == paginator.lastPage - 1">
            <template v-if="index + 1 >= paginator.currentPage - 3">
              <li
                class="page-item active"
                v-if="index + 1 == paginator.currentPage"
                aria-current="page"
              >
                <span class="page-link">{{ index + 1 }}</span>
              </li>
              <li class="page-item" v-else>
                <Link class="page-link" :href="paginator.url[index]">{{
                  index + 1
                }}</Link>
              </li>
            </template>
          </template>
          <template v-else>
            <template
              v-if="
                index + 1 >= paginator.currentPage - 2 &&
                index + 1 <= paginator.currentPage + 2
              "
            >
              <li
                class="page-item active"
                v-if="index + 1 == paginator.currentPage"
                aria-current="page"
              >
                <span class="page-link">{{ index + 1 }}</span>
              </li>
              <li class="page-item" v-else>
                <Link class="page-link" :href="paginator.url[index]">{{
                  index + 1
                }}</Link>
              </li>
            </template>
          </template>
        </template>
      <template v-if="paginator.hasMorePages">
        <li class="page-item">
          <Link class="page-link" :href="paginator.nextPageUrl" rel="next"
            >></Link
          >
        </li>
        <li class="page-item">
          <Link
            class="page-link"
            :href="paginator.url[paginator.lastPage-1]"
            rel="next"
            >>></Link
          >
        </li>
      </template>
    </ul>
  </nav>
</template>

<script>
export default {
  components: {},
  props: ['paginator'],
  mounted() {},
  methods: {},
}
</script>
