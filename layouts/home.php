<?php
/**
 * @var MapasCulturais\App $app
 * @var MapasCulturais\Themes\BaseV2\Theme $this
 */
?>
<!DOCTYPE html>
<html lang="<?= $app->currentLCode ?>" dir="ltr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php $this->printDocumentMeta(); ?>
        <title><?= $this->getTitle($entity ?? null) ?></title>
        <link rel="profile" href="//gmpg.org/xfn/11" />
        <link rel="icon" href="<?= $this->asset($app->config['favicon.svg'], false) ?>" type="image/svg+xml">
        <link rel="apple-touch-icon" href="<?= $this->asset($app->config['favicon.180'], false) ?>">
        <?php $this->printPwaHeadTags(); ?>

        <?php $this->printStyles('redemapas-home'); ?>
        <?php $this->printScripts('redemapas-home'); ?>
    </head>

    <body <?php $this->bodyProperties() ?>>
        <?= $TEMPLATE_CONTENT ?>

        <div class="redemapas-pwa-launcher">
            <button type="button" data-redemapas-install hidden style="display:none">
                <?= \MapasCulturais\i::__('Instalar aplicativo') ?>
            </button>
            <?php if (!$app->user->is('guest')): ?>
            <button type="button" data-redemapas-push>
                <?= \MapasCulturais\i::__('Ativar notificações') ?>
            </button>
            <?php endif; ?>
        </div>
    </body>
</html>
