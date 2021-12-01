<?php
/**
 * @var $user Users пользователь
 * @var $specializations UsersCategories специализации
 * @var $dataProvider \yii\data\ActiveDataProvider фотографии для портфолио
 */

use frontend\models\Users;
use frontend\models\UsersCategories;
use frontend\widgets\ageFormatter\AgeFormatter;
use frontend\widgets\executorInfo\ExecutorInfo;
use frontend\widgets\rating\RatingWidget;
use frontend\widgets\timeFormatter\TimeFormatterWidget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\widgets\ListView;
use yii\widgets\Pjax;

?>

<div class="main-container page-container">
    <section class="content-view">
        <div class="user__card-wrapper">
            <div class="user__card">
                <img src="<?= $user->avatarFile->path ?? '/img/no-photos.png' ?>" width="120" height="120" alt="Аватар пользователя">
                <div class="content-view__headline">
                    <h1><?= $user->name ?></h1>
                    <p>
                        <?= $user->city->name ?>
                        <?= $user->birthday ? ', ' . AgeFormatter::widget(['birthday' => $user->birthday]): ''?>
                    </p>
                    <div class="profile-mini__name five-stars__rate">
                        <?= RatingWidget::widget(['rating' => $user->calcRatingScore()]) ?>
                    </div>
                        <?= ExecutorInfo::widget(['id' => $user->id]) ?>
                </div>
                <div class="content-view__headline user__card-bookmark user__card-bookmark--current">
                    <span><?= TimeFormatterWidget::widget([
                            'time' => $user->last_active_time,
                            'format' => TimeFormatterWidget::USER_FORMAT
                        ])?></span>
                    <a href="#"><b></b></a>
                </div>
            </div>
            <div class="content-view__description">
                <p><?= $user->about_me ?></p>
            </div>
            <div class="user__card-general-information">
                <div class="user__card-info">
                    <h3 class="content-view__h3">Специализации</h3>
                    <div class="link-specialization">
                        <?php foreach($user->categories as $spec): ?>
                            <?= Html::a($spec->category->name,
                                    Url::to(['tasks/index', 'TaskFilterForm' => [
                                        'category_ids' => $spec->category_id
                                    ]]), [
                                            'class' => 'link-regular'
                                ]
                            ) ?>

                        <?php endforeach; ?>
                    </div>
                    <h3 class="content-view__h3">Контакты</h3>
                    <div class="user__card-link">
                        <a class="user__card-link--tel link-regular" href="tel:7<?= $user->phone ?>">
                            +7 <?= $user->phone ?>
                        </a>
                        <a class="user__card-link--email link-regular" href="mailto:<?= $user->email ?>"><?= $user->email ?></a>
                        <a class="user__card-link--skype link-regular" href="#"><?= $user->skype ?></a>
                        <a class="user__card-link--skype link-regular" href="#"><?= $user->skype ?></a>
                    </div>
                </div>
                <div class="user__card-photo">
                    <h3 class="content-view__h3">Фото работ</h3>

                    <?php Pjax::begin() ?>
                    <?= ListView::widget([
                        'dataProvider' => $dataProvider,
                        'layout' => "{items}",
                        'itemView' => '_img',
                        'options' => [
                            'style' => [
                                'width' => '100%',
                                'display' => 'flex',
                                'flex-direction' => 'inherit',
                                'justify-content' => 'flex-start',
                                'align-items' => 'center',
                                'margin-bottom' => '20px'
                            ]
                        ]
                    ]) ?>
                    <?=
                    LinkPager::widget([
                        'pagination' => $dataProvider->getPagination(),
                        'options' => [
                                'class' => 'pagination',
                                'style' => [
                                        'display' => 'flex',
                                    'justify-content' => 'end'
                                ]
                        ]
                    ]);
                    ?>
                    <?php Pjax::end() ?>

<!--                    <a href="#"><img src="../img/rome-photo.jpg" width="85" height="86" alt="Фото работы"></a>-->
<!--                    <a href="#"><img src="../img/smartphone-photo.png" width="85" height="86" alt="Фото работы"></a>-->
<!--                    <a href="#"><img src="../img/dotonbori-photo.png" width="85" height="86" alt="Фото работы"></a>-->
                </div>
            </div>
        </div>
        <div class="content-view__feedback">
            <h2>Отзывы<span> (<?= count($user->reviewsByExecuted) ?>)</span></h2>
            <div class="content-view__feedback-wrapper reviews-wrapper">

                <?php foreach ($user->getReviewsByExecuted()->all() as $review): ?>
                    <div class="feedback-card__reviews">
                        <p class="link-task link">Задание
                            <a href="<?= Url::to(['tasks/view', 'id' => $review->id]) ?>" class="link-regular">
                                <?= $review->task->name ?></a></p>
                        <div class="card__review">
                            <a href="">
                                <img src="<?= $review->user->avatarFile->path ?? '/img/no-photos.png' ?>" width="55" height="54">
                            </a>
                            <div class="feedback-card__reviews-content">
                                <p class="link-name link"><a href="<?= Url::to(['users/view', 'id' => $review->user_id])?>" class="link-regular">
                                        <?= $review->user->name ?></a></p>
                                <p class="review-text">
                                    <?= $review->text ?? ''?>
                                </p>
                            </div>
                            <div class="card__review-rate">
                                <p class="five-rate big-rate">
                                    <?= $review->score ? $review->score. '<span></span>' : ''?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>
    </section>
    <section class="connect-desk">
        <div class="connect-desk__chat">

        </div>
    </section>
</div>