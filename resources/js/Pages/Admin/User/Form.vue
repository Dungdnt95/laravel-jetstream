<script setup>
import { Head, Link } from '@inertiajs/inertia-vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
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
      email: {
        required: 'ユーザーのメールを入力してください。',
        unique_custom: 'このメールアドレスは既に登録されています。',
      },
      password: {
        'required': 'パスワードを入力してください。',
        password_rule:
          'パスワードは半角英数字で、大文字、小文字、数字で入力してください',
        max: 'パスワードは15文字以内で入力してください。',
        min: 'パスワードは8文字以上で入力してください。',
      },
      password_confirmation: {
        required: 'パスワード確認 を入力してください。',
        confirmed: 'パスワード確認が入力されたものと異なります。',
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
                :action="data.urlAction"
                ref="formData"
              >
                <Field type="hidden" :value="csrfToken" name="_token" />
                <Field type="hidden" v-if="data.isEdit" value="PUT" name="_method" />
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
                        name="email"
                        v-model="model.email"
                        data-vv-validate-on="change"
                        :validateOnBlur="false"
                        :validateOnChange="false"
                        :validateOnInput="false"
                        :validateOnModelUpdate="false"
                        rules="required|unique_custom"
                        class="form-control"
                      />
                      <ErrorMessage class="error" name="email" />
                    </div>
                  </CRow>
                  <CRow class="mb-2">
                    <CFormLabel class="col-sm-3 lbl-input" v-bind:require="!data.isEdit"
                      >パスワード:</CFormLabel
                    >
                    <div class="col-sm-6">
                      <Field
                        name="password"
                        type="password"
                        autocomplete="off"
                        v-model="model.password"
                        :rules="data.isEdit ? 'max:15|min:8|password_rule' : 'required|max:15|min:8|password_rule'"
                        class="form-control"
                        ref="password"
                      />
                      <ErrorMessage class="error" name="password" />
                    </div>
                  </CRow>
                  <CRow class="mb-2">
                    <CFormLabel class="col-sm-3 lbl-input" v-bind:require="!data.isEdit"
                      >パスワード確認:</CFormLabel
                    >
                    <div class="col-sm-6">
                      <Field
                        name="password_confirmation"
                        type="password"
                        autocomplete="off"
                        v-model="model.password_confirmation"
                        :rules="data.isEdit ? 'confirmed:@password' : 'required|confirmed:@password'"
                        class="form-control"
                      />
                      <ErrorMessage
                        class="error"
                        name="password_confirmation"
                      />
                    </div>
                  </CRow>
                </CCardBody>
                <CCardFooter>
                  <div class="col-md-12 text-center btn-box">
                    <CButton type="submit" class="btn btn-primary w-100px mr-5px">
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
    defineRule('unique_custom', (value) => {
      return axios
        .post(this.data.checkEmailUrl, {
          _token: Laravel.csrfToken,
          value: value,
          id: this.data.isEdit ? this.data.user.id : '',
        })
        .then(function (response) {
          return response.data.valid
        })
        .catch(() => {})
    })
  },
  data() {
    return {
      csrfToken: Laravel.csrfToken,
      model: this.data.isEdit ? this.data.user : {},
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
      this.$refs.formData.submit()
    },
  },
}
</script>
