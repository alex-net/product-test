<?php 

namespace app\modules\admin\assets;

class UploadImagesWidgetAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@app/modules/admin/front';
    public $js = ['upload-images-widdget-asset.js'];
    public $depends = [\yii\web\YiiAsset::class];
}