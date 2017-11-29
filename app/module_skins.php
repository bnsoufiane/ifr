<?php

use Curotec\ModuleSkin;

// Defining skins for modules

class IfrModuleSkin extends ModuleSkin
{
    protected static $name = 'IFR Product Skin';

    protected $activityTemplates = array(
        'ActivityTemplates\FreeFormAnswer',
        'ActivityTemplates\Select',
        'ActivityTemplates\Story',
        'ActivityTemplates\Cartoon',
        'ActivityTemplates\YesNo',
        'ActivityTemplates\Blog',
        'ActivityTemplates\Calculation',
        'ActivityTemplates\Wysiwyg',
        'ActivityTemplates\QnA',
        'ActivityTemplates\Assessment',
        'ActivityTemplates\MultipleAnswers',
        'ActivityTemplates\TrueFalse',
        'ActivityTemplates\Fillblank',
    );
}
