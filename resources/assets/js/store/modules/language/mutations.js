import * as types from './mutation-types'

export default {
  [types.BOOTSTRAP_LANGUAGE] (state, language) {
    state.language = language
  },

  [types.SET_TOTAL_LANGUAGE] (state, totalLanguage) {
    state.totalLanguage = totalLanguage
  },

  [types.ADD_LANGUAGE] (state, data) {
    state.language.push(data.language)
  },

  [types.UPDATE_LANGUAGE] (state, data) {
    let pos = state.language.findIndex(anguage => language.id === data.language.id)

    state.language[pos] = data.language
  },

  [types.DELETE_LANGUAGE] (state, id) {
    let index = state.language.findIndex(LANGUAGE => language.id === id)
    state.language.splice(index, 1)
  },

  [types.DELETE_MULTIPLE_LANGUAGE] (state, selectedLanguage) {
    selectedLanguage.forEach((anguage) => {
      let index = state.language.findIndex(_cust => _cust.id === language.id)
      state.language.splice(index, 1)
    })

    state.selectedLanguage = []
  },

  [types.SET_SELECTED_LANGUAGE] (state, data) {
    state.selectedLanguage = data
  },

  [types.RESET_SELECTED_LANGUAGE] (state, data) {
    state.selectedLanguage = null
  },

  [types.SET_SELECT_ALL_STATE] (state, data) {
    state.selectAllField = data
  }

}
