<template>
  <div class="customer-create main-content">
    <form action="" @submit.prevent="submitCustomerData">
      <div class="page-header">
        <h3 class="page-title">{{ isEdit ? $t('customers.edit_customer') : $t('customers.new_customer') }}</h3>
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <router-link slot="item-title" to="/admin/dashboard">{{ $t('general.home') }}</router-link>
          </li>
          <li class="breadcrumb-item">
            <router-link slot="item-title" to="/admin/customers">{{ $tc('customers.customer', 2) }}</router-link>
          </li>
          <li class="breadcrumb-item">
            {{ isEdit ? $t('customers.edit_customer') : $t('customers.new_customer') }}
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
            {{ isEdit ? $t('customers.update_customer') : $t('customers.save_customer') }}
          </base-button>
        </div>
      </div>
      <!-- /////// -->
      <div class="customer-card card">
        <div class="card-body">
          <div class="row">
            <div class="text-center section-title col-sm-12">{{ $t('customers.customer_type') }}</div>
            <div class="col-sm-12">
              <div class="form-group">
                <div>
                  <button @click="showForm" class="btn btn-primary btn-outline" type="button" aria-pressed="false" id="private_btn">Private customer</button>
                  <button @click="hideForm" class="btn btn-primary btn-outline" type="button" aria-pressed="false" id="business_btn">Business customer</button>
                </div>
                <!-- <label class="form-label">{{ $t('customers.display_name') }}</label><span class="text-danger"> *</span> -->
                <!-- <base-input
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
                </div> -->
              </div>
            </div>
          </div>
          <hr> <!-- second row complete  -->
        </div>

        <div v-if="isBusiness" class="card-body">
          <div class="row">
            <!-- <div class="text-center section-title col-sm-12">{{ $t('customers.customer_type') }}</div> -->
            <div class="col-sm-12">
              <div class="form-group">
                <div>
                  <ul>
                    <li>
                      <input v-model="removelines" :value="1" type="radio" id="f-option" name="selector">
                      <label for="f-option">Company With Personal Liabaility</label>
                      
                      <div class="check"></div>
                    </li>
                    
                    <li>
                      <input v-model="removelines"  :value="2" type="radio" id="s-option" name="selector">
                      <label for="s-option">Capital Company</label>
                      
                      <div class="check"></div>
                    </li>
                    
                    <li>
                      <input v-model="removelines"  :value="3" type="radio" id="t-option" name="selector">
                      <label for="t-option">Public Company / Authority</label>
                      
                      <div class="check"></div>
                    </li>
                  </ul>
                </div>
                <!-- <label class="form-label">{{ $t('customers.display_name') }}</label><span class="text-danger"> *</span> -->
                <!-- <base-input
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
                </div> -->
              </div>
            </div>
          </div>
          <hr> <!-- second row complete  -->
        </div>
      </div>
      <!-- /////////////// -->
      <div v-if="isPrivate" class="customer-card card">
        <div class="card-body">
          <div class="row">
            <div class="section-title col-sm-2">{{ $t('customers.basic_info') }}</div>
            <div class="col-sm-5">
              <div class="form-group">
                <label class="form-label">{{ $t('customers.display_name') }}</label><span class="text-danger"> *</span>
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
                <label class="form-label">{{ $t('customers.email') }}</label>
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
                <label class="form-label">{{ $t('customers.primary_currency') }}</label>
                <base-select
                  v-model="currency"
                  :options="currencies"
                  :custom-label="currencyNameWithCode"
                  :allow-empty="false"
                  :searchable="true"
                  :show-labels="false"
                  :tabindex="5"
                  :placeholder="$t('customers.select_currency')"
                  label="name"
                  track-by="id"
                />
              </div>
            </div>
            <div class="col-sm-5">
              <div class="form-group">
                <label class="form-label">{{ $t('customers.primary_contact_name') }}</label>
                <base-input
                  v-model.trim="formData.contact_name"
                  :label="$t('customers.contact_name')"
                  type="text"
                  tab-index="2"
                />
              </div>
              <div class="form-group">
                <label class="form-label">{{ $t('customers.phone') }}</label>
                <base-input
                  v-model.trim="formData.phone"
                  type="text"
                  name="phone"
                  tab-index="4"
                />
              </div>
              <div class="form-group">
                <label class="form-label">{{ $t('customers.website') }}</label>
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
            </div>
          </div>
          <hr> <!-- first row complete  -->
          <div class="row">
            <div class="section-title col-sm-2">{{ $t('customers.billing_address') }}</div>
            <div class="col-sm-5">
              <div class="form-group">
                <label class="form-label">{{ $t('customers.name') }}</label>
                <base-input
                  v-model.trim="billing.name"
                  type="text"
                  name="address_name"
                  tab-index="7"
                />
              </div>
              <div class="form-group">
                <label class="form-label">{{ $t('customers.state') }}</label>
                <base-input
                  v-model="billing.state"
                  name="billing.state"
                  type="text"
                  tab-index="9"
                />
              </div>
              <div class="form-group">
                <label class="form-label">{{ $t('customers.address') }}</label>
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
                <label class="form-label">{{ $t('customers.country') }}</label>
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
                <label class="form-label">{{ $t('customers.city') }}</label>
                <base-input
                  v-model="billing.city"
                  name="billing.city"
                  type="text"
                  tab-index="10"
                />
              </div>
              <div class="form-group">
                <label class="form-label">{{ $t('customers.phone') }}</label>
                <base-input
                  v-model.trim="billing.phone"
                  type="text"
                  name="phone"
                  tab-index="13"
                />
              </div>
              <div class="form-group">
                <label class="form-label">{{ $t('customers.zip_code') }}</label>
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
          <div class="same-address-checkbox-container">
            <div class="p-1">
              <base-button ref="sameAddress" icon="copy" color="theme" class="btn-sm" @click="copyAddress(true)">
                {{ $t('customers.copy_billing_address') }}
              </base-button>
            </div>
          </div>

          <div class="row">
            <div class="section-title col-sm-2">
              {{ $t('customers.shipping_address') }}
            </div>
            <div class="col-sm-5">
              <div class="form-group">
                <label class="form-label">{{ $t('customers.name') }}</label>
                <base-input
                  v-model.trim="shipping.name"
                  type="text"
                  name="address_name"
                  tab-index="15"
                />
              </div>
              <div class="form-group">
                <label class="form-label">{{ $t('customers.state') }}</label>
                <base-input
                  v-model="shipping.state"
                  name="shipping.state"
                  type="text"
                  tab-index="17"
                />
              </div>
              <div class="form-group">
                <label class="form-label">{{ $t('customers.address') }}</label>
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
                <label class="form-label">{{ $t('customers.country') }}</label>
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
                <label class="form-label">{{ $t('customers.city') }}</label>
                <base-input
                  v-model="shipping.city"
                  name="shipping.city"
                  type="text"
                  tab-index="18"
                />
              </div>
              <div class="form-group">
                <label class="form-label">{{ $t('customers.phone') }}</label>
                <base-input
                  v-model.trim="shipping.phone"
                  type="text"
                  name="phone"
                  tab-index="21"
                />
              </div>
              <div class="form-group">
                <label class="form-label">{{ $t('customers.zip_code') }}</label>
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
                  {{ $t('customers.save_customer') }}
                </base-button>
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
      isPrivate:false,
      isBusiness:false,
      removelines: 'b',
      isCopyFromBilling: false,
      isLoading: false,
      formData: {
        name: null,
        contact_name: null,
        email: null,
        phone: null,
        currency_id: null,
        website: null,
        addresses: []
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
      email: {
        email
      },
      website: {
        url
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
    isEdit () {
      if (this.$route.name === 'customers.edit') {
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
      this.loadCustomer()
    } else {
      this.currency = this.defaultCurrency
    }
  },
  methods: {
    showForm(){
      this.isPrivate = true;
      this.isBusiness = false;
    },
    hideForm(){
      this.isBusiness = true;
      this.isPrivate = true;
    },
    currencyNameWithCode ({name, code}) {
      return `${code} - ${name}`
    },
    ...mapActions('customer', [
      'addCustomer',
      'fetchCustomer',
      'updateCustomer'
    ]),
    async loadCustomer () {
      let { data: { customer, currencies, currency } } = await this.fetchCustomer(this.$route.params.id)

      this.formData.id = customer.id
      this.formData.name = customer.name
      this.formData.contact_name = customer.contact_name
      this.formData.email = customer.email
      this.formData.phone = customer.phone
      this.formData.currency_id = customer.currency_id
      this.formData.website = customer.website

      if (customer.billing_address) {
        this.billing = customer.billing_address

        if (customer.billing_address.country_id) {
          this.billing_country = this.billingCountries.find((c) => c.id === customer.billing_address.country_id)
        }
      }

      if (customer.shipping_address) {
        this.shipping = customer.shipping_address

        if (customer.shipping_address.country_id) {
          this.shipping_country = this.shippingCountries.find((c) => c.id === customer.shipping_address.country_id)
        }
      }

      this.currencyList = currencies
      this.formData.currency_id = customer.currency_id
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
    async submitCustomerData () {
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
          let response = await this.updateCustomer(this.formData)
          if (response.data.success) {
            window.toastr['success'](this.$t('customers.updated_message'))
            this.$router.push('/admin/customers')
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
          let response = await this.addCustomer(this.formData)
          if (response.data.success) {
            window.toastr['success'](this.$t('customers.created_message'))
            this.$router.push('/admin/customers')
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
<style scoped>
@import url('https://fonts.googleapis.com/css?family=Lato');

body, html{
  height: 100%;
  background: #222222;
	font-family: 'Lato', sans-serif;
}

.container{
  display: block;
  position: relative;
  margin: 40px auto;
  height: auto;
  width: 500px;
  padding: 20px;
}

h2 {
	color: #0f0e0e;
}

.container ul{
  list-style: none;
  margin: 0;
  padding: 0;
	overflow: auto;
}

ul li{
  color: #AAAAAA;
  display: block;
  position: relative;
  float: left;
  width: 100%;
  height: 100px;
	border-bottom: 1px solid #333;
}

ul li input[type=radio]{
  position: absolute;
  visibility: hidden;
}

ul li label{
  display: block;
  position: relative;
  font-weight: 300;
  font-size: 1.35em;
  padding: 25px 25px 25px 80px;
  margin: 10px auto;
  height: 30px;
  z-index: 9;
  cursor: pointer;
  -webkit-transition: all 0.25s linear;
}

ul li:hover label{
	color: #4f55a1;
}

ul li .check{
  display: block;
  position: absolute;
  border: 5px solid #AAAAAA;
  border-radius: 100%;
  height: 25px;
  width: 25px;
  top: 30px;
  left: 20px;
	z-index: 5;
	transition: border .25s linear;
	-webkit-transition: border .25s linear;
}

ul li:hover .check {
  border: 5px solid #1e0153;
}

ul li .check::before {
  display: block;
  position: absolute;
	content: '';
  border-radius: 100%;
  height: 15px;
  width: 15px;
  top: 5px;
	left: 5px;
  margin: auto;
	transition: background 0.25s linear;
	-webkit-transition: background 0.25s linear;
}

input[type=radio]:checked ~ .check {
  border: 5px solid #1e0153;
}

input[type=radio]:checked ~ label{
  color: #1e0153;
}

.signature {
	margin: 10px auto;
	padding: 10px 0;
	width: 100%;
}

.signature p{
	text-align: center;
	font-family: Helvetica, Arial, Sans-Serif;
	font-size: 0.85em;
	color: #AAAAAA;
}

.signature .much-heart{
	display: inline-block;
	position: relative;
	margin: 0 4px;
	height: 10px;
	width: 10px;
	background: #AC1D3F;
	border-radius: 4px;
	-ms-transform: rotate(45deg);
    -webkit-transform: rotate(45deg);
    transform: rotate(45deg);
}

.signature .much-heart::before, 
.signature .much-heart::after {
	  display: block;
  content: '';
  position: absolute;
  margin: auto;
  height: 10px;
  width: 10px;
  border-radius: 5px;
  background: #AC1D3F;
  top: -4px;
}

.signature .much-heart::after {
	bottom: 0;
	top: auto;
	left: -4px;
}

.signature a {
	color: #AAAAAA;
	text-decoration: none;
	font-weight: bold;
}
</style>
