<?php
/* @var $this yii\web\View */
/* @var $users array */

use yii\helpers\Html;

$this->title = 'User List';
?>
<div class="user-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= Html::encode($user['id']) ?></td>
                <td><?= Html::encode($user['username']) ?></td>
                <td>
                    <?= Html::a('Update', ['update', 'id' => $user['id']], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('Delete', ['delete', 'id' => $user['id']], [
                        'class' => 'btn btn-danger',
                        'data-confirm' => 'Are you sure you want to delete this user?',
                        'data-method' => 'post',
                    ]) ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
