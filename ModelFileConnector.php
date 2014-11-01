<?php

namespace ThePeach\yii2utils;

use Yii;
use yii\base\Model;
use yii\helpers\Html;
use yii\web\UploadedFile;
use \ThePeach\yii2utils\s3\FileUtils;

class ModelFileConnector
{
    /** @var string base upload directory */
    private static $_uploadPath = 'uploads';

    /**
     * Uploads the image and sets its relative path into the $model $attribute.
     *
     * @param Model  $model         the model to use
     * @param string $fromAttribute the attribute to read the file from. Contains an UploadedFile
     * @param string $toAttribute   the attribute where to save the relative file path
     * @param string $fileName      the name of the file without extension (will handled automatically).
     * @param string $subDir        additional subdirectory where to move the file to (must exist!),
     *                              no trailing slash expected.
     */
    public static function uploadAndSetFile(
        Model $model, $fromAttribute, $toAttribute, $fileName, $subDir = null
    ) {
        $file = UploadedFile::getInstance($model, $fromAttribute);
        $filePath = null;

        if ($file !== null) {
            $nameExtension = explode('.', $file->name);
            $ext = end($nameExtension);
            $fileNameExt = $fileName . ".{$ext}";
            $filePath = self::getUploadPath($subDir . DIRECTORY_SEPARATOR . $fileNameExt);

            // TODO check return status
            FileUtils::saveUploadedFile($file, $filePath);

            // get the filename without extension so we can check we are talking about the same file
            $toFilePathArr = explode('.', $model->$toAttribute);
            array_pop($toFilePathArr);
            $toFileName = implode('.', $toFilePathArr);

            // remove the old file if filename is different
            if ($model->$toAttribute
                && $toFileName !== self::getUploadPath($subDir . DIRECTORY_SEPARATOR . $fileName)
            ) {
                // TODO check return status
                FileUtils::delete($model->$toAttribute);
            }
        }
        elseif ($file === null && $model->$toAttribute) {
            // we restore for later assignment
            $filePath = $model->$toAttribute;
        }

        $model->$toAttribute = $filePath;
    }

    /**
     * Takes a file url from an attribute of the model and copies it to be used as another attribute.
     *
     * @param Model  $model          the model to take the information from
     * @param string $fromAttribute  the attribute of the model to copy the file from
     * @param string $toAttributeUrl the attribute that will contain the URL of the copied file
     * @param string $fileName       the name of the new file without extension
     * @param null   $subDir         optional sub directory where to store the copied file
     *                               relative to webroot. No trailing slash
     *
     * @internal param string $type the attribute name that will be used to compose the new image file name
     */
    public static function copyAndSetFile(
        Model $model, $fromAttribute, $toAttributeUrl, $fileName, $subDir = null
    ) {
        // compose the path of the new image
        $nameExtension = explode('.', $model->$fromAttribute);
        $ext = end($nameExtension);
        $toFileName = $fileName . ".{$ext}";
        $toFilePath = self::getUploadPath($subDir . DIRECTORY_SEPARATOR . $toFileName);

        $model->$toAttributeUrl = FileUtils::copyFile($model->$fromAttribute, $toFilePath);
    }

    /**
     * Physically removes the file if existing, and sets the $attribute to null.
     *
     * @param Model  $model
     * @param string $attribute
     * @return bool
     */
    public static function removeUploadedFile(Model $model, $attribute)
    {
        $result = false;

        if ($model->$attribute) {
            $result = FileUtils::delete($model->$attribute);
        }

        $model->$attribute = null;

        return $result;
    }

    /**
     * Return the path of the $uploadsRelPath inside the uploads folder ($uploadPath)
     *
     * @param string $relPath
     *
     * @return string the relative path from the webroot
     */
    public static function getUploadPath($relPath)
    {
        return self::$_uploadPath . DIRECTORY_SEPARATOR . $relPath;
    }

}
