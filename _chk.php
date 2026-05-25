<?php
chdir(__DIR__);
echo shell_exec('php -r "require vendor/autoload.php; \$app=require bootstrap/app.php; \$app->boot(); echo \View::exists(\'admin.dashboard\')?\'EXISTS\':\'MISSING\';" 2>&1');
