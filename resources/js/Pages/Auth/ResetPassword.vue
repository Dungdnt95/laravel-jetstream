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
      password: {
        password_rule:
          "パスワードは半角英数字で、大文字、小文字、数字で入力してください",
        required: "パスワードを入力してください。",
        max: "パスワードは15文字以内で入力してください。",
        min: "パスワードは8文字以上で入力してください。",
      },
      password_confirmation: {
        required: "パスワード確認 を入力してください。",
        confirmed: "パスワード確認が入力されたものと異なります。",
      },
    },
  },
};
configure({
  generateMessage: localize(messError),
});
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
        :action="data.urlUpdate"

      >
        <!-- :action="route('init-password.update', 1)" -->
        <input type="hidden" :value="csrfToken" name="_token" />
        <input type="hidden" value="PUT" name="_method" />
        <div class="mt-4">
          <InputLabel for="password" value="パスワードの再設定" />
          <Field
            id="password"
            name="password"
            type="password"
            autocomplete="off"
            v-model="model.password"
            rules="required|max:15|min:8|password_rule"
            class="mt-1 block w-full form-control"
            ref="password"
          />
          <ErrorMessage class="error" name="password" />
        </div>

        <div class="mt-4">
          <InputLabel for="password_confirmation" value="パスワード（確認用）" />
          <Field
            id="password_confirmation"
            name="password_confirmation"
            type="password"
            autocomplete="off"
            v-model="model.password_confirmation"
            rules="required|confirmed:@password"
            class="mt-1 block w-full form-control"
            ref="password"
          />
          <ErrorMessage class="error" name="password_confirmation" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <Link
            :href="route('login.index')"
            class="underline text-sm text-gray-600 hover:text-gray-900"
          >
            ログイン画面へ
          </Link>
          <PrimaryButton class="ml-2"
          >
            設定する
          </PrimaryButton>
        </div>
      </form>
    </VeeForm>
  </AuthenticationCard>
</template>
<script>
export default {
  data() {
    return {
      model: {},
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
