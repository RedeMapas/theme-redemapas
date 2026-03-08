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

        <?php $this->printJsObject(); ?>
        <?php $this->printStyles('redemapas-home'); ?>
        <?php $this->printScripts('redemapas-home'); ?>
    </head>

    <body <?php $this->bodyProperties() ?>>
        <?= $TEMPLATE_CONTENT ?>

        <?php if (!$app->user->is('guest')): ?>
        <a href="<?= $app->createUrl('panel', 'index') ?>"
           class="redemapas-notifications-bell"
           aria-label="<?= \MapasCulturais\i::__('Notificações') ?>"
           data-redemapas-notifications>
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
            </svg>
            <span class="redemapas-notifications-badge" data-redemapas-notifications-count hidden></span>
        </a>
        <?php endif; ?>

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
