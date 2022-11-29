<script setup>
import { Link } from '@inertiajs/inertia-vue3'
import { useForm } from '@inertiajs/inertia-vue3'
import $ from 'jquery'
import { Form as VeeForm } from 'vee-validate'
</script>
<template>
  <CRow>
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="searchFrom pull-right">
        <VeeForm as="div" v-slot="{ handleSubmit }">
          <form
            class="form-inline"
            method="POST"
            @submit="handleSubmit($event, onSubmit)"
            ref="formData"
          >
            <div>
              <input
                name="free_word"
                placeholder="検索"
                v-model="model.free_word"
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
        </VeeForm>
      </div>
    </div>
  </CRow>
</template>
<script>
export default {
  mounted() {},
  created: function () {},
  data() {
    return {
      model: useForm({
        sort: this.request.sort,
        direction: this.request.direction,
        free_word: this.request.free_word,
      }),
    }
  },
  props: ['routeName', 'createUrl', 'request'],
  methods: {
    onSubmit() {
      $('.loading').removeClass('hidden')
      this.model.get(route(this.routeName))
    },
  },
}
</script>
