<?php

/**
 * @var Notifications $notifications
 * @var Notifications $notification
 * @var float|int $score
 */

use app\models\Notifications;
use yii\helpers\Url;

?>

<div class="header__lightbulb" style="
<?php if (!empty($notifications)): ?>
        background-image: url('/img/lightbulb-white.png')
<?php endif; ?>
        "></div>
<div class="lightbulb__pop-up">
    <h3>Новые события</h3>
    <?php if (!empty($notifications)): ?>
        <?php foreach ($notifications as $notification): ?>
            <p class="lightbulb__new-task lightbulb__new-task<?= $notification->icon; ?>">
                <?= $notification->title; ?>
                <a href="<?= Url::to('/task/' . $notification->task_id) ?>" class="link-regular">
                    «<?= $notification->description; ?>»</a>
            </p>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="lightbulb__new-task no-notice">

            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bell-slash" viewBox="0 0 16 16">
                <path d="M5.164 14H15c-.299-.199-.557-.553-.78-1-.9-1.8-1.22-5.12-1.22-6 0-.264-.02-.523-.06-.776l-.938.938c.02.708.157 2.154.457 3.58.161.767.377 1.566.663 2.258H6.164l-1 1zm5.581-9.91a3.986 3.986 0 0 0-1.948-1.01L8 2.917l-.797.161A4.002 4.002 0 0 0 4 7c0 .628-.134 2.197-.459 3.742-.05.238-.105.479-.166.718l-1.653 1.653c.02-.037.04-.074.059-.113C2.679 11.2 3 7.88 3 7c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0c.942.19 1.788.645 2.457 1.284l-.707.707zM10 15a2 2 0 1 1-4 0h4zm-9.375.625a.53.53 0 0 0 .75.75l14.75-14.75a.53.53 0 0 0-.75-.75L.625 15.625z"/>
            </svg>
            Нет новых уведомлений
        </p>
    <?php endif; ?>
</div>