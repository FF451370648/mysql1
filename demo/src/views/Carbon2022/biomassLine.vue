<template>
  <div style="height: 100%">
    <div class="model-form">
      <el-row>
        <el-form ref="modelForm" :model="modelForm" label-width="100px">
          <el-col :span="4">
            <el-form-item label="选择模型">
              <el-select v-model="modelForm.type" placeholder="placeholder" @change="selectedModel">
                <el-option
                    v-for="item in modelOptions"
                    :key="item.value"
                    :label="item.label"
                    :value="item.value">
                </el-option>
              </el-select>
            </el-form-item>
          </el-col>
          <el-col :span="4">
            <el-form-item label="选择树种">
              <el-select v-model="modelForm.spe" placeholder="选择树种">
                <el-option
                    v-for="item in speOptions"
                    :key="item.value"
                    :label="item.label"
                    :value="item.value">
                </el-option>
              </el-select>
            </el-form-item>
          </el-col>
          <el-col :span="4">
            <el-form-item label="胸径范围">
              <el-col :span="11">
                <el-form-item>
                  <el-input v-model="modelForm.low" placeholder="最小胸径" type="number"></el-input>
                </el-form-item>
              </el-col>
              <el-col class="line" :span="2">-</el-col>
              <el-col :span="11">
                <el-form-item>
                  <el-input v-model="modelForm.top" placeholder="最大胸径" type="number"></el-input>
                </el-form-item>
              </el-col>
            </el-form-item>
          </el-col>
          <el-col :span="3">
            <el-form-item label="胸径尺度">
              <el-input v-model="modelForm.scale" type="number" step="0.01"></el-input>
            </el-form-item>
          </el-col>
          <el-col :span="4">
            <el-form-item label="定义名称">
              <el-input v-model="modelForm.name"></el-input>
            </el-form-item>
          </el-col>
          <el-col :span="6">
            <el-form-item>
              <el-button type="primary" size="small" @click="handleAddLine">添加</el-button>
              <el-button type="primary" size="small" @click="handleResetLine">重置</el-button>
              <el-button type="primary" size="small" @click="handleDownChart">下载图形</el-button>
              <el-button type="primary" size="small" @click="handleDownExcel">下载数据</el-button>
            </el-form-item>
          </el-col>
        </el-form>
      </el-row>
    </div>
    <div id="lineChart" style="width: 100%;height: 85%"></div>
  </div>
</template>

<script>
import {getSwlLineByDbh} from "@/network/carbon2022/biomassLine";
import {exportExcel, exportExcelByDataAndName} from "@/common/excel";

export default {
  name: "biomassLine",
  data() {
    return {
      myChart: "",
      chartOption: {
        animation: false,
        legend: {
          data: [],
          bottom: 10,
          itemGap: 30,
          textStyle: {
            fontSize: 20
          }
        },
        grid: {
          bottom: 100,
        },
        xAxis: {
          name: '胸径',
          nameTextStyle: {
            fontSize: 20
          },
          min: 5,
          minorTick: {
            show: true
          },
          minorSplitLine: {
            show: true
          }
        },
        yAxis: {
          name: '地上生物量',
          nameTextStyle: {
            fontSize: 20
          },
          minorTick: {
            show: true
          },
          minorSplitLine: {
            show: true
          }
        },
        tooltip: {
          trigger: 'axis'
        },
        series: []
      },
      modelForm: {
        type: "101",
        spe: "杉木",
        low: 5,
        top: 30,
        scale: 0.05,
        name: "杉木",
        color: 'rgba(19, 206, 102, 0.8)'
      },
      modelOptions: [{
        label: "国家",
        value: "101"
      }, {
        label: "广西",
        value: "102"
      }],
      speOptions: [],
      speOptions1: [
        {
          label: "杉木",
          value: "杉木"
        }, {
          label: "马尾松",
          value: "马尾松"
        }, {
          label: "云南松",
          value: "云南松"
        }, {
          label: "荷木",
          value: "荷木"
        }, {
          label: "枫香",
          value: "枫香"
        }, {
          label: "栎树",
          value: "栎树"
        }, {
          label: "湿地松",
          value: "湿地松"
        }
      ],
      speOptions2: [
        {
          label: "杉木",
          value: "杉木"
        }, {
          label: "马尾松",
          value: "马尾松"
        }, {
          label: "桉树",
          value: "桉树"
        }, {
          label: "阔叶树",
          value: "阔叶树"
        }, {
          label: "栎树",
          value: "栎树"
        }
      ]
    }
  },
  methods: {
    selectedModel() {
      this.modelForm.spe = ""
      if (this.modelForm.type === "101")
        this.speOptions = this.speOptions1
      else this.speOptions = this.speOptions2
    },
    handleAddLine() {
      if (Number(this.modelForm.top) <= Number(this.modelForm.low)) {
        this.$message.error("胸径范围设置不正确")
      } else if (this.modelForm.spe === "") {
        this.$message.error("树种设置不正确")
      } else if (this.modelForm.scale <= 0) {
        this.$message.error("胸径尺度设置不正确")
      } else if (this.modelForm.name === "") {
        this.$message.error("名称必须输入")
      } else if (this.chartOption.legend.data.indexOf(this.modelForm.name) !== -1) {
        this.$message.error("这个名称重复了")
      } else {
        getSwlLineByDbh(this.modelForm).then(res => {
          if (res.state === 1) {
            this.setChartOption(res.data)
            this.drawLine()
          }
        })
      }
    },
    handleResetLine() {
      this.chartOption.legend.data = []
      this.chartOption.series = []
      this.myChart.clear()
      this.drawLine()
    },
    setChartOption(data) {
      let addData = {
        type: 'line',
        showSymbol: false,
        name: this.modelForm.name,
        clip: true,
        data: data,
        lineStyle: {
          width: 3
        }
      }
      this.chartOption.legend.data.push(this.modelForm.name)
      this.chartOption.series.push(addData)
      let seriesData = this.chartOption.series.map(item => {
        return item.data
      })
      let xMin = 100
      seriesData.forEach(data => {
        data.forEach(value => {
          if (Number(value[0]) < xMin) xMin = Number(value[0])
        })
      })
      this.chartOption.xAxis.min = xMin
    },
    drawLine() {
      this.myChart.setOption(this.chartOption)
    },
    handleDownChart() {
      let img = new Image();
      img.setAttribute('crossOrigin', 'anonymous')
      img.onload = function () {
        let canvas = document.createElement('canvas')
        canvas.width = img.width
        canvas.height = img.height
        let context = canvas.getContext('2d')
        context.drawImage(img, 0, 0, img.width, img.height)
        let url = canvas.toDataURL('image/png')
        let a = document.createElement('a')
        let event = new MouseEvent('click')
        a.download = name || '一元生物量表'
        a.href = url
        a.dispatchEvent(event);
      }
      img.src = this.myChart.getDataURL({
        pixelRatio: 2,
        backgroundColor: '#fff'
      });
    },
    handleDownExcel() {
      const excelData = []
      for (let i = 0; i < this.chartOption.series.length; i++) {
        let data = this.chartOption.series[i].data
        let name = this.chartOption.legend.data[i]
        excelData.push({data, name})
      }
      if (excelData && excelData.length > 0) {
        exportExcelByDataAndName(excelData)
      }
    }
  },
  mounted() {
    this.selectedModel()
    this.myChart = this.$echarts.init(document.getElementById("lineChart"))
    this.drawLine()
  },
  watch: {
    modelForm() {

    }
  }
}
</script>

<style>
.model-form {
  padding: 15px;
}
</style>