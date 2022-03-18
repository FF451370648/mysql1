import Vue from 'vue'
import VueRouter from 'vue-router'
import {Layout} from "@/views/Layout"

Vue.use(VueRouter)

export const constantRouterMap = [
    {
        path: '',
        component: Layout,
        redirect: '/index/index',
        hidden: true
    },
    {
        path: '/401',
        name: "401",
        meta: {
            title: '错误提示',
        },
        component: () => import('@/views/error/401'),
        hidden: true
    },
    {
        path: '/index',
        name: 'index',
        component: Layout,
        meta: {
            title: 'index',
        },
        children: [
            {
                path: 'index',
                meta: {
                    title: 'index',
                },
                name: "index",
                component: () => import('@/views/index/index'),
            },
            {
                path: 'home',
                meta: {
                    title: 'home',
                },
                name: "home",
                component: () => import('@/views/index/home'),
            }
        ]
    },
    {
        path: '/carbon2022',
        name: 'carbon2022',
        meta: {
            title: '碳汇项目2022',
        },
        component: Layout,
        children: [
            {
                path: 'biomassLine',
                meta: {
                    title: '生物量一元曲线',
                },
                name: "biomassLineByDbh",
                component: () => import('@/views/Carbon2022/biomassLine'),
            }
        ]
    },
]
const router = new VueRouter({
    mode: 'hash',
    base: 'tyf',
    routes: constantRouterMap
})

export default router
