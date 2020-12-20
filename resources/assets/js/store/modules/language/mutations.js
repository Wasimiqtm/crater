import * as types from './mutation-types'

export default {
  [types.BOOTSTRAP_ROLES] (state, roles) {
    state.roles = roles
  },

  [types.SET_TOTAL_ROLES] (state, totalRoles) {
    state.totalRoles = totalRoles
  },

  [types.ADD_ROLE] (state, data) {
    state.roles.push(data.role)
  },

  [types.UPDATE_ROLE] (state, data) {
    let pos = state.roles.findIndex(role => role.id === data.role.id)

    state.roles[pos] = data.role
  },

  [types.DELETE_ROLE] (state, id) {
    let index = state.roles.findIndex(role => role.id === id)
    state.roles.splice(index, 1)
  },

  [types.DELETE_MULTIPLE_ROLES] (state, selectedRoles) {
    selectedRoles.forEach((role) => {
      let index = state.roles.findIndex(_cust => _cust.id === role.id)
      state.roles.splice(index, 1)
    })

    state.selectedRoles = []
  },

  [types.SET_SELECTED_ROLES] (state, data) {
    state.selectedRoles = data
  },

  [types.RESET_SELECTED_ROLE] (state, data) {
    state.selectedRole = null
  },

  [types.SET_SELECT_ALL_STATE] (state, data) {
    state.selectAllField = data
  }

}
