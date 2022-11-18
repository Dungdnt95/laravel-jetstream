<script setup>
import { Head, Link, useForm } from '@inertiajs/inertia-vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue'
import Checkbox from '@/Components/Checkbox.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import Notyf from '@/Components/Common/Notyf.vue'
import {
  Form as VeeForm,
  Field,
  ErrorMessage,
  defineRule,
  configure
} from 'vee-validate'
import { localize } from '@vee-validate/i18n'
import * as rules from '@vee-validate/rules'
import $ from 'jquery'
Object.keys(rules).forEach((rule) => {
  if (rule != 'default') {
    defineRule(rule, rules[rule])
  }
})
const onInvalidSubmit = ({ values, errors, results }) => {
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
      scrollTop: ele.offset().top - 150 + 'px'
    },
    500
  )
}
let messError = {
  en: {
    fields: {
      email: {
        required: 'メールアドレスを入力してください。',
        max: 'メールアドレスは255文字を超えてはなりません。',
        email: 'ログインID形式が正しくありません。'
      },
      password: {
        required: 'パスワードを入力してください。',
        max: 'パスワードは８文字～１６文字入力してください。',
        min: 'パスワードは８文字～１６文字入力してください。',
        password_rule:
          'パスワードは半角英数字で、大文字、小文字、数字で入力してください。'
      }
    }
  }
}
configure({
  generateMessage: localize(messError)
})
</script>
<template>
  <Head :title="data.title" />

  <AdminLayout>
    <template #content> 11212 </template>
  </AdminLayout>
</template>
<script>
export default {
  data() {
    return {
      model: this.data.request,
      csrfToken: Laravel.csrfToken
    }
  },
  mounted() {},
  props: {
    data: {
      type: Object
    }
  },
  components: {
    VeeForm,
    Field,
    ErrorMessage
  },
  methods: {
    onSubmit() {
      $('.loading').removeClass('hidden')
      this.$refs.formData.submit()
    }
  }
}
</script>
