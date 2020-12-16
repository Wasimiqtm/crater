import mutations from './mutations'
import * as actions from './actions'
import * as getters from './getters'

const initialState = {
  permissions: [],
  totalPermissions: 0,
  selectAllField: false,
  selectedPermissions: []
}

export default {
  namespaced: true,

  state: initialState,

  getters: getters,

  actions: actions,

  mutations: mutations
}
