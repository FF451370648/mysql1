import axios from "axios";
import {Loading, Message} from 'element-ui';

let loadingInstance;

export function request(config, isLoading = true, loadText = "正在加载中") {
    //117.141.142.232:10111
    const instance = axios.create({
        baseURL: 'http://10.8.77.32/mysql',
        timeout: 0
    })
    if (isLoading) {
        loadingInstance = Loading.service({
            fullscreen: true,
            text: loadText
        });
    }
    instance.interceptors.response.use(
        response => {
            if (isLoading) loadingInstance.close()
            if (response.data.state !== 1) {
                Message.error(response.data.info)
            }
            return response.data
        },

        error => {
            if (isLoading) loadingInstance.close()
            Message.error("连接服务器失败,请重试")
            return {
                state: 2,
                info: "无法连接服务器"
            }
        })
    return instance(config)
}

export function request_gx(config, isLoading = true) {
    const instance = axios.create({
        baseURL: 'http://219.159.80.138:11018/geodbService',
        timeout: 0
    })
    let loadingInstance;
    if (isLoading) {
        loadingInstance = Loading.service({
            fullscreen: true,
            text: "正在加载中"
        });
    }
    instance.interceptors.response.use(
        response => {
            if (isLoading) loadingInstance.close()
            return response.data
        },

        error => {
            if (isLoading) loadingInstance.close()
            Message.error("连接服务器失败,请重试")
            return {
                state: 2,
                info: "无法连接服务器"
            }
        })
    return instance(config)
}

export function request_cn(config, isLoading = true) {
    const instance = axios.create({
        baseURL: 'http://222.247.49.59:81',
        timeout: 0
    })
    let loadingInstance;
    if (isLoading) {
        loadingInstance = Loading.service({
            fullscreen: true,
            text: "正在加载中"
        });
    }
    instance.interceptors.response.use(
        response => {
            if (isLoading) loadingInstance.close()
            return response.data
        },

        error => {
            if (isLoading) loadingInstance.close()
            return {
                state: 2,
                info: "无法连接服务器"
            }
        })
    return instance(config)
}

export function request_api_tdt(config, isLoading = true) {
    const instance = axios.create({
        baseURL: 'https://api.tianditu.gov.cn',
        timeout: 0
    })
    let loadingInstance;
    if (isLoading) {
        loadingInstance = Loading.service({
            fullscreen: true,
            text: "正在加载中"
        });
    }
    instance.interceptors.response.use(
        response => {
            if (isLoading) loadingInstance.close()
            return response.data
        },

        error => {
            if (isLoading) loadingInstance.close()
            return {
                state: 2,
                info: "无法连接服务器"
            }
        })
    return instance(config)
}

export function requestTable(config, isLoading = true) {
    const instance = axios.create({
        baseURL: 'http://117.141.142.232:8181/',
        timeout: 0
    })
    let loadingInstance;
    if (isLoading) {
        loadingInstance = Loading.service({
            fullscreen: true,
            text: "正在加载中"
        });
    }
    instance.interceptors.response.use(
        response => {
            if (isLoading) loadingInstance.close()
            return response.data
        },

        error => {
            if (isLoading) loadingInstance.close()
            return {
                state: 2,
                info: "无法连接服务器"
            }
        })
    return instance(config)
}

