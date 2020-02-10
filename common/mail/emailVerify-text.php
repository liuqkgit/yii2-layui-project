<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verify-email', 'token' => $user->verification_token]);
?>
你好 <?= $user->username ?>,

点击下方链接来验证您的邮箱：

<?= $verifyLink ?>
