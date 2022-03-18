
import {getToken, removeToken, setToken} from "@/utils/auth";

const user = {
    state: {
        name: "",
        token: getToken("token"),
        organization: "",
        roles: [],
        auth_date: "",
        rules: [],
        organization_info: {}
    },
    getters: {
        token: state => state.token,
        userName: state => state.name,
        organization: state => state.organization,
        roles: state => state.roles,
        authDate: state => state.auth_date,
        organization_info: state => state.organization_info,
    },
    mutations: {

        set_name(state, name) {
            state.name = name
        },
        set_organization(state, organization) {
            state.organization = organization
        },
        set_roles(state, roles) {
            state.roles = roles
        },
        set_auth_date(state, auth_date) {
            state.auth_date = auth_date
        },
        set_organization_info(state, organization_info) {
            state.organization_info = organization_info
        }
    },
    actions: {
        logOut({commit}, data) {
            return new Promise((resolve, reject) => {
                logout(data).then(res => {
                    removeToken("token")
                    commit("set_roles", [])
                    commit("SET_TILEINFO", [])
                    resolve()
                })
            })
        },
        changeRules({commit}, newToken) {
            return new Promise((resolve, reject) => {
                const token = newToken
                setToken("token", token)
                getUserInfo({"token": token}).then(res => {
                    if (res.state === 1) {
                        let data = res.data
                        commit("set_name", data.name)
                        commit("set_organization", data.organization)
                        commit("set_roles", data.roles)
                        commit("set_auth_date", data.auth_date)
                        commit("set_organization_info", data.organization_info)
                    }
                })
            })
        }
    }
}
export default user