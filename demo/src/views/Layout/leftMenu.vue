<template>

  <el-menu
      :default-active="$route.path"
      class="el-menu-class"
      background-color="#545c64"
      text-color="#fff"
      active-text-color="#00ffff"
      :show-timeout="200"
      :router=true
  >
    <div v-for="(item,index) in permission_routers">
      <el-menu-item :index="item.path+'/index'" v-if="!item.hidden && item.noDropdown" :key="index">
        <span slot="title">{{ item.meta.title }}</span>
      </el-menu-item>
      <el-submenu :index="getSubIndex(index)" v-if="!item.hidden && item.children.length>=1 && !item.noDropdown"
                  :key="index">
        <template slot="title">
          <span>{{ item.meta.title }}</span>
        </template>
        <el-menu-item-group>
          <el-menu-item :index="getIndex(cItem,item)" v-for="(cItem,cIndex) in item.children" v-if="!cItem.hidden"
                        :key="getKey(cIndex,index)">
            {{ cItem.meta.title }}
          </el-menu-item>
        </el-menu-item-group>
      </el-submenu>
    </div>
  </el-menu>
</template>

<script>
import {mapGetters} from 'vuex'

export default {
  name: "leftMenu",
  computed: {
    ...mapGetters(['permission_routers'])
  },
  methods: {
    getSubIndex(index) {
      return 's' + index
    },
    getIndex(cItem, item) {
      return item.path + "/" + cItem.path
    },
    getKey(cIndex, index) {
      return index + "-" + cIndex
    }
  }
}
</script>

<style scoped>
.el-menu-class {
  height: 100%;
}

.el-submenu .el-menu-item {
  min-width: 100px;
}
</style>