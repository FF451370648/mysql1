import {request} from "@/network/request";

export function checkTlsTreeNo(params, isLoading = true) {
  return request({
    url: "/checkTlsTreeNo",
    data: params,
    method: 'post'
  }, isLoading)
}

export function addTlsTree(params, isLoading = true) {
  return request({
    url: "/addTlsTree",
    data: params,
    method: 'post'
  }, isLoading)
}

export function getTlsTreeTable(params, isLoading = false) {
  return request({
    url: "/getTlsTreeTable",
    data: params,
    method: 'post'
  }, isLoading)
}

export function getTlsTreeXyz(params, isLoading = true) {
  return request({
    url: "/getTlsTreeXyz",
    data: params,
    method: 'post'
  }, isLoading)
}