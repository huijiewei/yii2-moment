<?php
/**
 * Created by PhpStorm.
 * User: Huijiewei
 * Date: 2017/7/24
 * Time: 下午5:12
 */

namespace huijiewei\moment;

use yii\web\AssetBundle;
use yii\web\View;

class MomentAsset extends AssetBundle
{
    public $sourcePath = '@npm/moment';

    public $locale;
    public $setLocaleOnReady = true;

    public $js = [
        'min/moment.min.js',
    ];

    public $publishOptions = [
        'only' => [
            'min/*',
            'locale/*',
        ]
    ];

    public function init()
    {
        parent::init();

        if ($this->locale === null) {
            $this->locale = \Yii::$app->language;
        }
    }

    public function registerAssetFiles($view)
    {
        parent::registerAssetFiles($view);

        $this->registerLocaleInternal($view);
    }

    public function registerLocaleInternal($view)
    {
        $localeFilePath = $this->tryFindLocale();

        if (!$localeFilePath) {
            return;
        }

        $manager = $view->getAssetManager();

        $view->registerJsFile(
            $manager->getAssetUrl($this, 'locale/' . $this->locale),
            $this->jsOptions,
            'moment-locale-' . $this->locale
        );

        if ($this->setLocaleOnReady) {
            $js = "moment().locale('" . $this->locale . "');";
            $view->registerJs($js, View::POS_READY, 'moment-set-default-locale');
        }
    }

    private function tryFindLocale()
    {
        $localePath = $this->sourcePath . '/locale';

        $localeFile = substr(strtolower($this->locale), 0, 2) . '.js';
        $localeFilePath = "{$localePath}/$localeFile";

        if (!file_exists($localeFilePath)) {
            $localeFile = substr(strtolower($this->locale), 0, 5) . '.js';
            $localeFilePath = "{$localePath}/$localeFile";

            if (!file_exists($localeFilePath)) {
                $localeFile = substr(strtolower($this->locale), 3, 5) . '.js';
                $localeFilePath = "{$localePath}/$localeFile";

                if (!file_exists($localeFilePath)) {
                    return false;
                }
            }
        }

        $this->locale = $localeFile;

        return true;
    }
}
