const XLSX = require('xlsx');

export function exportExcelByDataAndName(data) {
    console.log(data)
    let wb = XLSX.utils.book_new()
    data.forEach(item => {
        let sheet = XLSX.utils.json_to_sheet(item.data)
        XLSX.utils.book_append_sheet(wb, sheet, item.name)
    })
    XLSX.writeFile(wb, "导出数据.xlsx")
}