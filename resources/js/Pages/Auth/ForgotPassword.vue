<script setup>
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import AuthenticationCard from "@/Components/AuthenticationCard.vue";
import AuthenticationCardLogo from "@/Components/AuthenticationCardLogo.vue";
import Checkbox from "@/Components/Checkbox.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import {
  Form as VeeForm,
  Field,
  ErrorMessage,
  defineRule,
  configure,
} from "vee-validate";
import { localize } from "@vee-validate/i18n";
import * as rules from "@vee-validate/rules";
import $ from "jquery";
Object.keys(rules).forEach((rule) => {
  if (rule != "default") {
    defineRule(rule, rules[rule]);
  }
});
const onInvalidSubmit = ({ values, errors, results }) => {
  let firstInputError = Object.entries(errors)[0][0];
  let ele = $('[name="' + firstInputError + '"]');
  if (
    $('[name="' + firstInputError + '"]').hasClass("hidden") ||
    $('[name="' + firstInputError + '"]').attr("type") == "hidden"
  ) {
    ele = $('[name="' + firstInputError + '"]').closest("div");
  }
  ele.focus();
  $("html, body").animate(
    {
      scrollTop: ele.offset().top - 150 + "px",
    },
    500
  );
};
let messError = {
  en: {
    fields: {
      email: {
        required: "メールアドレスを入力してください。",
        max: "メールアドレスは255文字を超えてはなりません。",
        email: "ログインID形式が正しくありません。",
      },
    },
  },
};
configure({
  generateMessage: localize(messError),
});
</script>

<template>
  <Head title="パスワード再発行申請" />

  <AuthenticationCard>
    <template #logo>
      <AuthenticationCardLogo />
    </template>

    <div class="mb-4 text-sm text-gray-600">
      パスワード再設定のURLを記載したメールを送信します
    </div>

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

        <div class="flex items-center justify-end mt-4">
            <Link
            :href="route('login.index')"
            class="underline text-sm text-gray-600 hover:text-gray-900"
          >
            ログイン画面へ
          </Link>
          <PrimaryButton class="ml-4"> 送信する </PrimaryButton>
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
    };
  },
  mounted() {},
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
      $(".loading").removeClass("hidden");
      this.$refs.formData.submit();
    },
  },
};
</script>
