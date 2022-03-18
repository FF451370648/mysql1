import Vue from 'vue'
import App from './App.vue'
import store from './store'
import router from './router'
import ElementUI from 'element-ui';
import * as echarts from 'echarts';
import '@/assets/esri/themes/light/main.css'
import esriConfig from "@arcgis/core/config.js";

esriConfig.assetsPath = "http://117.141.142.232:10111/assets";
esriConfig.fontsUrl = "http://117.141.142.232:10111/fonts";
Vue.config.productionTip = false
Vue.use(ElementUI);
Vue.prototype.$echarts = echarts

new Vue({
    router,
    store,
    render: h => h(App)
}).$mount('#app')
