<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\photo\models\PhotoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Photos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="photo-index">

<?=Html::beginForm(['delete-multiple'],'post');?>

    <div style="float: right">
        <?= Html::submitButton('Delete selected', [
            'class' => 'btn btn-success',
            'data' => [
                'confirm' => 'Are you sure?',
            ],
        ]) ?>
    </div>

    <?= Html::a('Upload', ['upload'], ['class' => 'btn btn-danger']) ?>

<? #TODO стили перенести в style.css ?>
    <div style="clear: both; margin-top: 10px;"></div>

    <?= GridView::widget([
        'tableOptions' => ['class'=> 'table table-striped'],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'title',
            'body:ntext',
            [
                'attribute' => 'img_path',
                'format' => 'html',
                'value' => function ($data) {
                    return Html::img('/uploads/'. $data['img_path'], ['width' => '70px']);
                },
            ],

            ['class' => 'yii\grid\ActionColumn'],
            ['class' => 'yii\grid\CheckboxColumn'],
        ],
    ]); ?>

<?= Html::endForm();?>

</div>
