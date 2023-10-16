<?php
/* @var $model app\models\NewsForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<h1>Create News</h1>
<?php $form = ActiveForm::begin(); ?>
<?= $form->field($model, 'title') ?>
<?= $form->field($model, 'content')->textarea() ?>
<div class="form-group">
    <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
</div>
<?php ActiveForm::end(); ?>
