<?php

namespace app\controller;

use app\BaseController;
use think\App;
use think\facade\Db;
use think\response\Json;
use Tree\biomass;
use Tree\Math;

class Swl2022 extends BaseController
{
    private $count;

    public function format_shu_zhong()
    {
        $shu_zhong = Db::table("lq_ym_data_ys")->field("species")->group("species")->select()->each(function ($item) {
            $code = trim($item["species"]);
            if ($code != "0") {
                $value = Db::table("lq_dic_pub")->where("code", $code)->value("value");
                if (empty($value)) $value = "其他硬阔";
                $save["shu_zhong"] = $value;
                Db::table("lq_ym_data_ys")->where($item)->save($save);
            }
        });
    }

    public function format_plot_xian()
    {
        Db::table("lq_yd_data_ys")->field("plot_no,auth_code as xian")->select()->each(function ($item) {
            $where["plot_no"] = $item["plot_no"];
            Db::table("lq_ym_data_ys")->where($where)->save($item);
        });
    }

    public function format_dbh()
    {
        Db::table("lq_ym_data_ys")->field("id,o_dbh")->select()->each(function ($item) {
            $where["id"] = $item["id"];
            $save["o_dbh"] = round($item["o_dbh"] / 10, 1);
            Db::table("lq_ym_data_ys")->where($where)->save($save);
        });
    }

    public function test_excel()
    {
        $this->count = Db::table("lq_ym_data_ys")->whereIn("tally_type", "10,11,12,16,18,19,20")->count();
        $data = Db::table("lq_ym_data_ys")->whereIn("tally_type", "10,11,12,16,18,19,20")->field("shu_zhong,count(id) as count")->group("shu_zhong")->order("count desc")->select()->each(function ($item) {

            $name = $item["shu_zhong"];
            $name = explode("/", $name);
            $where[] = array("ji_mi_du", ">", 0);
            $where[] = array("name", "in", $name);
            $results = Db::table("density")->where($where)->field("zhu_shu,ji_mi_du")->select()->toArray();
            if (!empty($results)) {
                $count = 0;
                $mi_du = 0;
                foreach ($results as $result) {
                    $count += $result["zhu_shu"];
                    $mi_du += $result["zhu_shu"] * $result["ji_mi_du"];
                }
                $item["mi_du"] = round($mi_du / $count, 4);
                $item["zhu_shu"] = $count;
            } else {
                $item["mi_du"] = "";
                $item["zhu_shu"] = "";
            }
            $where = array();
            $where["value"] = $item["shu_zhong"];
            $item["code"] = Db::table("lq_dic_pub")->where($where)->value("code");
            $item["rate"] = round(100 * $item["count"] / $this->count, 2);
            return $item;
        })->toArray();
        //return json($data);
        exportExcel("树种", array(), $data);
    }

    public function math_gan_zhi()
    {
        $data = Db::table("biomass")->field("name,pingjun_xj,qmc_shu_gan,qmc_di_shang")->order("name,pingjun_xj")->select()->each(function ($item) {
            if ($item["qmc_di_shang"] > 0) $item["bili"] = $item["qmc_shu_gan"] / $item["qmc_di_shang"];
            return $item;
        })->toArray();
        exportExcel("树干比例", array("名称", "胸径", "树干生物量", "地上生物量", "比例"), $data);
    }

    public function format_ym_sg()
    {
        Db::table("ym_sg")->field("code")->group("code")->select()->each(function ($item) {
            $map["code"] = $item["code"];
            $map["name"] = "样木树种";
            $save["name"] = Db::table("lq_dic_pub")->where($map)->value("value");
            Db::table("ym_sg")->where($item)->save($save);

        });
    }

    public function setNeed()
    {
        $map[] = array("zhan_bi", ">", "0.32");
        $data = Db::table("swl_lq_sz_2015")->where($map)->field("name")->select()->each(function ($item) {
            return $item["name"];
        })->toArray();
        $save["need"] = "1";
        Db::table("ym_sg")->whereIn("name", $data)->save($save);
    }

    public function mathNeed()
    {
        $data = Db::table("ym_sg")->where("need", "1")->select()->each(function ($item) {
            if (str_contains($item["name"], "栎")) {
                $item["name"] = "栎树";
            }
            $tree_math = new Math();
            $save["xu_ji"] = $tree_math->mathTreeVolByTwo($item["name"], $item["dbh"], $item["th"]);
            $save["ds_swl"] = $tree_math->mathTreeSwlHeightByTwo($item["name"], $item["dbh"], $item["th"]);
            $save["dx_swl"] = $tree_math->mathTreeSwlLowByTwo($item["name"], $item["dbh"], $item["th"]);
            $save["swl"] = $save["ds_swl"] + $save["dx_swl"];
            Db::table("ym_sg")->where("id", $item["id"])->save($save);
        })->toArray();
    }

    /**
     * 生物量一元模型接口
     * @return Json
     */
    public function getSwlLineByDbh(): Json
    {
        $type = request()->param("type");
        $spe = request()->param("spe");
        $low = request()->param("low");
        $top = request()->param("top");
        $scale = request()->param("scale");
        $bio = new biomass();
        $line_data = array();
        for ($i = $low; $i < $top; $i += $scale) {
            $tmp = array();
            $tmp[] = $i;
            $tmp[] = $bio->getBiomassByDbh($type, $spe, $i);
            $line_data [] = $tmp;
        }
        return json(array("state" => 1, "data" => $line_data));
    }
}