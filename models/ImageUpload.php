<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * Class ImageUpload
 * @package app\models
 */
class ImageUpload extends Model
{
    public $image;

    public function rules()
    {
        return [
            [['image'], 'required'],
            [['image'], 'file', 'extensions' => 'jpg, png']
        ];
    }

    /**
     * @param UploadedFile $file
     * @param $currentImageName
     * @return string
     */
    public function uploadFile(UploadedFile $file, $currentImageName)
    {
        $this->image = $file;

        if ($this->validate()) {
            $this->deleteCurrentImage($currentImageName);

            return $this->saveImage();
        }
    }

    /**
     * @return string
     */
    private function getFolder()
    {
        return Yii::getAlias('@web') . 'uploads/';
    }

    /**
     * @return string
     */
    private function generateFilename()
    {
        return strtolower(md5(uniqid($this->image->baseName)) . '.' . $this->image->extension);
    }

    /**
     * @param $currentImageName
     */
    public function deleteCurrentImage($currentImageName)
    {
        if ($this->fileExists($currentImageName)) {
            unlink($this->getFolder() . $currentImageName);
        }
    }

    /**
     * @param $currentImageName
     * @return bool
     */
    public function fileExists($currentImageName)
    {
        if (!empty($currentImageName) && $currentImageName != null) {
            return file_exists($this->getFolder() . $currentImageName);
        }
    }

    /**
     * @return string
     */
    public function saveImage()
    {
        $filename = $this->generateFilename();

        $this->image->saveAs($this->getFolder() . $filename);

        return $filename;
    }
}