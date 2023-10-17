<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $user app\models\User */

$this->title = $user->username;
?>
    <div class="user-view">

        <h1><?= Html::encode($this->title) ?></h1>

        <?= DetailView::widget([
            'model' => $user,
            'attributes' => [
                'id',
                'username',
            ],
        ]) ?>

    </div>
<?php
