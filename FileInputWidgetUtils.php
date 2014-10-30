<?php

namespace ThePeach;

use Yii;
use yii\base\Model;
use yii\helpers\Html;
use \ThePeach\s3\FileUtils;

/**
 * Class FileInputWidgetUtils
 * Basic class to interact with the fileInputWidget.
 * Add either the first or the second package to use this helper
 * "require": {
 *     "kartik-v/bootstrap-fileinput": "~2.3.0",
 *     "kartik-v/yii2-widgets": "*"
 * }
 *
 * @package ThePeach
 */
class FileInputWidgetUtils
{
    /**
     * Creates the array of options for the FileInput Widget.
     * Sets the initialPreview of the image.
     *
     * @param Model  $model     the model
     * @param string $attribute the attribute of the model to take the information from
     *
     * @return array
     */
    public static function getFileInputWidgetOptions(Model $model, $attribute)
    {
        $options['options'] = ['accept' => 'image/*'];
        $options['pluginOptions'] = ['showUpload' => false];

        if ($model->$attribute) {
            $options['pluginOptions']['initialPreview'] = [
                Html::img(
                    Utils::isRemoteUrl($model->$attribute)
                        ? $model->$attribute
                        : fileUtils::getFileUrl($model->$attribute, true),
                    [
                        'class'=>'file-preview-image',
                        'alt'=>$model->$attribute,
                        'title'=>$model->$attribute
                    ])
            ];
            $options['pluginOptions']['initialCaption'] = $model->$attribute;
        }

        return $options;
    }
}