<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\photo\models\Photo */

$this->title = 'Update photo info: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Photos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update photo description';
?>
<div class="photo-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
