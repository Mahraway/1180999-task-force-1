<?php
/**
 * @var object $task Данные задачи
 * @var object $responseForm Форма добавления отклика
 * @var object $completionForm Модель формы завершения задачи
 */

use frontend\models\TasksFiles;
use taskforce\Task;
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\models\Files;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

?>

<div class="main-container page-container">
    <section class="content-view">
        <div class="content-view__card">
            <div class="content-view__card-wrapper">
                <div class="content-view__header">
                    <div class="content-view__headline">
                        <h1><?= $task->name ?></h1>
                        <span>Размещено в категории
                            <a href="<?= Url::to(['tasks/index', 'TaskFilterForm' => ['category_ids' => $task->category_id]]) ?>"
                               class="link-regular">
                                <?= $task->category->name ?>
                            </a>
                            <?= date('Y-m-d', strtotime($task->dt_add)) ?>
                        </span>
                    </div>
                    <b class="new-task__price new-task__price--clean content-view-price"><?= $task->cost ?><b> ₽</b></b>
                    <div class="new-task__icon new-task__icon--clean content-view-icon"></div>
                </div>
                <div class="content-view__description">
                    <h3 class="content-view__h3">Общее описание</h3>
                    <p>
                        <?= $task->description ?>
                    </p>
                </div>
                <div class="content-view__attach">
                    <h3 class="content-view__h3">Вложения</h3>

                    <?php
                    $taskFiles = TasksFiles::find()
                        ->where(['task_id' => $task->id])
                        ->select('file_id')->column();
                    foreach ($taskFiles as $file_id) {
                        print Html::a(
                            Files::findOne(['id' => $file_id])->name,
                            Url::base() . '/' . Files::findOne(['id' => $file_id])->path,
                            ['target' => '_blank']
                        );
                    }
                    ?>
                </div>
                <div class="content-view__location">
                    <h3 class="content-view__h3">Расположение</h3>
                    <div class="content-view__location-wrapper">
                        <div class="content-view__map">
                            <a href="#"><img src="../img/map.jpg" width="361" height="292"
                                             alt="Москва, Новый арбат, 23 к. 1"></a>
                        </div>
                        <div class="content-view__address">
                            <span class="address__town">Москва</span><br>
                            <span>Новый арбат, 23 к. 1</span>
                            <p>Вход под арку, код домофона 1122</p>
                        </div>
                    </div>
                </div>
            </div>
            <?php if (!Yii::$app->user->isGuest): ?>
            <div class="content-view__action-buttons">

                    <?php if ($task->status === Task::STATUS_NEW || $task->status === Task::STATUS_IN_WORK): ?>

                        <?php if ($task->status != Task::STATUS_IN_WORK || $task->status === Task::STATUS_NEW): ?>
                            <button class=" button button__big-color response-button open-modal"
                                    type="button" data-for="response-form">Откликнуться
                            </button>
                        <?php endif; ?>
                        <?php if (Yii::$app->user->identity->getId() === $task->user_id): ?>
                            <?php if ($task->status != Task::STATUS_IN_WORK): ?>
                                <button class="button button__big-color refusal-button open-modal"
                                        type="button" data-for="refuse-form">Отказаться
                                </button>
                            <?php endif; ?>
                            <button class="button button__big-color request-button open-modal"
                                    type="button" data-for="complete-form">Завершить
                            </button>
                        <?php endif; ?>
                    <?php endif; ?>
            </div>
        </div>

        <?php

        if ($task->status === Task::STATUS_NEW) {
            if (count($task->responses) > 0 && $task->user_id === Yii::$app->user->identity->getId()) {
                print $this->render('_responses', [
                    'task' => $task
                ]);
            }
        }
        ?>
        <?php endif;?>

    </section>
    <section class="connect-desk">
        <div class="connect-desk__profile-mini">
            <div class="profile-mini__wrapper">
                <h3>Заказчик</h3>
                <div class="profile-mini__top">
                    <img src="../img/man-brune.jpg" width="62" height="62" alt="Аватар заказчика">
                    <div class="profile-mini__name five-stars__rate">
                        <p><?= $task->user->name ?></p>
                    </div>
                </div>
                <p class="info-customer"><span>12 заданий</span><span class="last-">2 года на сайте</span></p>
                <a href="<?= Url::to(['users/view', 'id' => $task->user->id]) ?>" class="link-regular">Смотреть
                    профиль</a>
            </div>
        </div>
        <div id="chat-container">
            <!--                    добавьте сюда атрибут task с указанием в нем id текущего задания-->
            <chat class="connect-desk__chat"></chat>
        </div>

        <section class="modal response-form form-modal" id="response-form">

            <?= $this->render('responseForm', [
                'responseForm' => $responseForm
            ])

            ?>

        </section>
        <section class="modal completion-form form-modal" id="complete-form">

            <?= $this->render('_completeForm', [
                'completionForm' => $completionForm
            ]) ?>

        </section>
        <section class="modal form-modal refusal-form" id="refuse-form">
            <?= $this->render('_refuseForm', [
                'task' => $task
            ]) ?>
        </section>
</div>