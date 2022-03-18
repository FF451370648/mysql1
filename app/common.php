<?php
require_once('./vendor/gasparesganga/php-shapefile/src/Shapefile/ShapefileAutoloader.php');
Shapefile\ShapefileAutoloader::register();

use Shapefile\Shapefile;
use Shapefile\ShapefileReader;
use Shapefile\Geometry\Polygon;
use Shapefile\Geometry\MultiPolygon;
use Shapefile\ShapefileWriter;
use think\facade\Db;
use think\helper\Str;


/**
 * 获取随机组织ID
 * @return string
 */
function get_organization_uid(): string
{
    return "o" . date("Ymd") . Str::random(16);
}

/**
 * 获取随机用户ID
 * @return string
 */
function get_user_uid(): string
{
    return "u" . date("Ymd") . Str::random(11);
}

/**
 * 获取登录TOKENID
 * @return string
 */
function get_token_id(): string
{
    return "t" . date("Ymd") . Str::random(21);
}

/**
 * 获取随机项目ID
 * @return string
 */
function get_project_id(): string
{
    return "p" . date("Ymd") . Str::random(26);
}

/**
 * 获取造林项目ID
 * @return string
 */
function get_afforestation_id(): string
{
    return "af" . date("Ymd") . Str::random(20);
}

/**
 * 发起http get请求(REST API), 并获取REST请求的结果
 * @param string $url
 * @return bool|string - http response body if succeeds, else false.
 */
function request_get(string $url = '')
{
    if (empty($url)) {
        return false;
    }

    $postUrl = $url;
    // 初始化curl
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $postUrl);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    // 要求结果为字符串且输出到屏幕上
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    // 运行curl
    $data = curl_exec($curl);
    $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    if ($code != 200) {
        return false;
    }
    curl_close($curl);
    return $data;
}

/**
 * 发起http post请求(REST API), 并获取REST请求的结果
 * @param string $url
 * @param array $param
 * @return bool|string - http response body if succeeds, else false.
 */
function request_post(string $url = '', $param = '')
{
    if (empty($url) || empty($param)) {
        return false;
    }

    $postUrl = $url;
    $curlPost = $param;
    // 初始化curl
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $postUrl);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    // 要求结果为字符串且输出到屏幕上
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    // post提交方式
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
    // 运行curl
    $data = curl_exec($curl);
    curl_close($curl);

    return $data;
}

/**
 * 发起http post请求(REST API), 并获取REST请求的结果
 * @param string $url
 * @param string $param
 * @return - http response body if succeeds, else false.
 */
function request_post_2($url = '', $param = '')
{
    if (empty($url) || empty($param)) {
        return false;
    }

    $postUrl = $url;
    $curlPost = $param;
    // 初始化curl
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $postUrl);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    // 要求结果为字符串且输出到屏幕上
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $headers = array("Content-Type: application/json;charset=UTF-8", "Accept: application/json");
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    // post提交方式
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
    // 运行curl
    $data = curl_exec($curl);
    curl_close($curl);

    return $data;
}

function base64EncodeImage($image_file)
{
    $image_info = getimagesize($image_file);
    $image_data = fread(fopen($image_file, 'r'), filesize($image_file));
    return 'data:' . $image_info['mime'] . ';base64,' . chunk_split(base64_encode($image_data));
}

/**
 * 解压文件并返回指定类型文件名
 * @param string $path
 * @param string $zip_file_name
 * @param array $file_type
 * @return array file_path
 */
function unzip(string $path, string $zip_file_name, array $file_type): array
{
    $re["state"] = 2;
    $zip_tmp_path = $path . str_replace(".zip", "", $zip_file_name);
    mkdir($zip_tmp_path);
    $zip = new ZipArchive();
    if ($zip->open($path . $zip_file_name)) {
        $zip->extractTo($zip_tmp_path);
        $zip->close();
        unlink($path . $zip_file_name);
        $re = searchDir($zip_tmp_path, $file_type);
    } else {
        $re["info"] = "这个文件无法解压";
    }

    return $re;
}

/**
 * 解压文件并返回指定类型文件名
 * @param string $path
 * @param array $file_type
 * @return array $info
 */
function searchDir(string $path, array $file_type): array
{
    $re["state"] = 2;
    $re["info"] = "没有找到指定文件";
    if ($dh = @openDir($path)) {
        while (($file = readdir($dh)) !== false) {
            if ($file != "." and $file != ".." and in_array(pathinfo($file)['extension'], $file_type)) {
                $re["info"] = "解压并且找到了指定文件";
                $re["file_path"] = $path . "/" . $file;
                $re["file_type"] = pathinfo($file)['extension'];
                $re["state"] = 1;
                break;
            }
        }
    }
    return $re;
}

/**
 * 搜索CODE返回VALUE
 * @param array $array
 * @param mixed $search
 * @return mixed $result
 */
function in_search_code(array $array, $search)
{
    $result = $search;
    foreach ($array as $value) {
        if ($result == $value["code"]) return $value["value"];
    }
    return $result;
}

/**
 * 读取XYZ文件
 * @param string $path
 * @return array $result
 */
function readXYZ(string $path): array
{
    $re = array();
    $data = array();

    if (is_file($path)) {
        $file = fopen($path, "r");
        $x_key = 0;
        $y_key = 0;
        $z_key = 0;
        $z_min = 200;
        $start = false;
        while (!feof($file)) {
            $getInfo = explode(" ", fgets($file));
            if (count($getInfo) > 2) {
                try {
                    if (!$start) {

                        if (in_array("X", $getInfo)) {
                            foreach ($getInfo as $key => $val) {
                                if ($val == "X") $x_key = $key;
                                if ($val == "Y") $y_key = $key;
                                if ($val == "Z") $z_key = $key;
                            }
                            $start = true;
                        }
                    } else {
                        //var_dump(count($getInfo));

                        $reTmp = array();
                        $reTmp[] = (double)$getInfo[$x_key];
                        $reTmp[] = (double)$getInfo[$y_key];
                        $reTmp[] = (double)$getInfo[$z_key];
                        if ($z_min > (double)$getInfo[$z_key]) $z_min = (double)$getInfo[$z_key];
                        $data[] = $reTmp;
                    }
                } catch (ErrorException $e) {
                    var_dump($e->getMessage());
                    var_dump($getInfo);
                }
            }
        }
        $re["data"] = $data;
        $re["z_min"] = $z_min;
    }
    return $re;
}

/**
 * wkt转geoJson
 * @param string $wkt
 * @return string $result
 */
function poly_wkt2geo(string $wkt): string
{
    if (empty($wkt)) return "";
    if (str_contains($wkt, 'MU')) {
        $polygon = new MultiPolygon();
    } else {
        $polygon = new Polygon();
    }


    try {
        $polygon->initFromWKT($wkt);
        $data = $polygon->getGeoJSON();
    } catch (ShapefileException $e) {
        $polygon = new MultiPolygon();
        $polygon->initFromWKT($wkt);
        $data = $polygon->getGeoJSON();
    }

    return $data;
}

/**
 * geoJson转wkt
 * @param string $geoJson
 * @return string $result
 */
function poly_geo2wkt(string $geoJson): string
{
    $polygon = new Polygon();
    try {
        $polygon->initFromGeoJSON($geoJson);
    } catch (\Shapefile\ShapefileException $e) {
        return '';
    }
    return $polygon->getWKT();
}

/**
 * 平板转PBSQL
 * @param array $data
 * @param array $fields
 * @return array|null $result
 */
function pb2pgsql($data, array $fields): ?array
{
    foreach ($fields as $val) {
        switch ($val["type"]) {
            case "文本":
            case "行政代码":
            case "固定":
                $re[$val["name"]] = trim($data[$val["alias"]]);
                break;
            case "自定代码":
                $code = trim($data[$val["alias"]]);
                $code = explode("(", $code);
                $code = array_pop($code);
                $code = explode(")", $code);
                $code = $code[0];
                $re[$val["name"]] = (string)$code;
                break;
            case "整数":
                $re[$val["name"]] = (int)trim($data[$val["alias"]]);
                break;
            case "小数":
                $re[$val["name"]] = round(trim($data[$val["alias"]]), (int)$val["precision"]);
                break;
        }
    }
    $re["state"] = 3;
    $geoJson = array();
    $geoJson["type"] = "Polygon";

    $geoJson["coordinates"] = json_decode($data["shape"], true)["rings"];

    $rings = poly_geo2wkt(json_encode($geoJson, JSON_UNESCAPED_UNICODE));

    if (count(explode(",", $rings)) < 2) {
        return null;
    }
    $re["shape"] = "sde.st_geometry ('" . $rings . "',4490)";
    return $re;
}

/**
 * 计算再哪个投影带
 * @param double $x
 * @return int $result
 */
function geo2000_2planar(float $x): int
{
    $t = 4522;
    if ($x < 106.5 and $x > 103.5) {
        $t = 4523;
    } elseif ($x < 109.5 and $x > 106.5) {
        $t = 4524;
    } elseif ($x < 112.5 and $x > 109.5) {
        $t = 4525;
    } elseif ($x < 115.5 and $x > 112.5) {
        $t = 4526;
    } elseif ($x < 118.5 and $x > 115.5) {
        $t = 4527;
    } elseif ($x < 121.5 and $x > 118.5) {
        $t = 4528;
    }
    return $t;
}

/**
 * 自定义压缩图片
 * @param string $sFile 源图片路径
 * @param int $multiple 自定义图片宽度
 * @return bool
 */
function getThumbI(string $sFile, int $multiple)
{
    //图片公共路径
    $public_path = '';
    //判断该图片是否存在
    if (!file_exists($public_path . $sFile)) return $sFile;
    //判断图片格式(图片文件后缀)
    $extend = explode(".", $sFile);
    $attach_fileext = strtolower($extend[count($extend) - 1]);
    if (!in_array($attach_fileext, array('jpg', 'png', 'jpeg'))) {
        return '';
    }
    $img = getimagesize($sFile);
    switch ($img[2]) {
        case 1:
            $im = @imagecreatefromgif($sFile);
            break;
        case 2:
            $im = @imagecreatefromjpeg($sFile);
            break;
        case 3:
            $im = @imagecreatefrompng($sFile);
            break;
    }
    $pic_width = round(imagesx($im) * $multiple);
    $pic_height = round(imagesy($im) * $multiple);

    $sFileNameS = str_replace("." . $attach_fileext, "_" . $pic_width . '_' . $pic_height . '.' . $attach_fileext, $sFile);
    //判断是否已压缩图片，若是则返回压缩图片路径
    if (file_exists($public_path . $sFileNameS)) {
        return $sFileNameS;
    }

    //生成压缩图片，并存储到原图同路径下
    resizeImage($public_path . $sFile, $public_path . $sFileNameS, $pic_width, $pic_height);
    if (file_exists($public_path . $sFileNameS)) {
        unlink($sFile);
        copy($public_path . $sFileNameS, $sFile);
        unlink($public_path . $sFileNameS);
        return true;
    }

    return false;
}

/**
 * 生成图片
 * @param string $im 源图片路径
 * @param string $dest 目标图片路径
 * @param int $maxwidth 生成图片宽
 * @param int $maxheight 生成图片高
 */
function resizeImage(string $im, string $dest, int $maxwidth, int $maxheight)
{
    $img = getimagesize($im);
    switch ($img[2]) {
        case 1:
            $im = @imagecreatefromgif($im);
            break;
        case 2:
            $im = @imagecreatefromjpeg($im);
            break;
        case 3:
            $im = @imagecreatefrompng($im);
            break;
    }

    $pic_width = imagesx($im);
    $pic_height = imagesy($im);
    $resizewidth_tag = false;
    $resizeheight_tag = false;

    if (($maxwidth && $pic_width > $maxwidth) || ($maxheight && $pic_height > $maxheight)) {
        if ($maxwidth && $pic_width > $maxwidth) {
            $widthratio = $maxwidth / $pic_width;
            $resizewidth_tag = true;
        }

        if ($maxheight && $pic_height > $maxheight) {
            $heightratio = $maxheight / $pic_height;
            $resizeheight_tag = true;
        }

        if ($resizewidth_tag && $resizeheight_tag) {
            if ($widthratio < $heightratio)
                $ratio = $widthratio;
            else
                $ratio = $heightratio;
        }


        if ($resizewidth_tag && !$resizeheight_tag)
            $ratio = $widthratio;
        if ($resizeheight_tag && !$resizewidth_tag)
            $ratio = $heightratio;
        $newwidth = $pic_width * $ratio;
        $newheight = $pic_height * $ratio;
        if (function_exists("imagecopyresampled")) {
            $newim = imagecreatetruecolor($newwidth, $newheight);
            imagecopyresampled($newim, $im, 0, 0, 0, 0, $newwidth, $newheight, $pic_width, $pic_height);
        } else {
            $newim = imagecreate($newwidth, $newheight);
            imagecopyresized($newim, $im, 0, 0, 0, 0, $newwidth, $newheight, $pic_width, $pic_height);
        }

        imagejpeg($newim, $dest);
        imagedestroy($newim);
    } else {
        imagejpeg($im, $dest);
    }
}

function testExcel()
{
    $model_path = app()->getRootPath() . 'public/model_excel/简易伐区设计表.xls';
    $path = "D:/简易伐区设计表.xls";
    $objReader = PHPExcel_IOFactory::createReader('Excel5');
    $objPHPExcel = $objReader->load($model_path);
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    header('pragma:public');
    header("Content-Disposition:attachment;filename=cs.xls");
    header('Cache-Control: max-age=0');
    $objWriter->save('php://output');
}

/**
 * 生成简易伐区设计表
 * @param string $path 路径
 * @param array $data 数据
 * @param array $user 信息
 * @return string
 */
function writeCutReport3(string $path, array $data, array $user)
{
    $model_path = app()->getRootPath() . 'public/model_excel/简易伐区设计表.xls';
    $write_path = $path . "简易伐区设计表.xls";
    $objPHPExcel = new \PHPExcel();
    $objPHPExcel->getProperties()
        ->setCreator("tyf")
        ->setLastModifiedBy("tyf")
        ->setTitle("tree cut report")
        ->setSubject("tree")
        ->setDescription("tree")
        ->setKeywords("tree cut")
        ->setCategory("tree cut");
    $i = 1;
    foreach ($data as $datum) {
        try {
            $loadReader = PHPExcel_IOFactory::createReader('Excel5');
            $loadPHPExcel = $loadReader->load($model_path);
            $sheet = $loadPHPExcel->getSheet(0);
            $sheet->setTitle('简易伐区设计表' . $i);
            $sheet->setCellValue('J3', $datum['uid_label']);
            $sheet->setCellValue('A4', $sheet->getCell('A4')->getValue() . $datum['wy_name']);
            $sheet->setCellValue('G4', $sheet->getCell('G4')->getValue() . $datum['ny_name']);
            $sheet->setCellValue('A6', $datum['xian_c']);
            $sheet->setCellValue('E6', $datum['xiang_c']);
            $sheet->setCellValue('G6', $datum['cun_c']);
            $sheet->setCellValue('I6', (int)substr($datum['lin_ban'], 0, 2) . '林班');
            $sheet->setCellValue('K6', (int)$datum['xiao_ban'] . '小班');
            $sheet->setCellValue('A7', $sheet->getCell('A7')->getValue() . $datum['location'] . ",四至界线:东至" . $datum['east'] . " 南至" . $datum['south'] . " 西至" . $datum['west'] . " 北至" . $datum['north']);
            $sheet->setCellValue('A8', $sheet->getCell('A8')->getValue() . $datum['mz_mc']);
            $sheet->setCellValue('A9', $sheet->getCell('A9')->getValue() . $datum['mian_ji'] . 'hm²');
            $sheet->setCellValue('C9', $sheet->getCell('C9')->getValue() . $datum['di_lei']);
            $sheet->setCellValue('E9', $sheet->getCell('E9')->getValue() . $datum['lm_qs']);
            $sheet->setCellValue('G9', $sheet->getCell('G9')->getValue() . $datum['sen_lin_lb']);
            $sheet->setCellValue('A10', $sheet->getCell('A10')->getValue() . $datum['lin_zhong']);
            $sheet->setCellValue('D10', $sheet->getCell('D10')->getValue() . $datum['bhdj']);
            $sheet->setCellValue('G10', $sheet->getCell('G10')->getValue() . $datum['pingjun_nl']);
            $sheet->setCellValue('I10', $sheet->getCell('I10')->getValue() . $datum['ling_zu']);
            $sheet->setCellValue('K10', $sheet->getCell('K10')->getValue() . $datum['yu_bi_du']);
            $sheet->setCellValue('A11', $sheet->getCell('A11')->getValue() . $datum['shu_zhong']);
            $sheet->setCellValue('C11', $sheet->getCell('C11')->getValue() . $datum['qi_yuan']);
            $sheet->setCellValue('E11', $sheet->getCell('E11')->getValue() . $datum['pingjun_xj'] . 'cm');
            $sheet->setCellValue('G11', $sheet->getCell('G11')->getValue() . $datum['pingjun_sg'] . 'm');
            $sheet->setCellValue('I11', $sheet->getCell('I11')->getValue() . $datum['mei_gq_xj'] . 'm³');
            $sheet->setCellValue('A12', $sheet->getCell('A12')->getValue() . $datum['mei_gq_zs'] . '株');
            $sheet->setCellValue('E12', $sheet->getCell('E12')->getValue() . $datum['xb_xj'] . 'm³');
            $sheet->setCellValue('I12', $sheet->getCell('I12')->getValue() . $datum['xb_zs'] . '株');
            $sheet->setCellValue('A14', $sheet->getCell('A14')->getValue() . $datum['cf_lx']);
            $sheet->setCellValue('E14', $sheet->getCell('E14')->getValue() . $datum['cf_fs']);
            $sheet->setCellValue('I14', $sheet->getCell('I14')->getValue() . $datum['cf_qd'] . "%");
            $sheet->setCellValue('A15', $sheet->getCell('A15')->getValue() . $datum['cf_mianji'] . "hm²");
            $sheet->setCellValue('C15', $sheet->getCell('C15')->getValue() . $datum['cf_xj'] . "m³");
            $sheet->setCellValue('E15', $sheet->getCell('E15')->getValue() . $datum['xb_cj'] . "m³");
            $sheet->setCellValue('I15', $sheet->getCell('I15')->getValue() . $datum['cf_ccl'] . "%");
            $sheet->setCellValue('A16', $sheet->getCell('A16')->getValue() . $datum['cfh_gq_zs'] . "株");
            $sheet->setCellValue('G16', $sheet->getCell('G16')->getValue() . $datum['cfh_ybd']);
            $sheet->setCellValue('A17', $sheet->getCell('A17')->getValue() . $datum['cf_date']);
            $sheet->setCellValue('A19', $sheet->getCell('A19')->getValue() . $datum['gx_date']);
            $sheet->setCellValue('E19', $sheet->getCell('E19')->getValue() . $datum['gx_mianji'] . "m³");
            $sheet->setCellValue('I19', $sheet->getCell('I19')->getValue() . $datum['gx_shuz']);
            $sheet->setCellValue('A20', $sheet->getCell('A20')->getValue() . $datum['gx_qi_yuan']);
            $sheet->setCellValue('E20', $sheet->getCell('E20')->getValue() . $datum['gx_zd']);
            $sheet->setCellValue('I20', $sheet->getCell('I20')->getValue() . $datum['gx_gg']);
            $sheet->setCellValue('A21', $sheet->getCell('A21')->getValue() . $datum['gx_gq_zs'] . '株/公顷');
            $sheet->setCellValue('E21', $sheet->getCell('E21')->getValue() . $datum['gx_zhj'] . 'm');
            $sheet->setCellValue('G21', $sheet->getCell('G21')->getValue() . $datum['gx_miao_mu']);
            $sheet->setCellValue('I21', $sheet->getCell('I21')->getValue() . $datum['gx_yong_m'] . '株');
            $sheet->setCellValue('A22', $sheet->getCell('A22')->getValue() . $datum['gx_fy_cs']);
            $sheet->setCellValue('I22', $sheet->getCell('I22')->getValue() . $datum['gx_mu_zhu']);
            $sheet->setCellValue('C23', $datum['bak']);
            $sheet->setCellValue('E24', $user['organization']);
            $sheet->setCellValue('J24', date('Y-m-d'));
            $sheet->getRowDimension(1)->setRowHeight(14);
            $sheet->getRowDimension(2)->setRowHeight(35);
            $sheet->getRowDimension(4)->setRowHeight(21);
            $sheet->getRowDimension(5)->setRowHeight(21);
            $sheet->getRowDimension(7)->setRowHeight(51);
            $sheet->getRowDimension(13)->setRowHeight(21);
            $sheet->getRowDimension(16)->setRowHeight(22);
            $sheet->getRowDimension(17)->setRowHeight(22);
            $sheet->getRowDimension(18)->setRowHeight(21);
            $sheet->getRowDimension(23)->setRowHeight(48);
            $sheet->getRowDimension(24)->setRowHeight(30);
            $objPHPExcel->addExternalSheet($sheet);
            ob_clean();

        } catch (ErrorException $e) {

        }
        $i++;
    }
    $objPHPExcel->removeSheetByIndex(0);
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save($write_path);
    return $write_path;
}

/**
 * 生成伐区设计汇总表
 * @param string $path 路径
 * @param array $data 数据
 * @param array $user 信息
 * @return string
 */
function writeCutReport4(string $path, array $data, array $user): string
{

    $model_path = app()->getRootPath() . 'public/model_excel/皆伐表.xls';
    $write_path = $path . "皆伐伐区调查设计汇总表.xls";
    $loadReader = PHPExcel_IOFactory::createReader('Excel5');
    $objPHPExcel = $loadReader->load($model_path);
    $sheet = $objPHPExcel->getSheet(0);
    $sheet->setCellValue('A3', "县:" . $data[0]['xian_c']);
    $cellStyle = $sheet->getCell('A7')->getStyle();
    $style = array(
        'borders' => array(
            'allborders' => array( //设置全部边框
                'style' => \PHPExcel_Style_Border::BORDER_THIN //粗的是thick
            ),
        ),
        'font' => array(
            'name' => $cellStyle->getFont()->getName(),
            'size' => 9),
    );
    $data_num = count($data);
    $xj_all = 0;
    $sg_all = 0;
    $vol = 0;
    $area = 0;
    $zs = 0;
    $tvol = 0;
    for ($i = 0; $i < $data_num; $i++) {
        $k = $i + 7;
        $obj = $data[$i];
        try {
            $sheet->setCellValue('A' . $k, $obj['xiang_c']);
            $sheet->setCellValue('B' . $k, $obj['cun_c']);
            $sheet->setCellValue('C' . $k, (int)substr($obj['lin_ban'], 0, 2));
            $sheet->setCellValue('D' . $k, (int)$obj['xiao_ban']);
            $sheet->setCellValue('E' . $k, $obj['di_lei']);
            $sheet->setCellValue('F' . $k, $obj['lin_zhong']);
            $sheet->setCellValue('G' . $k, $obj['shu_zhong']);
            $sheet->setCellValue('H' . $k, $obj['qi_yuan']);
            $sheet->setCellValue('I' . $k, $obj['pingjun_nl']);
            $sheet->setCellValue('J' . $k, $obj['yu_bi_du']);
            $sheet->setCellValue('K' . $k, $obj['pingjun_xj']);
            $xj_all += $obj['pingjun_xj'] * $obj['pingjun_xj'] * $obj['xb_zs'];
            $sheet->setCellValue('L' . $k, $obj['pingjun_sg']);
            $sg_all += $obj['pingjun_sg'] * $obj['xb_zs'];
            $sheet->setCellValue('M' . $k, $obj['trgxdj']);
            $sheet->setCellValue('N' . $k, $obj['mei_gq_xj']);
            $sheet->setCellValue('O' . $k, $obj['mian_ji']);
            $area += $obj['mian_ji'];
            $sheet->setCellValue('P' . $k, $obj['xb_zs']);
            $zs += $obj['xb_zs'];
            $sheet->setCellValue('Q' . $k, $obj['xb_xj']);
            $vol += $obj['xb_xj'];
            $sheet->setCellValue('R' . $k, $obj['xb_cj']);
            $tvol += $obj['xb_cj'];
            $sheet->setCellValue('S' . $k, $obj['gx_qi_yuan']);
            $sheet->setCellValue('T' . $k, $obj['gx_shuz']);

            $sheet->getRowDimension($k)->setRowHeight(25.5);
        } catch (ErrorException $exception) {

        }
    }
    $last_row = 7 + $data_num;
    $sheet->getRowDimension($last_row)->setRowHeight(25.5);
    $sheet->setCellValue('A' . $last_row, '合计');
    if ($zs > 0) {
        $sheet->setCellValue('K' . $last_row, round(sqrt($xj_all / $zs), 1));
        $sheet->setCellValue('L' . $last_row, round($sg_all / $zs, 1));
    }


    $sheet->setCellValue('N' . $last_row, round($vol / $area));
    $sheet->setCellValue('O' . $last_row, round($area, 2));
    $sheet->setCellValue('P' . $last_row, round($zs));
    $sheet->setCellValue('Q' . $last_row, round($vol));
    $sheet->setCellValue('R' . $last_row, round($tvol));
    $sheet->getStyle("A7:U" . $last_row)->applyFromArray($style);
    $last_row++;
    $sheet->getRowDimension($last_row)->setRowHeight(25.5);
    $sheet->mergeCells('A' . $last_row . ':E' . $last_row);
    $sheet->setCellValue('A' . $last_row, '统计员:' . $user['name']);
    $sheet->mergeCells('H' . $last_row . ':J' . $last_row);
    $sheet->setCellValue('H' . $last_row, '审核人:');
    $sheet->mergeCells('S' . $last_row . ':U' . $last_row);
    $sheet->setCellValue('S' . $last_row, '时间:' . date('Y-m-d'));
    $sheet->getStyle("A7:U" . $last_row)->getAlignment()->setVertical('center')->setHorizontal('center')->setWrapText(true);
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save($write_path);
    return $write_path;
}

/**
 * 生产签到表
 * @param string $path 路径
 * @param array $data 数据
 * @return string
 */
function writeClockExcel(string $path, array $data): string
{

    $model_path = app()->getRootPath() . 'public/model_excel/签到打卡表.xls';
    $write_path = $path;
    $loadReader = PHPExcel_IOFactory::createReader('Excel5');
    $objPHPExcel = $loadReader->load($model_path);
    $sheet = $objPHPExcel->getSheet(0);
    $style = array(
        'borders' => array(
            'allborders' => array( //设置全部边框
                'style' => \PHPExcel_Style_Border::BORDER_THIN //粗的是thick
            ),
        ),
        'font' => array(
            'name' => '宋体',
            'size' => 9),
    );
    $data_num = count($data);
    for ($i = 0; $i < $data_num; $i++) {
        $k = $i + 3;
        $obj = $data[$i];
        try {
            $sheet->setCellValue('A' . $k, $obj['name']);
            $sheet->setCellValue('B' . $k, $obj['clock_time']);
            $sheet->setCellValue('C' . $k, $obj['xian']);
            $sheet->setCellValue('D' . $k, $obj['xiang']);
            $sheet->setCellValue('E' . $k, $obj['cun']);
            $sheet->setCellValue('F' . $k, $obj['lin_ban']);
            $sheet->setCellValue('G' . $k, $obj['xiao_ban']);
            $sheet->getRowDimension($k)->setRowHeight(25.5);
        } catch (ErrorException $exception) {

        }
    }
    $last_row = 3 + $data_num;
    $sheet->getStyle("A3:G" . $last_row)->applyFromArray($style);
    $sheet->getStyle("A3:G" . $last_row)->getAlignment()->setVertical('center')->setHorizontal('center')->setWrapText(true);
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save($write_path);
    return $write_path;
}

/**
 * 导出SHAPEFILE数据
 * @param string $model 模板
 * @param string $path 路径
 * @param array $data 数据
 * @return string
 */
function writeShapefileByModel(string $model, string $path, array $data): string
{
    try {
        $ShapefileReader = new ShapefileReader($model);
        $Shapefile = new ShapefileWriter($path . ".shp");
        $Shapefile->setShapeType(Shapefile::SHAPE_TYPE_POLYGON);
        foreach ($ShapefileReader->getFields() as $name => $field) {
            $Shapefile->addField($name, $field['type'], $field['size'], $field['decimals']);
        }
        foreach ($data as $key => $val) {
            try {
                $polygon = new Polygon();
                $polygon->initFromWKT($val["shape"]);
                foreach ($Shapefile->getFieldsNames() as $fieldsName) {
                    $field_name = strtolower($fieldsName);
                    $polygon->setData($fieldsName, $val[$field_name]);
                }

                $Shapefile->writeRecord($polygon);
            } catch (Exception $exception) {

            }
        }
        $Shapefile = null;
        copy(str_replace('.shp', '.prj', $model), $path . '.prj');
        copy(str_replace('.shp', '.cpg', $model), $path . '.cpg');
        return $path . ".shp";
    } catch (Exception $e) {
        return "";
    }
}

function getExcel($fileName, $headArr, $data)
{
    if (empty($data) || !is_array($data)) {
        die("data must be a array");
    }

    if (empty($fileName)) {
        exit;
    }

    $fileName .= ".xls";
    $objPHPExcel = new \PHPExcel();


    $key = ord("A");
    $key2 = ord("@");
    foreach ($headArr as $v) {
        if ($key > ord("Z")) {
            $key2 += 1;
            $key = ord("A");
            $colum = chr($key2) . chr($key);//超过26个字母时才会启用
        } else {
            if ($key2 >= ord("A")) {
                $colum = chr($key2) . chr($key);//超过26个字母时才会启用
            } else {
                $colum = chr($key);
            }
        }
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum . '1', $v);
        $key += 1;
    }

    $column = 2;
    $objActSheet = $objPHPExcel->getActiveSheet();
    foreach ($data as $key => $rows) { //行写入
        $span = ord("A");
        $span2 = ord("@");
        foreach ($rows as $k => $v) {
            if ($span > ord("Z")) {
                $span2 += 1;
                $span = ord("A");
                $j = chr($span2) . chr($span);
            } else {
                if ($span2 >= ord("A")) {
                    $j = chr($span2) . chr($span);
                } else {
                    $j = chr($span);
                }
            }
            $objActSheet->setCellValue($j . $column, $v);
            $span++;
        }
        $column++;
    }
    $fileName = iconv("utf-8", "gb2312", $fileName);
    header('Content-Type: application/vnd.ms-excel');
    header("Content-Disposition: attachment;filename=\"$fileName\"");
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
    exit;
}

function exportExcel($fileName, $headArr, $Data)
{
    //var_dump(1);
    getExcel($fileName, $headArr, $Data);
}

function importExecl($file)
{
    if (!file_exists($file)) {
        return array("error" => 0, 'message' => 'file not found!');
    }

    // 判断文件是什么格式
    $type = pathinfo($file);
    $type = strtolower($type["extension"]);
    $type = $type === 'csv' ? $type : 'Excel5';
    ini_set('max_execution_time', '0');
    // 判断使用哪种格式
    $objReader = \PHPExcel_IOFactory::createReader($type);
    $objPHPExcel = $objReader->load($file);
    $sheet = $objPHPExcel->getSheet(0);
    // 取得总行数
    $highestRow = $sheet->getHighestRow();
    // 取得总列数
    $highestColumn = $sheet->getHighestColumn();
    //循环读取excel文件,读取一条,插入一条
    $data = array();
    //从第一行开始读取数据
    for ($j = 1; $j <= $highestRow; $j++) {
        //从A列读取数据
        for ($k = 'A'; $k <= $highestColumn; $k++) {
            // 读取单元格
            $data[$j][] = $objPHPExcel->getActiveSheet()->getCell("$k$j")->getValue();
        }
    }
    return $data;
}