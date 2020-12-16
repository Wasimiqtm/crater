<template>
  <div class="customer-create main-content">
    <div class="page-header">
      <h3 class="page-title">{{ $t('language.title') }}</h3>
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <router-link
            slot="item-title"
            to="dashboard">
            {{ $t('general.home') }}
          </router-link>
        </li>
        <li class="breadcrumb-item">
          <router-link
            slot="item-title"
            to="#">
            {{ $tc('language.role',2) }}
          </router-link>
        </li>
      </ol>
      <div class="page-actions row">
        <div class="col-xs-2 mr-4">
          <base-button
            v-show="totalRoles || filtersApplied"
            :outline="true"
            :icon="filterIcon"
            size="large"
            color="theme"
            right-icon
            @click="toggleFilter"
          >
            {{ $t('general.filter') }}
          </base-button>
        </div>
        <router-link slot="item-title" class="col-xs-2" to="customers/create">
          <base-button
            size="large"
            icon="plus"
            color="theme">
            {{ $t('customers.new_customer') }}
          </base-button>
        </router-link>
      </div>
    </div>

    <transition name="fade">
      <div v-show="showFilters" class="filter-section">
        <div class="row">
          <div class="col-sm-4">
            <label class="form-label">{{ $t('language.display_name') }}</label>
            <base-input
              v-model="filters.name"
              type="text"
              name="name"
              autocomplete="off"
            />
          </div>
          <label class="clear-filter" @click="clearFilter">{{ $t('general.clear_all') }}</label>
        </div>
      </div>
    </transition>

    <div v-cloak v-show="showEmptyScreen" class="col-xs-1 no-data-info" align="center">
      <astronaut-icon class="mt-5 mb-4"/>
      <div class="row" align="center">
        <label class="col title">{{ $t('customers.no_customers') }}</label>
      </div>
      <div class="row">
        <label class="description col mt-1" align="center">{{ $t('customers.list_of_customers') }}</label>
      </div>
      <div class="btn-container">
        <base-button
          :outline="true"
          color="theme"
          class="mt-3"
          size="large"
          @click="$router.push('customers/create')"
        >
          {{ $t('customers.add_new_customer') }}
        </base-button>
      </div>
    </div>

    <div v-show="!showEmptyScreen" class="table-container">
      <div class="table-actions mt-5">
        <p class="table-stats">{{ $t('general.showing') }}: <b>{{ roles.length }}</b> {{ $t('general.of') }} <b>{{ totalRoles }}</b></p>

        <transition name="fade">
          <v-dropdown v-if="selectedRoles.length" :show-arrow="false">
            <span slot="activator" href="#" class="table-actions-button dropdown-toggle">
              {{ $t('general.actions') }}
            </span>
            <v-dropdown-item>
              <div class="dropdown-item" @click="removeMultipleRoles">
                <font-awesome-icon :icon="['fas', 'trash']" class="dropdown-item-icon" />
                {{ $t('general.delete') }}
              </div>
            </v-dropdown-item>
          </v-dropdown>
        </transition>
      </div>

      <div class="custom-control custom-checkbox">
        <input
          id="select-all"
          v-model="selectAllFieldStatus"
          type="checkbox"
          class="custom-control-input"
          @change="selectAllRoles"
        >
        <label for="select-all" class="custom-control-label selectall">
          <span class="select-all-label">{{ $t('general.select_all') }} </span>
        </label>
      </div>

      <table-component
        ref="table"
        :show-filter="false"
        :data="fetchData"
        table-class="table"
      >
        <table-column
          :sortable="false"
          :filterable="false"
          cell-class="no-click"
        >
          <template slot-scope="row">
            <div class="custom-control custom-checkbox">
              <input
                :id="row.id"
                v-model="selectField"
                :value="row.id"
                type="checkbox"
                class="custom-control-input"
              >
              <label :for="row.id" class="custom-control-label" />
            </div>
          </template>
        </table-column>
        <table-column
          :label="$t('language.name')"
          show="name"
        />

        <table-column
          :label="$t('language.added_on')"
          sort-as="created_at"
          show="formattedCreatedAt"
        />
        <table-column
          :sortable="false"
          :filterable="false"
          cell-class="action-dropdown"
        >
          <template slot-scope="row">
            <span> {{ $t('language.action') }} </span>
            <v-dropdown>
              <a slot="activator" href="#">
                <dot-icon />
              </a>
              <v-dropdown-item>

                <router-link :to="{path: `roles/${row.id}/edit`}" class="dropdown-item">
                  <font-awesome-icon :icon="['fas', 'pencil-alt']" class="dropdown-item-icon"/>
                  {{ $t('general.edit') }}
                </router-link>

              </v-dropdown-item>
              <v-dropdown-item>
                <div class="dropdown-item" @click="removeRole(row.id)">
                  <font-awesome-icon :icon="['fas', 'trash']" class="dropdown-item-icon" />
                  {{ $t('general.delete') }}
                </div>
              </v-dropdown-item>
            </v-dropdown>
          </template>
        </table-column>
      </table-component>
    </div>
  </div>
</template>
<script>
import { mapActions, mapGetters } from 'vuex'
import { SweetModal, SweetModalTab } from 'sweet-modal-vue'
import DotIcon from '../../components/icon/DotIcon'
import AstronautIcon from '../../components/icon/AstronautIcon'
import BaseButton from '../../../js/components/base/BaseButton'
import { request } from 'http'

export default {
  components: {
    DotIcon,
    AstronautIcon,
    SweetModal,
    SweetModalTab,
    BaseButton
  },
  data () {
    return {
      showFilters: false,
      filtersApplied: false,
      isRequestOngoing: true,
      filters: {
        name: ''
      }
    }
  },
  computed: {
    showEmptyScreen () {
      return !this.totalRoles && !this.isRequestOngoing && !this.filtersApplied
    },
    filterIcon () {
      return (this.showFilters) ? 'times' : 'filter'
    },
    ...mapGetters('role', [
      'roles',
      'selectedRoles',
      'totalRoles',
      'selectAllField'
    ]),
    selectField: {
      get: function () {
        return this.selectedRoles
      },
      set: function (val) {
        this.selectRole(val)
      }
    },
    selectAllFieldStatus: {
      get: function () {
        return this.selectAllField
      },
      set: function (val) {
        this.setSelectAllState(val)
      }
    }
  },
  watch: {
    filters: {
      handler: 'setFilters',
      deep: true
    }
  },
  destroyed () {
    if (this.selectAllField) {
      this.selectAllRoles()
    }
  },
  methods: {
    ...mapActions('role', [
      'fetchRoles',
      'selectAllRoles',
      'selectRole',
      'deleteRole',
      'deleteMultipleRoles',
      'setSelectAllState'
    ]),
    refreshTable () {
      this.$refs.table.refresh()
    },
    async fetchData ({ page, filter, sort }) {
      let data = {
        name: this.filters.name,
        orderByField: sort.fieldName || 'created_at',
        orderBy: sort.order || 'desc',
        page
      }

      this.isRequestOngoing = true
      let response = await this.fetchRoles(data)
      this.isRequestOngoing = false

      return {
        data: response.data.roles.data,
        pagination: {
          totalPages: response.data.roles.last_page,
          currentPage: page
        }
      }
    },
    setFilters () {
      this.filtersApplied = true
      this.refreshTable()
    },
    clearFilter () {
      this.filters = {
        name: ''
      }

      this.$nextTick(() => {
        this.filtersApplied = false
      })
    },
    toggleFilter () {
      if (this.showFilters && this.filtersApplied) {
        this.clearFilter()
        this.refreshTable()
      }

      this.showFilters = !this.showFilters
    },
    async removeRole (id) {
      swal({
        title: this.$t('general.are_you_sure'),
        text: this.$tc('language.confirm_delete'),
        icon: '/assets/icon/trash-solid.svg',
        buttons: true,
        dangerMode: true
      }).then(async (willDelete) => {
        if (willDelete) {
          let res = await this.deleteRole(id)
          if (res.data.success) {
            window.toastr['success'](this.$tc('language.deleted_message'))
            this.refreshTable()
            return true
          } else if (request.data.error) {
            window.toastr['error'](res.data.message)
          }
        }
      })
    },
    async removeMultipleRoles () {
      swal({
        title: this.$t('general.are_you_sure'),
        text: this.$tc('language.confirm_delete', 2),
        icon: '/assets/icon/trash-solid.svg',
        buttons: true,
        dangerMode: true
      }).then(async (willDelete) => {
        if (willDelete) {
          let request = await this.deleteMultipleRoles()
          if (request.data.success) {
            window.toastr['success'](this.$tc('language.deleted_message', 2))
            this.refreshTable()
          } else if (request.data.error) {
            window.toastr['error'](request.data.message)
          }
        }
      })
    }
  }
}
</script>
