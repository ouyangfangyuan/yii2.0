<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'urlManager'=>[
        	'enablePrettyUrl'=>true,//开启美化
			'showScriptName'=>false,//关闭脚本文件
			'suffix'=>'.html',
        ],
    ],
];
