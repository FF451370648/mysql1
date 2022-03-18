<template xmlns="">
  <div class="home">
    <el-container>
      <el-header class="header">
        <m-header></m-header>
      </el-header>
      <el-container class="center-right">
        <el-aside :width=sideWidth>
          <left-menu></left-menu>
        </el-aside>
        <el-container>
          <el-main>
            <router-view/>
          </el-main>
        </el-container>
      </el-container>
    </el-container>
  </div>
</template>

<script>
import mHeader from "@/views/Layout/mHeader";
import leftMenu from "@/views/Layout/leftMenu";

export default {
  name: "index",
  components: {mHeader, leftMenu},
  data() {
    return {
      sideWidth: '180px'
    }
  }
}
</script>

<style scoped>


.header {
  background-color: #829ab0;
  color: #e8e8e8;
  text-align: center;
  height: 5vh !important;
}


.center-right {
  height: 95vh;
}

.el-menu-class {
  height: 100%;
}

.el-main {
  padding: 0;
}
</style>