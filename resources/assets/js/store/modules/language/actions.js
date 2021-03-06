import * as types from './mutation-types'

export const fetchRoles = ({ commit, dispatch, state }, params) => {
  return new Promise((resolve, reject) => {
    window.axios.get(`/api/language`, {params}).then((response) => {
      commit(types.BOOTSTRAP_ROLES, response.data.roles.data)
      commit(types.SET_TOTAL_ROLES, response.data.roles.total)
      resolve(response)
    }).catch((err) => {
      reject(err)
    })
  })
}

export const fetchRole = ({ commit, dispatch }, id) => {
  return new Promise((resolve, reject) => {
    window.axios.get(`/api/language/${id}/edit`).then((response) => {
      resolve(response)
    }).catch((err) => {
      reject(err)
    })
  })
}

export const addRole = ({ commit, dispatch, state }, data) => {
  return new Promise((resolve, reject) => {
    window.axios.post('/api/roles', data).then((response) => {
      commit(types.ADD_ROLE, response.data)
      resolve(response)
    }).catch((err) => {
      reject(err)
    })
  })
}

export const updateRole = ({ commit, dispatch, state }, data) => {
  return new Promise((resolve, reject) => {
    window.axios.put(`/api/language/${data.id}`, data).then((response) => {
      if(response.data.success){
        commit(types.UPDATE_ROLE, response.data)
      }
      resolve(response)
    }).catch((err) => {
      reject(err)
    })
  })
}

export const deleteRole = ({ commit, dispatch, state }, id) => {
  return new Promise((resolve, reject) => {
    window.axios.delete(`/api/roles/${id}`).then((response) => {
      commit(types.DELETE_ROLE, id)
      resolve(response)
    }).catch((err) => {
      reject(err)
    })
  })
}

export const deleteMultipleRoles = ({ commit, dispatch, state }, id) => {
  return new Promise((resolve, reject) => {
    window.axios.post(`/api/roles/delete`, {'id': state.selectedRoles}).then((response) => {
      commit(types.DELETE_MULTIPLE_ROLES, state.selectedRoles)
      resolve(response)
    }).catch((err) => {
      reject(err)
    })
  })
}

export const setSelectAllState = ({ commit, dispatch, state }, data) => {
  commit(types.SET_SELECT_ALL_STATE, data)
}

export const selectAllRoles = ({ commit, dispatch, state }) => {
  if (state.selectedRoles.length === state.roles.length) {
    commit(types.SET_SELECTED_ROLES, [])
    commit(types.SET_SELECT_ALL_STATE, false)
  } else {
    let allRoleIds = state.roles.map(cust => cust.id)
    commit(types.SET_SELECTED_ROLES, allRoleIds)
    commit(types.SET_SELECT_ALL_STATE, true)
  }
}

export const selectRole = ({ commit, dispatch, state }, data) => {
  commit(types.SET_SELECTED_ROLES, data)
  if (state.selectedRoles.length === state.roles.length) {
    commit(types.SET_SELECT_ALL_STATE, true)
  } else {
    commit(types.SET_SELECT_ALL_STATE, false)
  }
}

export const resetSelectedRole = ({ commit, dispatch, state }, data) => {
  commit(types.RESET_SELECTED_ROLE)
}

export const add_to_database = () => {
  return new Promise((resolve, reject) => {
    window.axios.get(`/api/language/enterDatabase`).then((response) => {
      resolve(response)
    }).catch((err) => {
      reject(err)
    })
  })
}
