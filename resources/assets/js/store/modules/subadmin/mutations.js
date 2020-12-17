import * as types from './mutation-types'

export default {
  [types.BOOTSTRAP_SUBADMINS] (state, subAdmins) {
    state.subAdmins = subAdmins
  },

  [types.SET_TOTAL_SUBADMINS] (state, totalSubAdmins) {
    state.totalSubAdmins = totalSubAdmins
  },

  [types.ADD_SUBADMIN] (state, data) {
    state.subAdmins.push(data.subAdmin)
  },

  [types.UPDATE_SUBADMIN] (state, data) {
    let pos = state.subAdmins.findIndex(subAdmin => subAdmin.id === data.subAdmin.id)

    state.subAdmins[pos] = data.subAdmin
  },

  [types.DELETE_SUBADMIN] (state, id) {
    let index = state.subAdmins.findIndex(subAdmin => subAdmin.id === id)
    state.subAdmins.splice(index, 1)
  },

  [types.DELETE_MULTIPLE_SUBADMINS] (state, selectedSubAdmins) {
    selectedSubAdmins.forEach((subAdmin) => {
      let index = state.subAdmins.findIndex(_cust => _cust.id === subAdmin.id)
      state.subAdmins.splice(index, 1)
    })

    state.selectedSubAdmins = []
  },

  [types.SET_SELECTED_SUBADMINS] (state, data) {
    state.selectedSubAdmins = data
  },

  [types.RESET_SELECTED_SUBADMIN] (state, data) {
    state.selectedSubAdmin = null
  },

  [types.SET_SELECT_ALL_STATE] (state, data) {
    state.selectAllField = data
  }

}
