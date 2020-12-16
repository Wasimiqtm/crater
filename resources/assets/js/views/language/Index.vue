<template>
  
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
        // language:[],
    //   showFilters: false,
    // //   language:[],
    //   filtersApplied: false,
    //   isRequestOngoing: true,
    //   filters: {
    //     name: ''
    //   }
    }
  },
//   computed: {
//     showEmptyScreen () {
//       return !this.totalLanguage && !this.isRequestOngoing && !this.filtersApplied
//     },
//     filterIcon () {
//       return (this.showFilters) ? 'times' : 'filter'
//     },
    // ...mapGetters('language', [
    //   'language',
    //   'selectedLanguage',
    //   'totalLanguage',
    //   'selectAllField'
    // ]),
//     selectField: {
//       get: function () {
//         return this.selectedLanguage
//       },
//       set: function (val) {
//         this.selectLanguage(val)
//       }
//     },
//     selectAllFieldStatus: {
//       get: function () {
//         return this.selectAllField
//       },
//       set: function (val) {
//         this.setSelectAllState(val)
//       }
//     }
//   },
//   watch: {
//     filters: {
//       handler: 'setFilters',
//       deep: true
//     }
//   },
//   destroyed () {
//     if (this.selectAllField) {
//       this.selectAllLanguage()
//     }
//   },


  methods: {
    ...mapActions('language', [
      'fetchLanguage',
    //   'selectAllLanguage',
    //   'selectLanguage',
    //   'deleteLanguage',
    //   'deleteMultipleLangauge',
    //   'setSelectAllState'
    ]),
    // refreshTable () {
    //   this.$refs.table.refresh()
    // },
    async fetchData () {
        
      this.isRequestOngoing = true
      let response = await this.fetchLanguage()
      this.isRequestOngoing = false
      
      this.language = response.data
    //   let listOfObjects = Object.keys(response.data).map((key) => {
    //   return response.data[key]
    //     })
        // console.log(response.data);

        var result = Object.keys(response.data).map(function(key) {
            return [String(key), response.data[key]];
            });
        console.log(result);

            for (let index = 0; index < result.length; index++) {

                for (let index1 = 0; index1 < result[index].length; index1++) {
                    // const element = array[index];
                    console.log(result[index][index1]);
                    
                }
                // console.log(result[index]);
                
            }

            // console.log(result);
    //   return {
    //     data: this.language,
    //     // pagination: {
    //     //   totalPages: response.data.language.last_page,
    //     //   currentPage: page
    //     // }
    //   }
    },
    // setFilters () {
    //   this.filtersApplied = true
    //   this.refreshTable()
    // },
    // clearFilter () {
    //   this.filters = {
    //     name: ''
    //   }

    //   this.$nextTick(() => {
    //     this.filtersApplied = false
    //   })
    // },
    // toggleFilter () {
    //   if (this.showFilters && this.filtersApplied) {
    //     this.clearFilter()
    //     this.refreshTable()
    //   }

    //   this.showFilters = !this.showFilters
    // },
    // async removeRole (id) {
    //   swal({
    //     title: this.$t('general.are_you_sure'),
    //     text: this.$tc('language.confirm_delete'),
    //     icon: '/assets/icon/trash-solid.svg',
    //     buttons: true,
    //     dangerMode: true
    //   }).then(async (willDelete) => {
    //     if (willDelete) {
    //       let res = await this.deleteLanguage(id)
    //       if (res.data.success) {
    //         window.toastr['success'](this.$tc('language.deleted_message'))
    //         this.refreshTable()
    //         return true
    //       } else if (request.data.error) {
    //         window.toastr['error'](res.data.message)
    //       }
    //     }
    //   })
    // },
    // async removeMultiplelanguage () {
    //   swal({
    //     title: this.$t('general.are_you_sure'),
    //     text: this.$tc('language.confirm_delete', 2),
    //     icon: '/assets/icon/trash-solid.svg',
    //     buttons: true,
    //     dangerMode: true
    //   }).then(async (willDelete) => {
    //     if (willDelete) {
    //       let request = await this.deleteMultipleLangauge()
    //       if (request.data.success) {
    //         window.toastr['success'](this.$tc('language.deleted_message', 2))
    //         this.refreshTable()
    //       } else if (request.data.error) {
    //         window.toastr['error'](request.data.message)
    //       }
    //     }
    //   })
    // }
  },

  created(){
      this.fetchData()
  }
}
</script>
