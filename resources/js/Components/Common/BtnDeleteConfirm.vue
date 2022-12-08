<template>
  <div>
    <CDropdownItem @click="showAlert" style="cursor: pointer">
      <i class="fa fa-trash" aria-hidden="true"></i>
      削除
    </CDropdownItem>
  </div>
</template>

<script>
import axios from 'axios'
import $ from 'jquery'
import { Notyf } from 'notyf'
import 'notyf/notyf.min.css'
import { useForm } from '@inertiajs/inertia-vue3'

export default {
  data() {
    return {
      model: useForm({}),
    }
  },
  components: {},
  props: ['deleteAction', 'listUrl', 'messageConfirm'],
  mounted() {},
  methods: {
    showAlert() {
      let that = this
      this.$swal({
        title: that.messageConfirm,
        icon: 'warning',
        confirmButtonText: '削除する',
        cancelButtonText: '閉じる',
        showCancelButton: true,
      }).then((result) => {
        if (result.value) {
          $('.loading').removeClass('hidden')
          axios
            .delete(that.deleteAction, {
              _token: Laravel.csrfToken,
            })
            .then(function (response) {
              $('.loading').addClass('hidden')
              that
                .$swal({
                  title: response.data.message,
                  icon: 'success',
                  confirmButtonText: '閉じる',
                })
                .then(function () {
                  // location.reload();
                  that.model.get(location.href)
                })
            })
            .catch(() => {
              $('.loading').addClass('hidden')
              const notyf = new Notyf({
                duration: 6000,
                position: {
                  x: 'center',
                  y: 'bottom',
                },
                types: [
                  {
                    type: 'error',
                    duration: 8000,
                    dismissible: true,
                  },
                ],
              })
              return notyf.error('エラーが発生しました。')
            })
        }
      })
    },
  },
}
</script>
