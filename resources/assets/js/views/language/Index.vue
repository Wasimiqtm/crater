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
            {{ $tc('language.title',2) }}
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
        <!-- <router-link slot="item-title" class="col-xs-2" to="roles/create">
          <base-button
            size="large"
            icon="plus"
            color="theme">
            {{ $t('language.add_new_language') }}
          </base-button>
        </router-link> -->
      </div>
    </div>

    <transition name="fade">
      <div v-show="showFilters" class="filter-section">
        <div class="row">
          <div class="col-sm-4">
            <label class="form-label">{{ $t('language.module') }}</label>
            <base-input
              v-model="filters.parent"
              type="text"
              name="parent"
              autocomplete="off"
            />
          </div>
           <div class="col-sm-4">
            <label class="form-label">{{ $t('language.english') }}</label>
            <base-input
              v-model="filters.value_en"
              type="text"
              name="english"
              autocomplete="off"
            />
           </div>
            <div class="col-sm-4">
            <label class="form-label">{{ $t('language.denmark') }}</label>
            <base-input
              v-model="filters.value_da"
              type="text"
              name="danish"
              autocomplete="off"
            />
          </div>
          <label class="clear-filter" @click="clearFilter">{{ $t('general.clear_all') }}</label>
        </div>
      </div>
    </transition>

    <div v-cloak v-show="showEmptyScreen" class="col-xs-1 no-data-info" align="center">
      <!-- <astronaut-icon class="mt-5 mb-4"/> -->
      <div class="row" align="center">
        <label class="col title">{{ $t('roles.no_roles') }}</label>
      </div>
      <div class="row">
        <label class="description col mt-1" align="center">{{ $t('roles.list_of_roles') }}</label>
      </div>
      <div class="btn-container">
        <base-button
          :outline="true"
          color="theme"
          class="mt-3"
          size="large"
          @click="$router.push('roles/create')"
        >
          {{ $t('roles.add_new_role') }}
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
          :label="$t('language.module')"
          show="parent"
        />

        <table-column
          :label="$t('language.english')"
          show="value_en"
        />
         <table-column
          :label="$t('language.denmark')"
          show="value_da"
        />

        <table-column
          :sortable="false"
          :filterable="false"
          cell-class="action-dropdown"
        >
          <template slot-scope="row">
            <span> {{ $t('roles.action') }} </span>
            <v-dropdown>
              <a slot="activator" href="#">
                <dot-icon />
              </a>
              <!-- <v-dropdown-item>

                <router-link :to="{path: `roles/permissions/${row.id}`}" class="dropdown-item">
                  <font-awesome-icon :icon="['fas', 'space-shuttle']" class="dropdown-item-icon"/>
                  {{ $t('permissions.assign_permission') }}
                </router-link>

              </v-dropdown-item> -->
              <v-dropdown-item>

                <router-link :to="{path: `language/${row.id}/edit`}" class="dropdown-item">
                  <font-awesome-icon :icon="['fas', 'pencil-alt']" class="dropdown-item-icon"/>
                  {{ $t('general.edit') }}
                </router-link>

              </v-dropdown-item>
              <!-- <v-dropdown-item>
                <div class="dropdown-item" @click="removeRole(row.id)">
                  <font-awesome-icon :icon="['fas', 'trash']" class="dropdown-item-icon" />
                  {{ $t('general.delete') }}
                </div>
              </v-dropdown-item> -->
            </v-dropdown>
          </template>
        </table-column>
      </table-component>
    </div>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import DotIcon from '../../components/icon/DotIcon'
import SatelliteIcon from '../../components/icon/SatelliteIcon'
import BaseButton from '../../../js/components/base/BaseButton'

export default {
  components: {
    DotIcon,
    SatelliteIcon,
    BaseButton
  },
  data () {
    return {
      showFilters: false,
      filtersApplied: false,
      isRequestOngoing: true,
      filters: {
        parent: '',
        value_en: '',
        value_da: ''
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
    ...mapGetters('language', [
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

  mounted(){
    // this.add_database()
  },
  methods: {
    ...mapActions('language', [
      'fetchRoles',
      'selectAllRoles',
      'selectRole',
      'deleteRole',
      'deleteMultipleRoles',
      'setSelectAllState',
      'add_to_database'
    ]),
    refreshTable () {
      this.$refs.table.refresh()
    },
    async fetchData ({ page, filter, sort }) {
      let data = {
        parent: this.filters.parent,
        value_en: this.filters.value_en,
        value_da: this.filters.value_da,
        orderByField: sort.fieldName || 'created_at',
        orderBy: sort.order || 'desc',
        page
      }

      this.isRequestOngoing = true
      let response = await this.fetchRoles(data)
      this.isRequestOngoing = false
      console.log(response)
      return {
        data: response.data.roles.data,
        pagination: {
          totalPages: response.data.roles.last_page,
          currentPage: page
        }
      }
    },

    async add_database () {
     
      this.isRequestOngoing = true
      let response = await this.add_to_database()
      this.isRequestOngoing = false
      console.log(response)
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
        text: this.$tc('roles.confirm_delete'),
        icon: '/assets/icon/trash-solid.svg',
        buttons: true,
        dangerMode: true
      }).then(async (willDelete) => {
        if (willDelete) {
          let res = await this.deleteRole(id)
          if (res.data.success) {
            window.toastr['success'](this.$tc('roles.deleted_message'))
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
        text: this.$tc('roles.confirm_delete', 2),
        icon: '/assets/icon/trash-solid.svg',
        buttons: true,
        dangerMode: true
      }).then(async (willDelete) => {
        if (willDelete) {
          let request = await this.deleteMultipleRoles()
          if (request.data.success) {
            window.toastr['success'](this.$tc('roles.deleted_message', 2))
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
