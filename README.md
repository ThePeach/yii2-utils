# Yii2 Utils

This package contains some helpers.

## Utils

This class contains some static helpers:

### Quick list of methods

* `decodeBadHtmlEntities` - converts HTML entities into their correspondent UTF-8 character, and will also try to convert entities with a missing semicolon. To be use with caution.
* `isRemoteUrl` - checks if a given URL is remote or local.

## FileInputWidgetHelper

This class provides a quick way to generate the configuration for the package `kartik-v/bootstrap-fileinput` (currently supports only up to version 2.3).

### Quick list of methods

* `getFileInputWidgetOptions` - takes a model and one of its attributes where you want to use the file input widget on. It will return a configuration with a default preview using the already set attribute value, is available.

## ModelFileConnector

This class depends on the `s3\FileUtils`.

This class provides utilities to work with ActiveRecord models and their associated files to be uploaded.

The model needs to have:

* a path attribute, corresponding to a db column in the model table
* a virtual attribute, representing the actual file that will uploaded and saved.

The methods will automatically take care, through the use of the `s3\FileUtils`, of saving the file locally or on S3 (if configured) and to save the resulting path in the model's path attribute. Files will be stored into a `/uploads` directory (relative to the `/web` directory) and you can optionally specify any additional subpaths you need.

### Quick list of methods

* `uploadAndSetFile` - Uploads the image and sets its relative path into the $model $attribute.
* `copyAndSetFile` - Takes a file url from an attribute of the model and copies it to be used as another attribute.
* `removeUploadedFile` - Physically removes the file if existing, and sets the $attribute to null.
* `getUploadPath` - Return the path of the `$uploadsRelPath` parameter inside the uploads folder.

## s3\FileUtils

A simple class to deal with managing files in a local or s3 remote file system. Everything is done in a transparent way and it will automatically use s3 by default and fall back to the local file system if no s3 is configured and found.

### Quick list of methods

* `saveUploadedFile` - Saves an UploadedFile in the required position. Deals with `UploadedFile` files.
* `delete` - Deletes the file from the appropriate place.
* `copyFile` - Copy an image inside the $uploadPath from -> to the relative file path.
* `getFileUrl` - Returns the path of the file, either on S3 if configured or locally.

# Support

For help, support and bugs, please use the issue tracker.

Pull requests are encouraged and will be evaluated.

