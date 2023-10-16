<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Register User';
?>
<div class="user-register">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin([
        'action' => ['user/register'],
        'method' => 'post',
    ]); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true, 'name' => 'username']) ?>
    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true, 'name' => 'password']) ?>
    <?= $form->field($model, 'confirmPassword')->passwordInput(['maxlength' => true, 'name' => 'confirmPassword']) ?>

    <div class="form-group">
        <?= Html::submitButton('Register', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
