import * as types from './mutation-types'

export default {
  [types.BOOTSTRAP_PERMISSIONS] (state, permissions) {
    state.permissions = permissions
  },

  [types.SET_TOTAL_PERMISSIONS] (state, totalPermissions) {
    state.totalPermissions = totalPermissions
  },

  [types.ADD_PERMISSION] (state, data) {
    state.permissions.push(data.permission)
  },

  [types.UPDATE_PERMISSION] (state, data) {
    let pos = state.permissions.findIndex(permission => permission.id === data.permission.id)

    state.permissions[pos] = data.permission
  },

  [types.DELETE_PERMISSION] (state, id) {
    let index = state.permissions.findIndex(permission => permission.id === id)
    state.permissions.splice(index, 1)
  },

  [types.DELETE_MULTIPLE_PERMISSIONS] (state, selectedPermissions) {
    selectedPermissions.forEach((permission) => {
      let index = state.permissions.findIndex(_cust => _cust.id === permission.id)
      state.permissions.splice(index, 1)
    })

    state.selectedPermissions = []
  },

  [types.SET_SELECTED_PERMISSIONS] (state, data) {
    state.selectedPermissions = data
  },

  [types.RESET_SELECTED_PERMISSION] (state, data) {
    state.selectedPermission = null
  },

  [types.SET_SELECT_ALL_STATE] (state, data) {
    state.selectAllField = data
  }

}
