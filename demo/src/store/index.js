import Vue from 'vue'
import Vuex from 'vuex'
import user from './modules/user'
import permission from './modules/permission'
import gis from "@/store/modules/gis";

Vue.use(Vuex)

export default new Vuex.Store({
  modules: {user, permission, gis}
})
