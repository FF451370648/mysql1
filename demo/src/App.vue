<template>
  <div id="app">
    <router-view></router-view>
  </div>
</template>

<script>


export default {
  name: 'App',
}
</script>
<style>

#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  height: 100vh;
}

.table-pagination {
  margin-top: 8px;
  display: flex;
  justify-content: flex-end;
}

.table-button-header {
  margin-bottom: 8px;
  display: flex;
}

.table-default {
  margin: 8px;
}

.el-loading-mask {
  z-index: 3110 !important;
}
</style>
