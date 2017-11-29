<?php

View::composer('admin.layout.base', 'Curotec\\Composers\\AdminUserComposer');
View::composer('admin.layout.base', 'Curotec\\Composers\\AdminMessagesComposer');

View::composer('layouts.base', 'Curotec\\Composers\\UserComposer');
View::composer('layouts.base', 'Curotec\\Composers\\ModulesComposer');
