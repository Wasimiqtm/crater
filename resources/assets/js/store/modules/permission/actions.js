import * as types from './mutation-types'

export const fetchPermissions = ({ commit, dispatch, state }, params) => {
  return new Promise((resolve, reject) => {
    window.axios.get(`/api/permission`, {params}).then((response) => {
      commit(types.BOOTSTRAP_PERMISSIONS, response.data.permissions.data)
      commit(types.SET_TOTAL_PERMISSIONS, response.data.permissions.total)
      resolve(response)
    }).catch((err) => {
      reject(err)
    })
  })
}

export const fetchPermission = ({ commit, dispatch }, id) => {
  return new Promise((resolve, reject) => {
    window.axios.get(`/api/permission/${id}/edit`).then((response) => {
      resolve(response)
    }).catch((err) => {
      reject(err)
    })
  })
}

export const addPermission = ({ commit, dispatch, state }, data) => {
  return new Promise((resolve, reject) => {
    window.axios.post('/api/permission', data).then((response) => {
      commit(types.ADD_PERMISSION, response.data)
      resolve(response)
    }).catch((err) => {
      reject(err)
    })
  })
}

export const updatePermission = ({ commit, dispatch, state }, data) => {
  return new Promise((resolve, reject) => {
    window.axios.put(`/api/permission/${data.id}`, data).then((response) => {
      if(response.data.success){
        commit(types.UPDATE_PERMISSION, response.data)
      }
      resolve(response)
    }).catch((err) => {
      reject(err)
    })
  })
}

export const deletePermission = ({ commit, dispatch, state }, id) => {
  return new Promise((resolve, reject) => {
    window.axios.delete(`/api/permission/${id}`).then((response) => {
      commit(types.DELETE_PERMISSION, id)
      resolve(response)
    }).catch((err) => {
      reject(err)
    })
  })
}

export const deleteMultiplePermissions = ({ commit, dispatch, state }, id) => {
  return new Promise((resolve, reject) => {
    window.axios.post(`/api/permission/delete`, {'id': state.selectedPermissions}).then((response) => {
      commit(types.DELETE_MULTIPLE_PERMISSIONS, state.selectedPermissions)
      resolve(response)
    }).catch((err) => {
      reject(err)
    })
  })
}

export const setSelectAllState = ({ commit, dispatch, state }, data) => {
  commit(types.SET_SELECT_ALL_STATE, data)
}

export const selectAllPermissions = ({ commit, dispatch, state }) => {
  if (state.selectedPermissions.length === state.permissions.length) {
    commit(types.SET_SELECTED_PERMISSIONS, [])
    commit(types.SET_SELECT_ALL_STATE, false)
  } else {
    let allPermissionIds = state.permissions.map(cust => cust.id)
    commit(types.SET_SELECTED_PERMISSIONS, allPermissionIds)
    commit(types.SET_SELECT_ALL_STATE, true)
  }
}

export const selectPermission = ({ commit, dispatch, state }, data) => {
  commit(types.SET_SELECTED_PERMISSIONS, data)
  if (state.selectedPermissions.length === state.permissions.length) {
    commit(types.SET_SELECT_ALL_STATE, true)
  } else {
    commit(types.SET_SELECT_ALL_STATE, false)
  }
}

export const resetSelectedPermission = ({ commit, dispatch, state }, data) => {
  commit(types.RESET_SELECTED_PERMISSION)
}
