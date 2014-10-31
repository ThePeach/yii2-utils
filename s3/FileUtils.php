<?php

namespace ThePeach\yii2utils\s3;

use Yii;
use yii\web\UploadedFile;
use \ThePeach\yii2utils\Utils;

class FileUtils
{
    /**
     * Saves an UploadedFile in the required position.
     *
     * @param UploadedFile $file     the file that has been uploaded
     * @param string       $filePath the full path to the new file position
     * @return bool status of the operation
     */
    public static function saveUploadedFile(UploadedFile $file, $filePath)
    {
        if (Yii::$app->has('storage')) {
            // upload to S3 if the component is available.
            $result = Yii::$app->storage->uploadFile(
                $file->tempName,
                DIRECTORY_SEPARATOR . $filePath
            );
        }
        else {
            $result = $file->saveAs(Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . $filePath);
        }

        return $result;
    }

    /**
     * Deletes the file from the appropriate place.
     *
     * @param string $filePath the full path to the file to be deleted
     * @return bool status of the operation
     */
    public static function delete($filePath)
    {
        if (!Utils::isRemoteUrl($filePath)) {

            if (Yii::$app->has('storage')) {
                $result = Yii::$app->storage->deleteFile(
                    DIRECTORY_SEPARATOR . $filePath
                );
            }
            else {
                if (file_exists(
                    Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . $filePath
                )) {
                    $result = unlink(
                        Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . $filePath
                    );
                }
                else {
                    // file does not exist, as nothing happened
                    $result = true;
                }
            }
        }
        else {
            // is a remote URL we can't do anything
            $result = true;
        }

        return $result;
    }

    /**
     * Copy an image inside the $uploadPath from -> to the relative file path
     *
     * @param string $fromFilePath absolute path of the file to be copied
     * @param string $toFilePath relative path inside $uploadPath
     *
     * @return null|string
     */
    public static function copyFile($fromFilePath, $toFilePath)
    {
        $res = null;

        if (!Utils::isRemoteUrl($fromFilePath)) {

            if (Yii::$app->has('storage')) {
                // use Yii::$app->storage->copyObject([...]);
            }
            elseif (
                file_exists(
                    Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . $fromFilePath
                )
                && copy(
                    Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . $fromFilePath,
                    Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . $toFilePath
                )
            ) {
                $res = $toFilePath;
            }
        }

        return $res;
    }

    /**
     * Returns the path of the file, either on S3 if configured or locally.
     *
     * @param string $filePath the path to the file, no base slash needed
     * @param bool   $absolute whether to return the absolute local path
     * @return string
     */
    public static function getFileUrl($filePath, $absolute = false)
    {
        $url = null;

        if (Utils::isRemoteUrl($filePath)) {
            // meh
            $url = $filePath;
        }
        elseif ( Yii::$app->has('storage')) {
            $url = 'http://' . Yii::$app->storage->bucket . DIRECTORY_SEPARATOR . $filePath;
        }
        else {
            $url = (($absolute) ? Yii::getAlias('@web') . DIRECTORY_SEPARATOR : '') . $filePath;
        }

        return $url;
    }
}
