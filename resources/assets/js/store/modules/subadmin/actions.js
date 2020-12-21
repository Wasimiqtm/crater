import * as types from './mutation-types'

export const fetchSubAdmins = ({ commit, dispatch, state }, params) => {
  return new Promise((resolve, reject) => {
    window.axios.get(`/api/subAdmins`, {params}).then((response) => {
      commit(types.BOOTSTRAP_SUBADMINS, response.data.subAdmins.data)
      commit(types.SET_TOTAL_SUBADMINS, response.data.subAdmins.total)
      resolve(response)
    }).catch((err) => {
      reject(err)
    })
  })
}

export const fetchSubAdmin = ({ commit, dispatch }, id) => {
  return new Promise((resolve, reject) => {
    window.axios.get(`/api/subAdmins/${id}/edit`).then((response) => {
      resolve(response)
    }).catch((err) => {
      reject(err)
    })
  })
}

export const addSubAdmin = ({ commit, dispatch, state }, data) => {
  return new Promise((resolve, reject) => {
    window.axios.post('/api/subAdmins', data).then((response) => {
      commit(types.ADD_SUBADMIN, response.data)
      resolve(response)
    }).catch((err) => {
      reject(err)
    })
  })
}

export const updateSubAdmin = ({ commit, dispatch, state }, data) => {
  return new Promise((resolve, reject) => {
    window.axios.put(`/api/subAdmins/${data.id}`, data).then((response) => {
      if(response.data.success){
        commit(types.UPDATE_SUBADMIN, response.data)
      }
      resolve(response)
    }).catch((err) => {
      reject(err)
    })
  })
}

export const deleteSubAdmin = ({ commit, dispatch, state }, id) => {
  return new Promise((resolve, reject) => {
    window.axios.delete(`/api/subAdmins/${id}`).then((response) => {
      commit(types.DELETE_SUBADMIN, id)
      resolve(response)
    }).catch((err) => {
      reject(err)
    })
  })
}

export const deleteMultipleSubAdmins = ({ commit, dispatch, state }, id) => {
  return new Promise((resolve, reject) => {
    window.axios.post(`/api/subAdmins/delete`, {'id': state.selectedSubAdmins}).then((response) => {
      commit(types.DELETE_MULTIPLE_SUBADMINS, state.selectedSubAdmins)
      resolve(response)
    }).catch((err) => {
      reject(err)
    })
  })
}

export const setSelectAllState = ({ commit, dispatch, state }, data) => {
  commit(types.SET_SELECT_ALL_STATE, data)
}

export const selectAllSubAdmins = ({ commit, dispatch, state }) => {
  if (state.selectedSubAdmins.length === state.subAdmins.length) {
    commit(types.SET_SELECTED_SUBADMINS, [])
    commit(types.SET_SELECT_ALL_STATE, false)
  } else {
    let allSubAdminIds = state.subAdmins.map(cust => cust.id)
    commit(types.SET_SELECTED_SUBADMINS, allSubAdminIds)
    commit(types.SET_SELECT_ALL_STATE, true)
  }
}

export const selectSubAdmin = ({ commit, dispatch, state }, data) => {
  commit(types.SET_SELECTED_SUBADMINS, data)
  if (state.selectedSubAdmins.length === state.subAdmins.length) {
    commit(types.SET_SELECT_ALL_STATE, true)
  } else {
    commit(types.SET_SELECT_ALL_STATE, false)
  }
}

export const resetSelectedSubAdmin = ({ commit, dispatch, state }, data) => {
  commit(types.RESET_SELECTED_SUBADMIN)
}
