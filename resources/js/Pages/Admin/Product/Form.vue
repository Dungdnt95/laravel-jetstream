<script setup>
import { Head, Link } from '@inertiajs/inertia-vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { useForm } from '@inertiajs/inertia-vue3'
import $ from 'jquery'
import axios from 'axios'
import {
  Form as VeeForm,
  Field,
  ErrorMessage,
  defineRule,
  configure,
} from 'vee-validate'
import { localize } from '@vee-validate/i18n'
import * as rules from '@vee-validate/rules'
Object.keys(rules).forEach((rule) => {
  if (rule != 'default') {
    defineRule(rule, rules[rule])
  }
})
const onInvalidSubmit = ({ errors }) => {
  let firstInputError = Object.entries(errors)[0][0]
  let ele = $('[name="' + firstInputError + '"]')
  if (
    $('[name="' + firstInputError + '"]').hasClass('hidden') ||
    $('[name="' + firstInputError + '"]').attr('type') == 'hidden'
  ) {
    ele = $('[name="' + firstInputError + '"]').closest('div')
  }
  ele.focus()
  $('html, body').animate(
    {
      scrollTop: ele.offset().top - 150 + 'px',
    },
    500,
  )
}
let messError = {
  en: {
    fields: {
      name: {
        required: 'ユーザー名を入力してください。',
      },
      amount: {
        required: 'ユーザーのメールを入力してください。',
      },
    },
  },
}
configure({
  generateMessage: localize(messError),
})
</script>
<template>
  <Head :title="data.title" />
  <AdminLayout>
    <template #content>
      <CRow>
        <CCol :sm="12">
          <CCard>
            <VeeForm
              as="div"
              v-slot="{ handleSubmit }"
              @invalid-submit="onInvalidSubmit"
            >
              <form
                method="POST"
                @submit="handleSubmit($event, onSubmit)"
                ref="formData"
              >
                <CCardHeader>
                  <CFormLabel>{{data.title}}</CFormLabel>
                </CCardHeader>
                <CCardBody>
                  <CRow class="mb-2">
                    <CFormLabel class="col-sm-3 lbl-input" require
                      >ユーザー名:</CFormLabel
                    >
                    <div class="col-sm-6">
                      <Field
                        name="name"
                        v-model="model.name"
                        rules="required"
                        class="form-control"
                      />
                      <ErrorMessage class="error" name="name" />
                    </div>
                  </CRow>
                  <CRow class="mb-2">
                    <CFormLabel class="col-sm-3 lbl-input" require
                      >ユーザーのメール:</CFormLabel
                    >
                    <div class="col-sm-6">
                      <Field
                        name="amount"
                        v-model="model.amount"
                        data-vv-validate-on="change"
                        :validateOnBlur="false"
                        :validateOnChange="false"
                        :validateOnInput="false"
                        :validateOnModelUpdate="false"
                        rules="required"
                        class="form-control"
                      />
                      <ErrorMessage class="error" name="amount" />
                    </div>
                  </CRow>
                </CCardBody>
                <CCardFooter>
                  <div class="col-md-12 text-center btn-box">
                    <CButton type="submit" color="info" class="w-100px mr-5px">
                      登録
                    </CButton>
                    <Link :href="data.listUrl" class="btn btn-secondary w-100px">
                      キャンセル
                    </Link>
                  </div>
                </CCardFooter>
              </form>
            </VeeForm>
          </CCard>
        </CCol>
      </CRow>
    </template>
  </AdminLayout>
</template>
<script>
export default {
  created() {
  },
  data() {
    return {
      csrfToken: Laravel.csrfToken,
      model: useForm(this.data.isEdit ? this.data.product : {
        name: '',
        amount: '',
      }),
    }
  },
  mounted() {
  },
  props: {
    data: {
      type: Object,
    },
  },
  components: {
    VeeForm,
    Field,
    ErrorMessage,
  },
  methods: {
    onSubmit() {
      $('.loading').removeClass('hidden');
      if (this.data.isEdit) {
        this.model.patch(route('admin.product.update', this.data.product.id))
      } else {
        this.model.post(route('admin.product.store'))
      }
    },
  },
}
</script>
