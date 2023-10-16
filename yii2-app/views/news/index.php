<?php
/* @var $news array */

use yii\helpers\Html;
use yii\helpers\Url;

?>
<h1>News</h1>
<table class="table">
    <thead>
    <tr>
        <th>Title</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($news as $item): ?>
        <tr>
            <td><?= Html::encode($item['title']) ?></td>
            <td>
                <?= Html::a('View', Url::to(['news/view', 'id' => $item['id']])) ?>
                <?= Html::a('Edit', Url::to(['news/update', 'id' => $item['id']])) ?>
                <?= Html::a('Delete', Url::to(['news/delete', 'id' => $item['id']]), [
                    'data-method' => 'post',
                    'data-confirm' => 'Are you sure?',
                ]) ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
