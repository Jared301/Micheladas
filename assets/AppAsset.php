<?php
namespace app\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    /**
     * The directory that contains the asset files to be published.
     * @var string
     */
    public $basePath = '@webroot';

    /**
     * The base URL for the relative asset files listed below.
     * @var string
     */
    public $baseUrl = '@web';

    /**
     * List of CSS files that this bundle contains.
     * @var array
     */
    public $css = [
        'css/site.css',
    ];

    /**
     * List of JavaScript files that this bundle contains.
     * @var array
     */
    public $js = [
    ];

    /**
     * List of bundle class names that this bundle depends on.
     * @var array
     */
    public $depends = [
        'yii\web\YiiAsset',
        // Bootstrap 3 (no 5)
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
