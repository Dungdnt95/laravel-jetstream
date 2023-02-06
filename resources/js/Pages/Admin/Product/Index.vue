<script setup>
import { Head, Link } from '@inertiajs/inertia-vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
</script>
<template>
  <Head :title="data.title" />
  <AdminLayout>
    <template #content>
      <CRow>
        <CCol :sm="12">
          <CCard>
            <CCardHeader>
              <CFormLabel>{{ data.title }}</CFormLabel>
            </CCardHeader>
            <CCardBody>
              <form-search
                :request="data.request"
                :routeName="'admin.product.index'"
                :createUrl="data.createUrl"
              ></form-search>
              <template v-if="data.products.length">
                <CRow>
                  <div
                    class="col-md-5 col-sm-5 col-xs-12 group-select-page d-flex"
                  >
                    <limit-page-option
                      :limit-page-option="[20, 50, 100]"
                      :new-size-limit="
                        data.request.limit_page ? data.request.limit_page : 20
                      "
                    ></limit-page-option>
                  </div>
                  <div class="col-md-7 col-sm-7 col-xs-12 group-paginate">
                    <paginator :paginator="data.paginator"></paginator>
                  </div>
                </CRow>
                <CTable striped>
                  <CTableHead color="info">
                    <CTableRow>
                      <generate-sort :data="data.sortLinks"></generate-sort>
                      <CTableHeaderCell class="w-100px"></CTableHeaderCell>
                    </CTableRow>
                  </CTableHead>
                  <CTableBody>
                    <CTableRow
                      v-for="(product, index) in data.products"
                      :key="index"
                    >
                      <CTableDataCell>{{ product.id }}</CTableDataCell>
                      <CTableDataCell>{{ product.name }}</CTableDataCell>
                      <CTableDataCell>
                        <CDropdown variant="btn-group">
                          <CDropdownToggle color="info"
                            >操作選択</CDropdownToggle
                          >
                          <CDropdownMenu>
                            <Link class="dropdown-item" :href="product.edit_url"
                              ><i class="fa fa-eye"></i>確認・編集</Link
                            >
                            <CDropdownDivider />
                            <btn-delete-confirm
                              :message-confirm="'この管理者アカウントを削除しますか？'"
                              :delete-action="product.destroy_url"
                            >
                            </btn-delete-confirm>
                            <CDropdownDivider />
                            <div>
                              <CDropdownItem
                                @click="
                                  updateStatus(
                                    product.update_status,
                                    product.status === 0 ? 1 : 0
                                  )
                                "
                                style="cursor: pointer"
                              >
                              <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                {{
                                  product.status === 0 ? 'Active' : 'UnActive'
                                }}
                              </CDropdownItem>
                            </div>
                          </CDropdownMenu>
                        </CDropdown>
                      </CTableDataCell>
                    </CTableRow>
                  </CTableBody>
                </CTable>
                <div class="group-paginate">
                  <paginator :paginator="data.paginator"></paginator>
                </div>
              </template>
              <data-empty v-else></data-empty>
            </CCardBody>
          </CCard>
        </CCol>
      </CRow>
    </template>
  </AdminLayout>
</template>
<script>
import axios from 'axios'
export default {
  data() {
    return {}
  },
  mounted() {},
  props: {
    data: {
      type: Object,
    },
  },
  components: {},
  methods: {
    updateStatus(url, value) {
      let vm = this
      this.$swal({
        title: value === 0 ? 'Unactive product' : 'Active product',
        icon: 'question',
        confirmButtonText: value === 0 ? 'Unactive product' : 'Active product',
        cancelButtonText: '閉じる',
        showCancelButton: true,
      }).then((result) => {
        if (result.value) {
          axios
            .post(url, {
              _token: Laravel.csrfToken,
              status: value,
            })
            .then(function (response) {
              vm.$swal({
                title: response.data.message,
                icon: 'success',
                confirmButtonText: '閉じる',
              }).then(function () {
                window.location.reload()
              })
            })
            .catch(() => {})
        }
      })
    },
  },
}
</script>
