<template>
  <div class="customer-create main-content">
    <form action="" @submit.prevent="submitSubAdminData">
      <div class="page-header">
        <h3 class="page-title">{{ isEdit ? $t('subAdmins.edit_subAdmin') : $t('subAdmins.new_subAdmin') }}</h3>
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <router-link slot="item-title" to="/admin/dashboard">{{ $t('general.home') }}</router-link>
          </li>
          <li class="breadcrumb-item">
            <router-link slot="item-title" to="/admin/subAdmins">{{ $tc('subAdmins.subAdmin', 2) }}</router-link>
          </li>
          <li class="breadcrumb-item">
            {{ isEdit ? $t('subAdmins.edit_subAdmin') : $t('subAdmins.new_subAdmin') }}
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
            {{ isEdit ? $t('subAdmins.update_subAdmin') : $t('subAdmins.save_subAdmin') }}
          </base-button>
        </div>
      </div>

      <div class="customer-card card">
        <div class="card-body">
          <div class="row">
            <div class="section-title col-sm-2">{{ $t('subAdmins.basic_info') }}</div>
            <div class="col-sm-5">
              <div class="form-group">
                <label class="form-label">{{ $t('subAdmins.display_name') }}</label><span class="text-danger"> *</span>
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
              <div class="form-group">
                <label class="form-label">{{ $t('customers.email') }}</label><span class="text-danger"> *</span>
                <base-input
                  :invalid="$v.formData.email.$error"
                  v-model.trim="formData.email"
                  type="text"
                  name="email"
                  tab-index="3"
                  @input="$v.formData.email.$touch()"
                />
                <div v-if="$v.formData.email.$error">
                  <span v-if="!$v.formData.email.email" class="text-danger">
                    {{ $tc('validation.email_incorrect') }}
                  </span>
                </div>
              </div>

              <div class="form-group">
                <label class="form-label">{{ $tc('subAdmins.password') }}</label><span class="text-danger"> *</span>
                <base-input
                  v-model="formData.password"
                  :invalid="$v.formData.password.$error"
                  type="password"
                  @input="$v.formData.password.$touch()"
                />
                <div v-if="$v.formData.password.$error">
                  <span v-if="!$v.formData.password.minLength" class="text-danger"> {{ $tc('validation.password_min_length', $v.formData.password.$params.minLength.min, {count: $v.formData.password.$params.minLength.min}) }} </span>
                </div>
              </div>

              <div class="form-group">
                <label class="form-label">{{ $tc('settings.account_settings.confirm_password') }}</label><span class="text-danger"> *</span>
                <base-input
                  v-model="formData.confirm_password"
                  :invalid="$v.formData.confirm_password.$error"
                  type="password"
                  @input="$v.formData.confirm_password.$touch()"
                />
                <div v-if="$v.formData.confirm_password.$error">
                  <span v-if="!$v.formData.confirm_password.sameAsPassword" class="text-danger">{{ $tc('validation.password_incorrect') }}</span>
                </div>
              </div>

            </div>
            <div class="col-sm-5">
              <div class="form-group">
                <label class="form-label">{{ $t('settings.company_info.company_name') }}</label><span class="text-danger"> *</span>
                <base-input
                  v-model.trim="formData.company_name"
                  :label="$t('subAdmins.company_name')"
                  type="text"
                  tab-index="2"
                />
              </div>
              <div class="form-group">
                <label class="form-label">{{ $t('subAdmins.phone') }}</label>
                <base-input
                  v-model.trim="formData.phone"
                  type="text"
                  name="phone"
                  tab-index="4"
                />
              </div>
              <div class="form-group">
                <label class="form-label">{{ $t('subAdmins.website') }}</label>
                <base-input
                  v-model="formData.website"
                  :invalid="$v.formData.website.$error"
                  type="url"
                  tab-index="6"
                  @input="$v.formData.website.$touch()"
                />
                <div v-if="$v.formData.website.$error">
                  <span v-if="!$v.formData.website.url" class="text-danger">
                    {{ $tc('validation.invalid_url') }}
                  </span>
                </div>
              </div>
              <div class="form-group">
                <label class="form-label">{{ $t('subAdmins.primary_currency') }}</label>
                <base-select
                  v-model="currency"
                  :options="currencies"
                  :custom-label="currencyNameWithCode"
                  :allow-empty="false"
                  :searchable="true"
                  :show-labels="false"
                  :tabindex="5"
                  :placeholder="$t('subAdmins.select_currency')"
                  label="name"
                  track-by="id"
                />
              </div>
            </div>
          </div>
          <hr> <!-- first row complete  -->
          <div class="row">
            <div class="section-title col-sm-2">{{ $t('subAdmins.billing_address') }}</div>
            <div class="col-sm-5">
              <div class="form-group">
                <label class="form-label">{{ $t('subAdmins.name') }}</label>
                <base-input
                  v-model.trim="billing.name"
                  type="text"
                  name="address_name"
                  tab-index="7"
                />
              </div>
              <div class="form-group">
                <label class="form-label">{{ $t('subAdmins.state') }}</label>
                <base-input
                  v-model="billing.state"
                  name="billing.state"
                  type="text"
                  tab-index="9"
                />
              </div>
              <div class="form-group">
                <label class="form-label">{{ $t('subAdmins.address') }}</label>
                <base-text-area
                  v-model.trim="billing.address_street_1"
                  :tabindex="11"
                  :placeholder="$t('general.street_1')"
                  type="text"
                  name="billing_street1"
                  rows="2"
                  @input="$v.billing.address_street_1.$touch()"
                />
                <div v-if="$v.billing.address_street_1.$error">
                  <span v-if="!$v.billing.address_street_1.maxLength" class="text-danger">
                    {{ $t('validation.address_maxlength') }}
                  </span>
                </div>
                <base-text-area
                  :tabindex="12"
                  v-model.trim="billing.address_street_2"
                  :placeholder="$t('general.street_2')"
                  type="text"
                  name="billing_street2"
                  rows="2"
                  @input="$v.billing.address_street_2.$touch()"
                />
                <div v-if="$v.billing.address_street_2.$error">
                  <span v-if="!$v.billing.address_street_2.maxLength" class="text-danger">
                    {{ $t('validation.address_maxlength') }}
                  </span>
                </div>
              </div>
            </div>
            <div class="col-sm-5">
              <div class="form-group">
                <label class="form-label">{{ $t('subAdmins.country') }}</label>
                <base-select
                  v-model="billing_country"
                  :options="billingCountries"
                  :searchable="true"
                  :show-labels="false"
                  :allow-empty="true"
                  :tabindex="8"
                  :placeholder="$t('general.select_country')"
                  label="name"
                  track-by="id"
                />
              </div>
              <div class="form-group">
                <label class="form-label">{{ $t('subAdmins.city') }}</label>
                <base-input
                  v-model="billing.city"
                  name="billing.city"
                  type="text"
                  tab-index="10"
                />
              </div>
              <div class="form-group">
                <label class="form-label">{{ $t('subAdmins.phone') }}</label>
                <base-input
                  v-model.trim="billing.phone"
                  type="text"
                  name="phone"
                  tab-index="13"
                />
              </div>
              <div class="form-group">
                <label class="form-label">{{ $t('subAdmins.zip_code') }}</label>
                <base-input
                  v-model.trim="billing.zip"
                  type="text"
                  name="zip"
                  tab-index="14"
                />
              </div>
            </div>
          </div>
          <hr> <!-- second row complete  -->
          <!--<div class="same-address-checkbox-container">
            <div class="p-1">
              <base-button ref="sameAddress" icon="copy" color="theme" class="btn-sm" @click="copyAddress(true)">
                {{ $t('subAdmins.copy_billing_address') }}
              </base-button>
            </div>
          </div>-->

          <!--<div class="row">
            <div class="section-title col-sm-2">
              {{ $t('subAdmins.shipping_address') }}
            </div>
            <div class="col-sm-5">
              <div class="form-group">
                <label class="form-label">{{ $t('subAdmins.name') }}</label>
                <base-input
                  v-model.trim="shipping.name"
                  type="text"
                  name="address_name"
                  tab-index="15"
                />
              </div>
              <div class="form-group">
                <label class="form-label">{{ $t('subAdmins.state') }}</label>
                <base-input
                  v-model="shipping.state"
                  name="shipping.state"
                  type="text"
                  tab-index="17"
                />
              </div>
              <div class="form-group">
                <label class="form-label">{{ $t('subAdmins.address') }}</label>
                <base-text-area
                  v-model.trim="shipping.address_street_1"
                  :tabindex="19"
                  :placeholder="$t('general.street_1')"
                  type="text"
                  name="street_1"
                  rows="2"
                  @input="$v.shipping.address_street_1.$touch()"
                />
                <div v-if="$v.shipping.address_street_1.$error">
                  <span v-if="!$v.shipping.address_street_1.maxLength" class="text-danger">{{ $t('validation.address_maxlength') }}</span>
                </div>
                <base-text-area
                  v-model.trim="shipping.address_street_2"
                  :tabindex="20"
                  :placeholder="$t('general.street_2')"
                  type="text"
                  name="street_2"
                  rows="2"
                  @input="$v.shipping.address_street_2.$touch()"
                />
                <div v-if="$v.shipping.address_street_2.$error">
                  <span v-if="!$v.shipping.address_street_2.maxLength" class="text-danger">{{ $t('validation.address_maxlength') }}</span>
                </div>
              </div>
            </div>
            <div class="col-sm-5">
              <div class="form-group">
                <label class="form-label">{{ $t('subAdmins.country') }}</label>
                <base-select
                  v-model="shipping_country"
                  :options="shippingCountries"
                  :searchable="true"
                  :show-labels="false"
                  :tabindex="16"
                  :allow-empty="true"
                  :placeholder="$t('general.select_country')"
                  label="name"
                  track-by="id"
                />
              </div>
              <div class="form-group">
                <label class="form-label">{{ $t('subAdmins.city') }}</label>
                <base-input
                  v-model="shipping.city"
                  name="shipping.city"
                  type="text"
                  tab-index="18"
                />
              </div>
              <div class="form-group">
                <label class="form-label">{{ $t('subAdmins.phone') }}</label>
                <base-input
                  v-model.trim="shipping.phone"
                  type="text"
                  name="phone"
                  tab-index="21"
                />
              </div>
              <div class="form-group">
                <label class="form-label">{{ $t('subAdmins.zip_code') }}</label>
                <base-input
                  v-model.trim="shipping.zip"
                  type="text"
                  name="zip"
                  tab-index="22"
                />
              </div>
              <div class="form-group collapse-button-container">
                <base-button
                  :tabindex="23"
                  icon="save"
                  color="theme"
                  class="collapse-button"
                  type="submit"
                >
                  {{ $t('subAdmins.save_subAdmin') }}
                </base-button>
              </div>
            </div>
          </div>-->
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
  const { required, requiredIf, sameAs, minLength, email, url, maxLength } = require('vuelidate/lib/validators')

  export default {
    components: { MultiSelect },
    mixins: [validationMixin],
    data () {
      return {
        isCopyFromBilling: false,
        isLoading: false,
        formData: {
          name: null,
          company_name: null,
          email: null,
          phone: null,
          currency_id: null,
          website: null,
          addresses: [],
          password: null,
          confirm_password: null
        },

        currency: null,
        billing: {
          name: null,
          country_id: null,
          state: null,
          city: null,
          phone: null,
          zip: null,
          address_street_1: null,
          address_street_2: null,
          type: 'billing'
        },
        shipping: {
          name: null,
          country_id: null,
          state: null,
          city: null,
          phone: null,
          zip: null,
          address_street_1: null,
          address_street_2: null,
          type: 'shipping'
        },
        currencyList: [],

        billing_country: null,
        shipping_country: null,

        billingCountries: [],
        shippingCountries: []
      }
    },
    validations: {
      formData: {
        name: {
          required,
          minLength: minLength(3)
        },
        company_name: {
          required,
          minLength: minLength(3)
        },
        email: {
          required,
          email
        },
        website: {
          url
        },
        password: {
          required,
          minLength: minLength(5)
        },
        confirm_password: {
          required: requiredIf('isRequired'),
          sameAsPassword: sameAs('password')
        }
      },
      billing: {
        address_street_1: {
          maxLength: maxLength(255)
        },
        address_street_2: {
          maxLength: maxLength(255)
        }
      },
      shipping: {
        address_street_1: {
          maxLength: maxLength(255)
        },
        address_street_2: {
          maxLength: maxLength(255)
        }
      }
    },
    computed: {
      ...mapGetters('currency', [
        'defaultCurrency',
        'currencies'
      ]),
      isRequired () {
        if (this.formData.password === null || this.formData.password === undefined || this.formData.password === '') {
          return false
        }
        return true
      },
      isEdit () {
        if (this.$route.name === 'subadmins.edit') {
          return true
        }
        return false
      },
      hasBillingAdd () {
        let billing = this.billing
        if (
          billing.name ||
          billing.country_id ||
          billing.state ||
          billing.city ||
          billing.phone ||
          billing.zip ||
          billing.address_street_1 ||
          billing.address_street_2) {
          return true
        }
        return false
      },
      hasShippingAdd () {
        let shipping = this.shipping
        if (
          shipping.name ||
          shipping.country_id ||
          shipping.state ||
          shipping.city ||
          shipping.phone ||
          shipping.zip ||
          shipping.address_street_1 ||
          shipping.address_street_2) {
          return true
        }
        return false
      }
    },
    watch: {
      billing_country (newCountry) {
        if (newCountry) {
          this.billing.country_id = newCountry.id
          this.isDisabledBillingState = false
        } else {
          this.billing.country_id = null
        }
      },
      shipping_country (newCountry) {
        if (newCountry) {
          this.shipping.country_id = newCountry.id
          return true
        } else {
          this.shipping.country_id = null
        }
      }
    },
    mounted () {
      this.fetchCountry()
      if (this.isEdit) {
        this.loadSubAdmin()
      } else {
        this.currency = this.defaultCurrency
      }


    },
    methods: {
      currencyNameWithCode ({name, code}) {
        return `${code} - ${name}`
      },
      ...mapActions('subAdmin', [
        'addSubAdmin',
        'fetchSubAdmin',
        'updateSubAdmin'
      ]),
      async loadSubAdmin () {
        let { data: { subAdmin, currencies, currency } } = await this.fetchSubAdmin(this.$route.params.id)

        this.formData.id = subAdmin.id
        this.formData.name = subAdmin.name
        this.formData.company_name = subAdmin.company_name
        this.formData.email = subAdmin.email
        this.formData.phone = subAdmin.phone
        this.formData.currency_id = subAdmin.currency_id
        this.formData.website = subAdmin.website


        if (subAdmin.billing_address) {
          this.billing = subAdmin.billing_address

          if (subAdmin.billing_address.country_id) {
            this.billing_country = this.billingCountries.find((c) => c.id === subAdmin.billing_address.country_id)
          }
        }

        if (subAdmin.shipping_address) {
          this.shipping = subAdmin.shipping_address

          if (subAdmin.shipping_address.country_id) {
            this.shipping_country = this.shippingCountries.find((c) => c.id === subAdmin.shipping_address.country_id)
          }
        }

        this.currencyList = currencies
        this.formData.currency_id = subAdmin.currency_id
        this.currency = currency

      },
      async fetchCountry () {
        let res = await window.axios.get('/api/countries')
        if (res) {
          this.billingCountries = res.data.countries
          this.shippingCountries = res.data.countries
        }
      },
      copyAddress (val) {
        if (val === true) {
          this.isCopyFromBilling = true
          this.shipping = {...this.billing, type: 'shipping'}
          this.shipping_country = this.billing_country
          this.shipping_state = this.billing_state
          this.shipping_city = this.billing_city
        } else {
          this.shipping = {...AddressStub, type: 'shipping'}
          this.shipping_country = null
          this.shipping_state = null
          this.shipping_city = null
        }
      },
      async submitSubAdminData () {
        this.$v.formData.$touch()

        if (this.$v.$invalid) {
          return true
        }

        if (this.hasBillingAdd && this.hasShippingAdd) {
          this.formData.addresses = [{...this.billing}, {...this.shipping}]
        } else {
          if (this.hasBillingAdd) {
            this.formData.addresses = [{...this.billing}]
          }
          if (this.hasShippingAdd) {
            this.formData.addresses = [{...this.shipping}]
          }
        }

        if (this.isEdit) {
          if (this.currency) {
            this.formData.currency_id = this.currency.id
          }
          this.isLoading = true
          try {
            let response = await this.updateSubAdmin(this.formData)
            if (response.data.success) {
              window.toastr['success'](this.$t('subAdmins.updated_message'))
              this.$router.push('/admin/subAdmins')
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
          if (this.currency) {
            this.isLoading = true
            this.formData.currency_id = this.currency.id
          }
          try {
            let response = await this.addSubAdmin(this.formData)
            if (response.data.success) {
              window.toastr['success'](this.$t('subAdmins.created_message'))
              this.$router.push('/admin/subAdmins')
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


