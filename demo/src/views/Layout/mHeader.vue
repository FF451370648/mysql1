<template>
  <div class="header">
    <el-row :gutter="20">
      <el-col :span="8"></el-col>
      <el-col :span="8"></el-col>
      <el-col :span="8" class="header-col header-col-right">
        <header-right></header-right>
      </el-col>
    </el-row>
  </div>
</template>

<script>
import headerRight from "@/views/Layout/headerRight";

export default {
  name: "mHeader",
  components: {
    headerRight
  }
}
</script>

<style scoped>

.el-row {
  display: flex;
  height: 100%;
}

.header-col {
  display: flex;
}

.header-col-right {
  justify-content: flex-end;
}


</style>