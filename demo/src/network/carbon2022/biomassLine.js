import {request} from "@/network/request";


export function getSwlLineByDbh(params, isLoading = true,) {
    return request({
        url: "/swl2022/getSwlLineByDbh", data: params, method: 'post'
    }, isLoading, "正在计算曲线")
}