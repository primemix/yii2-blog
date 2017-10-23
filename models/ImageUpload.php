<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

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

    public function uploadFile(UploadedFile $file, $currentImageName)
    {
        $this->image = $file;

        if ($this->validate()) {
            $this->deleteCurrentImage($currentImageName);

            return $this->saveImage();
        }
    }

    private function getFolder()
    {
        return Yii::getAlias('@web') . 'uploads/';
    }

    private function generateFilename()
    {
        return strtolower(md5(uniqid($this->image->baseName)) . '.' . $this->image->extension);
    }

    public function deleteCurrentImage($currentImageName)
    {
        if ($this->fileExists($currentImageName)) {
            unlink($this->getFolder() . $currentImageName);
        }
    }

    public function fileExists($currentImageName)
    {
        if (!empty($currentImageName) && $currentImageName != null) {
            return file_exists($this->getFolder() . $currentImageName);
        }
    }

    public function saveImage()
    {
        $filename = $this->generateFilename();

        $this->image->saveAs($this->getFolder() . $filename);

        return $filename;
    }
}