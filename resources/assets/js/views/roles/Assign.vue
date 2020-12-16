<template>
  <div class="customer-create main-content">
    <div class="page-header">
      <h3 class="page-title">{{ $t('permissions.title') }}</h3>
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
            {{ $tc('permissions.assign_permission',2) }} TO {{role | uppercase }}
          </router-link>
        </li>
      </ol>
      <div class="page-actions row">

      </div>
    </div>

    <form @submit.prevent="save_permissions()">
      <div class="col-sm-12">
        <div class="col-sm-3" v-for="permission in permissions">
          <!--<base-switch class="btn-switch"/>-->
          <input type="checkbox"
               :id="permission.id"
               class="form-check-input"
               :value="permission.id"
               v-model="assigned_permissions"
               :checked="permission.assigned"
          />
          <div class="right ml-15">
            <p class="box-title">  {{ permission.name | uppercase }} </p>
          </div>
        </div>

      </div>
      <div class="form-group">
        <button class="btn btn-info">Submit</button>
      </div>
    </form>
  </div>
</template>
<script>

  import {permissions} from "../../store/modules/permission/getters";

  export default {
    data () {
      return {
        role:'null',
        permissions:[],
        isLoading: false,
        assigned_permissions: []
      }
    },
    mounted () {
      this.fetchData()
    },
    filters: {
      uppercase: function(v) {
        return v.toUpperCase();
      }
    },
    computed:{
    },
    methods: {
      async fetchData () {
        let response = await axios.get(`/api/roles/permissions/${this.$route.params.id}`)
        if (response.data) {
          response.data.permissions.map(permission => {
            if(permission.assigned)
            {
              this.assigned_permissions.push(permission.id)
            }
          })
          this.permissions = response.data.permissions;
          this.role = response.data.role.name;
        }
      },
      async save_permissions() {

        let response = await axios.put(`/api/roles/permissions/${this.$route.params.id}`, {'assigned_permissions': this.assigned_permissions})

        if (response.data.success) {
          window.toastr['success'](this.$tc('general.setting_updated'))
          this.fetchData();
        }
      }
    }
  }
</script>
