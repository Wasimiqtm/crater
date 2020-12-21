<template>
  <div class="customer-create main-content">
    <form action="" @submit.prevent="submitRoleData">
      <div class="page-header">
        <h3 class="page-title">{{ isEdit ? $t('language.edit_language') : $t('roles.new_role') }}</h3>
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <router-link slot="item-title" to="/admin/dashboard">{{ $t('general.home') }}</router-link>
          </li>
          <li class="breadcrumb-item">
            <router-link slot="item-title" to="/admin/roles">{{ $tc('language.title', 2) }}</router-link>
          </li>
          <li class="breadcrumb-item">
            {{ isEdit ? $t('language.edit_language') : $t('roles.new_role') }}
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
            {{ isEdit ? $t('language.update_language') : $t('roles.save_role') }}
          </base-button>
        </div>
      </div>
      <div class="customer-card card">
        <div class="card-body">
          <div class="row">
            <div class="section-title col-sm-2">{{ $t('language.title') }}</div>
            <div class="col-sm-5">
              <div class="form-group">
                <label class="form-label">{{ $t('language.english') }}</label><span class="text-danger"> *</span>
                <base-input
                  :invalid="$v.formData.value_en.$error"
                  v-model="formData.value_en"
                  focus
                  type="text"
                  name="value_en"
                  tab-index="1"
                  @input="$v.formData.value_en.$touch()"
                />
                <div v-if="$v.formData.value_en.$error">
                  <span v-if="!$v.formData.value_en.required" class="text-danger">
                    {{ $tc('validation.required') }}
                  </span>
                  <span v-if="!$v.formData.value_en.minLength" class="text-danger">
                    {{ $tc('validation.name_min_length', $v.formData.value_en.$params.minLength.min, { count: $v.formData.value_en.$params.minLength.min }) }}
                  </span>
                </div>

                <label class="form-label">{{ $t('language.denmark') }}</label><span class="text-danger"> *</span>
                <base-input
                  :invalid="$v.formData.value_da.$error"
                  v-model="formData.value_da"
                  focus
                  type="text"
                  name="value_da"
                  tab-index="1"
                  @input="$v.formData.value_da.$touch()"
                />
                <div v-if="$v.formData.value_da.$error">
                  <span v-if="!$v.formData.value_da.required" class="text-danger">
                    {{ $tc('validation.required') }}
                  </span>
                  <span v-if="!$v.formData.value_da.minLength" class="text-danger">
                    {{ $tc('validation.name_min_length', $v.formData.value_da.$params.minLength.min, { count: $v.formData.value_da.$params.minLength.min }) }}
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
        parent: null,
        value_en: null,
        value_da: null
      }
    }
  },
  validations: {
    formData: {
      value_en: {
        required,
        minLength: minLength(3)
      },
      value_da: {
        required,
        minLength: minLength(3)
      }
    }
  },
  computed: {
    isEdit () {
      if (this.$route.name === 'language.edit') {
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
    ...mapActions('language', [
      'addRole',
      'fetchRole',
      'updateRole'
    ]),
    async loadRole () {
      let { data: { role } } = await this.fetchRole(this.$route.params.id)

      this.formData.id = role.id
      this.formData.parent = role.parent
      this.formData.value_en = role.value_en
      this.formData.value_da = role.value_da

      console.log(this.formData)
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
            // window.i18n.locale = 
            window.toastr['success'](this.$t('roles.updated_message'))
            this.$router.push('/admin/language')
            this.isLoading = false
            return true
          } else {
            this.isLoading = false
            if (response.data.error) {
              window.toastr['error'](this.$t('validation.value_en'))
            }
          }
        } catch (err) {
          if (err.response.data.errors.value_en) {
            this.isLoading = false
            window.toastr['error'](this.$t('validation.value_en'))
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
