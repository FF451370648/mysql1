<?php

namespace app\controller;

use app\BaseController;
use think\App;
use think\facade\Db;
use think\response\Json;

class Index extends BaseController
{

    public function index(): Json
    {
        return json(Db::table("nian_lin_3")->field("qi_yuan,ling_zu,nian_lin")->select()->toArray());
    }
}