<script setup>
import { Head, Link } from '@inertiajs/inertia-vue3'
import AuthenticationCard from '@/Components/AuthenticationCard.vue'
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue'
import Checkbox from '@/Components/Checkbox.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import {
  Form as VeeForm,
  Field,
  ErrorMessage,
  defineRule,
  configure,
} from 'vee-validate'
import { localize } from '@vee-validate/i18n'
import * as rules from '@vee-validate/rules'
import $ from 'jquery'
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
      email: {
        required: 'メールアドレスを入力してください。',
        max: 'メールアドレスは255文字を超えてはなりません。',
        email: 'ログインID形式が正しくありません。',
      },
      password: {
        required: 'パスワードを入力してください。',
        max: 'パスワードは８文字～１６文字入力してください。',
        min: 'パスワードは８文字～１６文字入力してください。',
        password_rule:
          'パスワードは半角英数字で、大文字、小文字、数字で入力してください。',
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

  <AuthenticationCard>
    <template #logo>
      <AuthenticationCardLogo />
    </template>
    <VeeForm
      as="div"
      v-slot="{ handleSubmit }"
      @invalid-submit="onInvalidSubmit"
    >
      <form
        method="POST"
        @submit="handleSubmit($event, onSubmit)"
        ref="formData"
        id="formData"
      >
        <input type="hidden" :value="csrfToken" name="_token" />
        <input
          type="hidden"
          :value="data.request.url_redirect"
          name="url_redirect"
        />
        <div>
          <InputLabel for="email" value="メールアドレス" />
          <Field
            v-model="model.email"
            id="email"
            name="email"
            type="text"
            rules="required|email"
            class="mt-1 block w-full form-control"
          />
          <ErrorMessage class="error" name="email" />
        </div>

        <div class="mt-4">
          <InputLabel for="password" value="パスワード" />
          <Field
            id="password"
            name="password"
            type="password"
            autocomplete="off"
            v-model="model.password"
            rules="required|max:15|min:8|password_rule"
            class="mt-1 block w-full form-control"
          />
          <ErrorMessage class="error" name="password" />
        </div>

        <div class="block mt-4 text-center">
          <label class="flex items-center">
            <Checkbox v-model:checked="model.remember" name="remember_me" />
            <span class="ml-2 text-sm text-gray-600"
              >次回から自動的にログイン</span
            >
          </label>
          <label class="error" v-if="data.message">{{ data.message }}</label>
        </div>

        <div class="flex items-center justify-end mt-4">
          <Link
            :href="data.urlForgot"
            class="underline text-sm text-gray-600 hover:text-gray-900"
          >
            パスワードを忘れた方へ
          </Link>
          <PrimaryButton class="ml-4"> ログイン </PrimaryButton>
        </div>
      </form>
    </VeeForm>
  </AuthenticationCard>
</template>
<script>
export default {
  data() {
    return {
      model: this.data.request,
      csrfToken: Laravel.csrfToken,
    }
  },
  mounted() {},
  props: {
    data: {
      type: Object,
    },
    sessionAlert: {
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
      $('.loading').removeClass('hidden')
      this.$refs.formData.submit()
    },
  },
}
</script>
