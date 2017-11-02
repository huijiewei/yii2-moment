<?php
/**
 * Created by PhpStorm.
 * User: Huijiewei
 * Date: 2017/7/24
 * Time: 下午5:12
 */

namespace huijiewei\moment;

use yii\web\AssetBundle;

class MomentAsset extends AssetBundle
{
    public $sourcePath = '@huijiewei/moment/assets';

    public $js = [
        'moment.min.js',
    ];
}