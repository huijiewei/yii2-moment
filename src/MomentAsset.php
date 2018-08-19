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
    public $sourcePath = '@npm/moment';

    public $js = [
        'min/moment.min.js',
    ];

    public $publishOptions = [
        'only' => [
            'min/',
            'locale/',
        ]
    ];

    public function registerAssetFiles($view)
    {
        $locale = strtolower(\Yii::$app->language);

        if ($locale != 'en') {
            $sourcePath = \Yii::getAlias($this->sourcePath);

            $desiredFile = $this->getDesiredFile($sourcePath, $locale);

            if (!is_file($desiredFile)) {
                $fallbackLocale = substr($locale, 0, 2);

                if ($fallbackLocale != 'en') {
                    $desiredFile = $this->getDesiredFile($sourcePath, $fallbackLocale);

                    if (file_exists($desiredFile)) {
                        $locale = $fallbackLocale;
                    }
                }
            }

            if ($locale != 'en') {
                $this->js[] = 'locale/' . $locale . '.js';
            }
        }

        parent::registerAssetFiles($view);

        if ($locale != 'en') {
            $js = "moment.locale('{$locale}')";

            $view->registerJs($js, View::POS_READY, 'moment-locale-' . $locale);
        }
    }

    private function getDesiredFile($sourcePath, $locale)
    {
        return $sourcePath . DIRECTORY_SEPARATOR . 'locale' . DIRECTORY_SEPARATOR . "{$locale}.js";
    }
}
