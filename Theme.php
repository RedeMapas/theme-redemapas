<?php

namespace redemapas;

use MapasCulturais\App;

class Theme extends \MapasCulturais\Themes\BaseV2\Theme
{
    static function getThemeFolder()
    {
        return __DIR__;
    }

    function _init()
    {
        parent::_init();

        $app = App::i();

        $app->hook('view.render(site/index):before', function () {
            $this->enqueueStyle('redemapas-home', 'redemapas-home-css', 'css/home.css');
            $this->enqueueScript('redemapas-home', 'redemapas-home-js', 'js/home.js');
            $this->bodyClasses[] = 'redemapas-home';
        });
    }
}
