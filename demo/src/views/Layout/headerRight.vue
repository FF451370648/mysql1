<template>
  <div class="header-col header-col-right">
    <div class="header-right">
      <el-tag type="info">{{ nowTime }}</el-tag>
    </div>
  </div>
</template>

<script>

export default {
  name: "headerRight",
  data() {
    return {
      nowTime: "",
      timer: false,

    }
  },
  methods: {
    createTime() {
      this.nowTime = this.formatTime()
    },
    formatTime() {
      let time = new Date()
      let year = time.getFullYear()
      let month = time.getMonth() + 1
      let date = time.getDate()
      let hour = time.getHours()
      let min = time.getMinutes()
      let second = time.getSeconds()
      return year + "-" + month + "-" + date + "  " + hour + ":" + min + ":" + second
    },


  },
  mounted() {
    this.timer = setInterval(() => {
      this.createTime()
    }, 1000)
  },
  destroyed() {
    if (this.timer) {
      clearInterval(this.timer)
    }
  },
}
</script>

<style scoped>
.header-right {
  align-self: center;
  padding: 1px 5px;
}

.el-tag {
  margin: 1px 5px;
}

</style>