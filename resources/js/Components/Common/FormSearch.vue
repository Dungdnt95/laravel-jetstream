<script setup>
import { Link } from '@inertiajs/inertia-vue3'
</script>
<template>
  <CRow>
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="searchFrom pull-right">
        <form :action="listUrl" class="form-inline">
          <div>
            <input
              name="free_word"
              placeholder="検索"
              :value="request.free_word"
              autocomplete="off"
              type="control"
              id="free_word"
              class="form-control"
            />
            <CButton type="submit" color="info" class="w-100px">
              <i class="fa fa-search"></i> &nbsp; 検索
            </CButton>
          </div>
          <Link :href="createUrl" class="btn btn-info btn-action-create"
            ><i class="fa fa-plus"></i>新規登録
          </Link>
        </form>
      </div>
    </div>
  </CRow>
</template>
<script>
export default {
  mounted() {},
  created: function () {},
  props: ['listUrl', 'createUrl', 'request'],
  methods: {},
}
</script>
