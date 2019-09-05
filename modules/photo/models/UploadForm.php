<?php

namespace app\modules\photo\models;

use yii\base\Model;
use yii\web\UploadedFile;
use app\modules\photo\models\Photo;

class UploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $imageFiles;
    const IMAGE_PATH = 'uploads/';

    public function rules()
    {
        return [
            [['imageFiles'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg, gif', 'maxFiles' => 8],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'imageFiles' => 'Photos',
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            foreach ($this->imageFiles as $file) {
                $imgHash = md5_file($file->tempName);

                // Upload only unique image
                if (!Photo::find()->where(['img_hash' => $imgHash])->one()) {
                    $file->saveAs(self::IMAGE_PATH . basename($file->tempName) . '.' . $file->extension);
                }
            }
            return true;
        } else {
            return false;
        }
    }
}