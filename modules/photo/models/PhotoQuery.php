<?php

namespace app\modules\photo\models;

/**
 * This is the ActiveQuery class for [[Photo]].
 *
 * @see Photo
 */
class PhotoQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Photo[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Photo|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
