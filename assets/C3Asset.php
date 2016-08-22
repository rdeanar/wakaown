<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

class C3Asset extends AssetBundle
{
    public $basePath = '@webroot/assets';
    public $sourcePath = '@bower';

    public $css = [
        'c3/c3.css',
    ];
    public $js = [
        'd3/d3.min.js',
        'c3/c3.min.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
