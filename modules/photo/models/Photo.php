<?php

namespace app\modules\photo\models;

use Yii;
use dektrium\user\models\User;

/**
 * This is the model class for table "photo".
 *
 * @property int $id
 * @property string $title
 * @property string $body
 * @property int $user_id
 * @property string $img_path
 * @property string $img_hash
 *
 * @property User $user
 */
class Photo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'photo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['body', 'img_path', 'img_hash'], 'string'],
            [['user_id'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'body' => 'Body',
            'user_id' => 'User',
            'img_path' => 'Photo',
            'img_hash' => 'Image hash',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * {@inheritdoc}
     * @return PhotoQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PhotoQuery(get_called_class());
    }


    /**
     * Preparing data for insertion into a photo table
     * @param object $uploadModel UploadForm
     * @return array [title, user_id, img_path, img_hash]
     */
    public static function buildPhotoCollection($uploadModel) {
        $images = [];
        $hashes = [];

        foreach ($uploadModel->imageFiles as $key => $file) {
            $fileTitle = current(explode('.', $file->baseName));
            $imgPath = basename($file->tempName) . '.' . $file->extension;
            $imgHash = md5_file($file->tempName);

            // Checking for existing files (in a file array from the user)
            if (count($hashes) > 0 && in_array($imgHash, $hashes)) {
                unset($uploadModel->imageFiles[$key]);
                continue;
            }

            $hashes[] = $imgHash;

            // Check for file existence
            $existPhoto = self::find()->where(['img_hash' => $imgHash])->one();
            if ($existPhoto) {
                $imgPath = $existPhoto->img_path;
            }

            $images[] = [
                $fileTitle,
                Yii::$app->user->id,
                $imgPath,
                $imgHash
            ];
        }

        return $images;
    }
}
