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
                :listUrl="data.listUrl"
                :createUrl="data.createUrl"
              ></form-search>
              <template v-if="data.users.length">
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
                    <CTableRow v-for="(user, index) in data.users" :key="index">
                      <CTableDataCell>{{ user.id }}</CTableDataCell>
                      <CTableDataCell>{{ user.name }}</CTableDataCell>
                      <CTableDataCell>{{ user.email }}</CTableDataCell>
                      <CTableDataCell>
                        <CDropdown variant="btn-group">
                          <CDropdownToggle color="primary"
                            >操作選択</CDropdownToggle
                          >
                          <CDropdownMenu>
                            <Link class="dropdown-item" :href="user.edit_url"
                              ><i class="fa fa-eye"></i
                              >確認・編集</Link
                            >
                            <CDropdownDivider />
                            <btn-delete-confirm
                              :message-confirm="'この管理者アカウントを削除しますか？'"
                              :delete-action="user.destroy_url"
                            >
                            </btn-delete-confirm>
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
  methods: {},
}
</script>
