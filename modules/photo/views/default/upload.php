<?php
use yii\widgets\ActiveForm;

$this->title = 'Upload multiple';
$this->params['breadcrumbs'][] = ['label' => 'Photos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// show loading icon
$this->registerJs('
$("document").ready(function(){ 
    $("#uploadImage").click(function() {
        if ($("#uploadform-imagefiles")[0].files.length > 0) { 
            $("#loading").css("display", "block");
        }
    });
});
');
?>

<div class="photo-index">
    Maximum 8 files can be uploaded at a time.
    <br />
    Available image extensions: <u>png, jpg, jpeg, gif</u>
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
    <?= $form->field($model, 'imageFiles[]')->fileInput(['multiple' => true, 'accept' => 'image/*']) ?>
    <button id="uploadImage">Загрузить</button>
    <?php ActiveForm::end() ?>
</div>

<img src="/img/loading.gif" id="loading" style="display: none;" />
