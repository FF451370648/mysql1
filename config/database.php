<?php

return [
    // 默认使用的数据库连接配置
    'default' => env('database.driver', 'mysql'),

    // 自定义时间查询规则
    'time_query_rule' => [],

    // 自动写入时间戳字段
    // true为自动识别类型 false关闭
    // 字符串则明确指定时间字段类型 支持 int timestamp datetime date
    'auto_timestamp' => true,

    // 时间字段取出后的默认时间格式
    'datetime_format' => 'Y-m-d H:i:s',

    // 数据库连接配置信息
    'connections' => [
        'mysql' => [
            // 数据库类型
            'type' => env('database.type', 'mysql'),
            // 服务器地址
            'hostname' => "localhost",
            // 数据库名
            'database' => "swl",
            // 用户名
            'username' => "root",
            // 密码
            'password' => "168168",
            // 端口
            'hostport' => "3306",
            // 数据库连接参数
            'params' => [],
            // 数据库编码默认采用utf8
            'charset' => env('database.charset', 'utf8'),
            // 数据库表前缀
            'prefix' => env('database.prefix', ''),
            // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
            'deploy' => 0,
            // 数据库读写是否分离 主从式有效
            'rw_separate' => false,
            // 读写分离后 主服务器数量
            'master_num' => 1,
            // 指定从服务器序号
            'slave_no' => '',
            // 是否严格检查字段是否存在
            'fields_strict' => true,
            // 是否需要断线重连
            'break_reconnect' => false,
            // 监听SQL
            'trigger_sql' => env('app_debug', true),
            // 开启字段缓存
            'fields_cache' => false,
        ],
        'pbsql' => [
            // 数据库类型
            'type' => env('database.type', 'mysql'),
            // 服务器地址
            'hostname' => "117.141.142.232",
            // 数据库名
            'database' => "jczx2",
            // 用户名
            'username' => "jczx",
            // 密码
            'password' => "jczx123456",
            // 端口
            'hostport' => "10100",
            // 数据库连接参数
            'params' => [],
            // 数据库编码默认采用utf8
            'charset' => env('database.charset', 'utf8'),
            // 数据库表前缀
            'prefix' => env('database.prefix', ''),
            // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
            'deploy' => 0,
            // 数据库读写是否分离 主从式有效
            'rw_separate' => false,
            // 读写分离后 主服务器数量
            'master_num' => 1,
            // 指定从服务器序号
            'slave_no' => '',
            // 是否严格检查字段是否存在
            'fields_strict' => true,
            // 是否需要断线重连
            'break_reconnect' => false,
            // 监听SQL
            'trigger_sql' => env('app_debug', true),
            // 开启字段缓存
            'fields_cache' => false,
        ],
        'pgsql' => [
            // 数据库类型
            'type' => env('database.type', 'pgsql'),
            // 服务器地址
            'hostname' => "localhost",
            // 数据库名
            'database' => "sde",
            // 用户名
            'username' => "sde",
            // 密码
            'password' => "haha1992",
            // 端口
            'hostport' => "5432",
            // 数据库连接参数
            'params' => [],
            // 数据库编码默认采用utf8
            'charset' => env('database.charset', 'utf8'),
            // 数据库表前缀
            'prefix' => env('database.prefix', ''),
            // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
            'deploy' => 0,
            // 数据库读写是否分离 主从式有效
            'rw_separate' => false,
            // 读写分离后 主服务器数量
            'master_num' => 1,
            // 指定从服务器序号
            'slave_no' => '',
            // 是否严格检查字段是否存在
            'fields_strict' => true,
            // 是否需要断线重连
            'break_reconnect' => false,
            // 监听SQL
            'trigger_sql' => env('app_debug', true),
            // 开启字段缓存
            'fields_cache' => false,
        ],
        'local_pgsql' => [
            // 数据库类型
            'type' => env('database.type', 'pgsql'),
            // 服务器地址
            'hostname' => "localhost",
            // 数据库名
            'database' => "sde",
            // 用户名
            'username' => "sde",
            // 密码
            'password' => "haha1992",
            // 端口
            'hostport' => "5432",
            // 数据库连接参数
            'params' => [],
            // 数据库编码默认采用utf8
            'charset' => env('database.charset', 'utf8'),
            // 数据库表前缀
            'prefix' => env('database.prefix', ''),
            // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
            'deploy' => 0,
            // 数据库读写是否分离 主从式有效
            'rw_separate' => false,
            // 读写分离后 主服务器数量
            'master_num' => 1,
            // 指定从服务器序号
            'slave_no' => '',
            // 是否严格检查字段是否存在
            'fields_strict' => true,
            // 是否需要断线重连
            'break_reconnect' => false,
            // 监听SQL
            'trigger_sql' => env('app_debug', true),
            // 开启字段缓存
            'fields_cache' => false,
        ],
        'sqlsrv' => [
            // 数据库类型
            'type' => env('database.type', 'sqlsrv'),
            // 服务器地址
            'hostname' => "117.141.142.232",
            // 数据库名
            'database' => "YGGXJZZX",
            // 用户名
            'username' => "sa",
            // 密码
            'password' => "jczx@2021",
            // 端口
            'hostport' => "1433",
            // 数据库连接参数
            'params' => [],
            // 数据库编码默认采用utf8
            'charset' => env('database.charset', 'utf8'),
            // 数据库表前缀
            'prefix' => env('database.prefix', ''),
            // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
            'deploy' => 0,
            // 数据库读写是否分离 主从式有效
            'rw_separate' => false,
            // 读写分离后 主服务器数量
            'master_num' => 1,
            // 指定从服务器序号
            'slave_no' => '',
            // 是否严格检查字段是否存在
            'fields_strict' => true,
            // 是否需要断线重连
            'break_reconnect' => false,
            // 监听SQL
            'trigger_sql' => env('app_debug', true),
            // 开启字段缓存
            'fields_cache' => false,
        ],
        // 更多的数据库配置信息
    ],
];
