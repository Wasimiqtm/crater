<template>
  <div class="customer-create main-content">
    <form action="" @submit.prevent="submitPermissionData">
      <div class="page-header">
        <h3 class="page-title">{{ isEdit ? $t('permissions.edit_permission') : $t('permissions.new_permission') }}</h3>
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <router-link slot="item-title" to="/admin/dashboard">{{ $t('general.home') }}</router-link>
          </li>
          <li class="breadcrumb-item">
            <router-link slot="item-title" to="/admin/permissions">{{ $tc('permissions.permission', 2) }}</router-link>
          </li>
          <li class="breadcrumb-item">
            {{ isEdit ? $t('permissions.edit_permission') : $t('permissions.new_permission') }}
          </li>
        </ol>
        <div class="page-actions header-button-container">
          <base-button
            :loading="isLoading"
            :disabled="isLoading"
            :tabindex="23"
            icon="save"
            color="theme"
            type="submit"
          >
            {{ isEdit ? $t('permissions.update_permission') : $t('permissions.save_permission') }}
          </base-button>
        </div>
      </div>
      <div class="customer-card card">
        <div class="card-body">
          <div class="row">
            <div class="section-title col-sm-2">{{ $t('permissions.basic_info') }}</div>
            <div class="col-sm-5">
              <div class="form-group">
                <label class="form-label">{{ $t('permissions.display_name') }}</label><span class="text-danger"> *</span>
                <base-input
                  :invalid="$v.formData.name.$error"
                  v-model="formData.name"
                  focus
                  type="text"
                  name="name"
                  tab-index="1"
                  @input="$v.formData.name.$touch()"
                />
                <div v-if="$v.formData.name.$error">
                  <span v-if="!$v.formData.name.required" class="text-danger">
                    {{ $tc('validation.required') }}
                  </span>
                  <span v-if="!$v.formData.name.minLength" class="text-danger">
                    {{ $tc('validation.name_min_length', $v.formData.name.$params.minLength.min, { count: $v.formData.name.$params.minLength.min }) }}
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import MultiSelect from 'vue-multiselect'
import { validationMixin } from 'vuelidate'
import AddressStub from '../../stub/address'
const { required, minLength, email, url, maxLength } = require('vuelidate/lib/validators')

export default {
  components: { MultiSelect },
  mixins: [validationMixin],
  data () {
    return {
      isLoading: false,
      formData: {
        name: null
      }
    }
  },
  validations: {
    formData: {
      name: {
        required,
        minLength: minLength(3)
      }
    }
  },
  computed: {
    isEdit () {
      if (this.$route.name === 'permissions.edit') {
        return true
      }
      return false
    }
  },
  watch: {

  },
  mounted () {
    if (this.isEdit) {
      this.loadPermission()
    }
  },
  methods: {
    ...mapActions('permission', [
      'addPermission',
      'fetchPermission',
      'updatePermission'
    ]),
    async loadPermission () {
      let { data: { permission } } = await this.fetchPermission(this.$route.params.id)

      this.formData.id = permission.id
      this.formData.name = permission.name
    },
    async submitPermissionData () {
      this.$v.formData.$touch()

      if (this.$v.$invalid) {
        return true
      }

      if (this.isEdit) {

        this.isLoading = true
        try {
          let response = await this.updatePermission(this.formData)
          if (response.data.success) {
            window.toastr['success'](this.$t('permissions.updated_message'))
            this.$router.push('/admin/permissions')
            this.isLoading = false
            return true
          } else {
            this.isLoading = false
            if (response.data.error) {
              window.toastr['error'](this.$t('validation.email_already_taken'))
            }
          }
        } catch (err) {
          if (err.response.data.errors.email) {
            this.isLoading = false
            window.toastr['error'](this.$t('validation.email_already_taken'))
          }
        }
      } else {
        this.isLoading = true

        try {
          let response = await this.addPermission(this.formData)
          if (response.data.success) {
            window.toastr['success'](this.$t('permissions.created_message'))
            this.$router.push('/admin/permissions')
            this.isLoading = false
            return true
          }
        } catch (err) {
          if (err.response.data.errors.email) {
            this.isLoading = false
            window.toastr['error'](this.$t('validation.email_already_taken'))
          }
        }
      }
    }
  }
}
</script>
