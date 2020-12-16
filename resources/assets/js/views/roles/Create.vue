<template>
  <div class="customer-create main-content">
    <form action="" @submit.prevent="submitRoleData">
      <div class="page-header">
        <h3 class="page-title">{{ isEdit ? $t('roles.edit_role') : $t('roles.new_role') }}</h3>
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <router-link slot="item-title" to="/admin/dashboard">{{ $t('general.home') }}</router-link>
          </li>
          <li class="breadcrumb-item">
            <router-link slot="item-title" to="/admin/roles">{{ $tc('roles.role', 2) }}</router-link>
          </li>
          <li class="breadcrumb-item">
            {{ isEdit ? $t('roles.edit_role') : $t('roles.new_role') }}
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
            {{ isEdit ? $t('roles.update_role') : $t('roles.save_role') }}
          </base-button>
        </div>
      </div>
      <div class="customer-card card">
        <div class="card-body">
          <div class="row">
            <div class="section-title col-sm-2">{{ $t('roles.basic_info') }}</div>
            <div class="col-sm-5">
              <div class="form-group">
                <label class="form-label">{{ $t('roles.display_name') }}</label><span class="text-danger"> *</span>
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
      if (this.$route.name === 'roles.edit') {
        return true
      }
      return false
    }
  },
  watch: {

  },
  mounted () {
    if (this.isEdit) {
      this.loadRole()
    }
  },
  methods: {
    ...mapActions('role', [
      'addRole',
      'fetchRole',
      'updateRole'
    ]),
    async loadRole () {
      let { data: { role } } = await this.fetchRole(this.$route.params.id)

      this.formData.id = role.id
      this.formData.name = role.name
    },
    async submitRoleData () {
      this.$v.formData.$touch()

      if (this.$v.$invalid) {
        return true
      }

      if (this.isEdit) {

        this.isLoading = true
        try {
          let response = await this.updateRole(this.formData)
          if (response.data.success) {
            window.toastr['success'](this.$t('roles.updated_message'))
            this.$router.push('/admin/roles')
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
          let response = await this.addRole(this.formData)
          if (response.data.success) {
            window.toastr['success'](this.$t('roles.created_message'))
            this.$router.push('/admin/roles')
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
