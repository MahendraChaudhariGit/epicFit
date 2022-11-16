<?php return array (
  'access' => 
  array (
    'users_table' => 'users',
    'role' => 'App\\Models\\Access\\Role\\Role',
    'roles_table' => 'roles',
    'permission' => 'App\\Models\\Access\\Permission\\Permission',
    'permissions_table' => 'permissions',
    'group' => 'App\\Models\\Access\\Permission\\PermissionGroup',
    'permission_group_table' => 'permission_groups',
    'permission_role_table' => 'permission_role',
    'permission_user_table' => 'permission_user',
    'permission_dependencies_table' => 'permission_dependencies',
    'dependency' => 'App\\Models\\Access\\Permission\\PermissionDependency',
    'assigned_roles_table' => 'assigned_roles',
    'users' => 
    array (
      'default_per_page' => 25,
      'default_role' => 'User',
      'confirm_email' => true,
      'change_email' => false,
    ),
    'roles' => 
    array (
      'role_must_contain_permission' => true,
    ),
    'socialite_session_name' => 'socialite_provider',
  ),
  'analytics' => 
  array (
    'google-analytics' => 'UA-XXXXX-X',
  ),
  'app' => 
  array (
    'env' => 'production',
    'name' => 'EPIC Trainer',
    'debug' => false,
    'url' => 'http://crmtestdev.epictrainer.com',
    'timezone' => 'Pacific/Auckland',
    'locale' => 'en',
    'fallback_locale' => 'en',
    'locale_php' => 'en_US',
    'key' => 'base64:63DNjv5aJg6msiXzncA5M4c+cZyfTUBwbWVcd4Gtdbw=',
    'cipher' => 'AES-256-CBC',
    'log' => 'daily',
    'providers' => 
    array (
      0 => 'Illuminate\\Auth\\AuthServiceProvider',
      1 => 'Illuminate\\Broadcasting\\BroadcastServiceProvider',
      2 => 'Illuminate\\Bus\\BusServiceProvider',
      3 => 'Illuminate\\Cache\\CacheServiceProvider',
      4 => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
      5 => 'Illuminate\\Cookie\\CookieServiceProvider',
      6 => 'Illuminate\\Database\\DatabaseServiceProvider',
      7 => 'Illuminate\\Encryption\\EncryptionServiceProvider',
      8 => 'Illuminate\\Filesystem\\FilesystemServiceProvider',
      9 => 'Illuminate\\Foundation\\Providers\\FoundationServiceProvider',
      10 => 'Illuminate\\Hashing\\HashServiceProvider',
      11 => 'Illuminate\\Mail\\MailServiceProvider',
      12 => 'Illuminate\\Pagination\\PaginationServiceProvider',
      13 => 'Illuminate\\Pipeline\\PipelineServiceProvider',
      14 => 'Illuminate\\Queue\\QueueServiceProvider',
      15 => 'Illuminate\\Redis\\RedisServiceProvider',
      16 => 'Illuminate\\Auth\\Passwords\\PasswordResetServiceProvider',
      17 => 'Illuminate\\Session\\SessionServiceProvider',
      18 => 'Illuminate\\Translation\\TranslationServiceProvider',
      19 => 'Illuminate\\Validation\\ValidationServiceProvider',
      20 => 'Illuminate\\View\\ViewServiceProvider',
      21 => 'Appzcoder\\CrudGenerator\\CrudGeneratorServiceProvider',
      22 => 'Collective\\Html\\HtmlServiceProvider',
      23 => 'App\\Providers\\AccessServiceProvider',
      24 => 'App\\Providers\\AppServiceProvider',
      25 => 'App\\Providers\\AuthServiceProvider',
      26 => 'App\\Providers\\EventServiceProvider',
      27 => 'App\\Providers\\RouteServiceProvider',
      28 => 'Collective\\Html\\HtmlServiceProvider',
      29 => 'Creativeorange\\Gravatar\\GravatarServiceProvider',
      30 => 'Laracasts\\Utilities\\JavaScript\\JavaScriptServiceProvider',
      31 => 'HieuLe\\Active\\ActiveServiceProvider',
      32 => 'Laravel\\Socialite\\SocialiteServiceProvider',
      33 => 'Spatie\\Backup\\BackupServiceProvider',
      34 => 'Rap2hpoutre\\LaravelLogViewer\\LaravelLogViewerServiceProvider',
      35 => 'Maatwebsite\\Excel\\ExcelServiceProvider',
      36 => 'Barryvdh\\DomPDF\\ServiceProvider',
      37 => 'App\\Providers\\MacroServiceProvider',
      38 => 'Intervention\\Image\\ImageServiceProvider',
    ),
    'aliases' => 
    array (
      'App' => 'Illuminate\\Support\\Facades\\App',
      'Artisan' => 'Illuminate\\Support\\Facades\\Artisan',
      'Auth' => 'Illuminate\\Support\\Facades\\Auth',
      'Blade' => 'Illuminate\\Support\\Facades\\Blade',
      'Cache' => 'Illuminate\\Support\\Facades\\Cache',
      'Config' => 'Illuminate\\Support\\Facades\\Config',
      'Cookie' => 'Illuminate\\Support\\Facades\\Cookie',
      'Crypt' => 'Illuminate\\Support\\Facades\\Crypt',
      'DB' => 'Illuminate\\Support\\Facades\\DB',
      'Eloquent' => 'Illuminate\\Database\\Eloquent\\Model',
      'Event' => 'Illuminate\\Support\\Facades\\Event',
      'File' => 'Illuminate\\Support\\Facades\\File',
      'Gate' => 'Illuminate\\Support\\Facades\\Gate',
      'Hash' => 'Illuminate\\Support\\Facades\\Hash',
      'Lang' => 'Illuminate\\Support\\Facades\\Lang',
      'Log' => 'Illuminate\\Support\\Facades\\Log',
      'Mail' => 'Illuminate\\Support\\Facades\\Mail',
      'Password' => 'Illuminate\\Support\\Facades\\Password',
      'Queue' => 'Illuminate\\Support\\Facades\\Queue',
      'Redirect' => 'Illuminate\\Support\\Facades\\Redirect',
      'Redis' => 'Illuminate\\Support\\Facades\\Redis',
      'Request' => 'Illuminate\\Support\\Facades\\Request',
      'Response' => 'Illuminate\\Support\\Facades\\Response',
      'Route' => 'Illuminate\\Support\\Facades\\Route',
      'Schema' => 'Illuminate\\Support\\Facades\\Schema',
      'Session' => 'Illuminate\\Support\\Facades\\Session',
      'Storage' => 'Illuminate\\Support\\Facades\\Storage',
      'URL' => 'Illuminate\\Support\\Facades\\URL',
      'Validator' => 'Illuminate\\Support\\Facades\\Validator',
      'View' => 'Illuminate\\Support\\Facades\\View',
      'Input' => 'Illuminate\\Support\\Facades\\Input',
      'Active' => 'HieuLe\\Active\\Facades\\Active',
      'Excel' => 'Maatwebsite\\Excel\\Facades\\Excel',
      'Form' => 'Collective\\Html\\FormFacade',
      'Gravatar' => 'Creativeorange\\Gravatar\\Facades\\Gravatar',
      'Html' => 'Collective\\Html\\HtmlFacade',
      'Socialite' => 'Laravel\\Socialite\\Facades\\Socialite',
      'HTML' => 'Collective\\Html\\HtmlFacade',
      'PDF' => 'Barryvdh\\DomPDF\\Facade',
      'Image' => 'Intervention\\Image\\Facades\\Image',
    ),
  ),
  'app-pre-060716' => 
  array (
    'env' => 'production',
    'name' => 'EPIC Trainer',
    'debug' => false,
    'url' => 'http://crmtestdev.epictrainer.com',
    'timezone' => 'UTC',
    'locale' => 'en',
    'fallback_locale' => 'en',
    'locale_php' => 'en_US',
    'key' => 'base64:63DNjv5aJg6msiXzncA5M4c+cZyfTUBwbWVcd4Gtdbw=',
    'cipher' => 'AES-256-CBC',
    'log' => 'daily',
    'providers' => 
    array (
      0 => 'Illuminate\\Auth\\AuthServiceProvider',
      1 => 'Illuminate\\Broadcasting\\BroadcastServiceProvider',
      2 => 'Illuminate\\Bus\\BusServiceProvider',
      3 => 'Illuminate\\Cache\\CacheServiceProvider',
      4 => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
      5 => 'Illuminate\\Cookie\\CookieServiceProvider',
      6 => 'Illuminate\\Database\\DatabaseServiceProvider',
      7 => 'Illuminate\\Encryption\\EncryptionServiceProvider',
      8 => 'Illuminate\\Filesystem\\FilesystemServiceProvider',
      9 => 'Illuminate\\Foundation\\Providers\\FoundationServiceProvider',
      10 => 'Illuminate\\Hashing\\HashServiceProvider',
      11 => 'Illuminate\\Mail\\MailServiceProvider',
      12 => 'Illuminate\\Pagination\\PaginationServiceProvider',
      13 => 'Illuminate\\Pipeline\\PipelineServiceProvider',
      14 => 'Illuminate\\Queue\\QueueServiceProvider',
      15 => 'Illuminate\\Redis\\RedisServiceProvider',
      16 => 'Illuminate\\Auth\\Passwords\\PasswordResetServiceProvider',
      17 => 'Illuminate\\Session\\SessionServiceProvider',
      18 => 'Illuminate\\Translation\\TranslationServiceProvider',
      19 => 'Illuminate\\Validation\\ValidationServiceProvider',
      20 => 'Illuminate\\View\\ViewServiceProvider',
      21 => 'Appzcoder\\CrudGenerator\\CrudGeneratorServiceProvider',
      22 => 'Collective\\Html\\HtmlServiceProvider',
      23 => 'App\\Providers\\AccessServiceProvider',
      24 => 'App\\Providers\\AppServiceProvider',
      25 => 'App\\Providers\\AuthServiceProvider',
      26 => 'App\\Providers\\EventServiceProvider',
      27 => 'App\\Providers\\RouteServiceProvider',
      28 => 'Arcanedev\\LogViewer\\LogViewerServiceProvider',
      29 => 'Collective\\Html\\HtmlServiceProvider',
      30 => 'Creativeorange\\Gravatar\\GravatarServiceProvider',
      31 => 'DaveJamesMiller\\Breadcrumbs\\ServiceProvider',
      32 => 'Laracasts\\Utilities\\JavaScript\\JavaScriptServiceProvider',
      33 => 'HieuLe\\Active\\ActiveServiceProvider',
      34 => 'Laravel\\Socialite\\SocialiteServiceProvider',
      35 => 'Spatie\\Backup\\BackupServiceProvider',
      36 => 'App\\Providers\\MacroServiceProvider',
    ),
    'aliases' => 
    array (
      'App' => 'Illuminate\\Support\\Facades\\App',
      'Artisan' => 'Illuminate\\Support\\Facades\\Artisan',
      'Auth' => 'Illuminate\\Support\\Facades\\Auth',
      'Blade' => 'Illuminate\\Support\\Facades\\Blade',
      'Cache' => 'Illuminate\\Support\\Facades\\Cache',
      'Config' => 'Illuminate\\Support\\Facades\\Config',
      'Cookie' => 'Illuminate\\Support\\Facades\\Cookie',
      'Crypt' => 'Illuminate\\Support\\Facades\\Crypt',
      'DB' => 'Illuminate\\Support\\Facades\\DB',
      'Eloquent' => 'Illuminate\\Database\\Eloquent\\Model',
      'Event' => 'Illuminate\\Support\\Facades\\Event',
      'File' => 'Illuminate\\Support\\Facades\\File',
      'Gate' => 'Illuminate\\Support\\Facades\\Gate',
      'Hash' => 'Illuminate\\Support\\Facades\\Hash',
      'Lang' => 'Illuminate\\Support\\Facades\\Lang',
      'Log' => 'Illuminate\\Support\\Facades\\Log',
      'Mail' => 'Illuminate\\Support\\Facades\\Mail',
      'Password' => 'Illuminate\\Support\\Facades\\Password',
      'Queue' => 'Illuminate\\Support\\Facades\\Queue',
      'Redirect' => 'Illuminate\\Support\\Facades\\Redirect',
      'Redis' => 'Illuminate\\Support\\Facades\\Redis',
      'Request' => 'Illuminate\\Support\\Facades\\Request',
      'Response' => 'Illuminate\\Support\\Facades\\Response',
      'Route' => 'Illuminate\\Support\\Facades\\Route',
      'Schema' => 'Illuminate\\Support\\Facades\\Schema',
      'Session' => 'Illuminate\\Support\\Facades\\Session',
      'Storage' => 'Illuminate\\Support\\Facades\\Storage',
      'URL' => 'Illuminate\\Support\\Facades\\URL',
      'Validator' => 'Illuminate\\Support\\Facades\\Validator',
      'View' => 'Illuminate\\Support\\Facades\\View',
      'Active' => 'HieuLe\\Active\\Facades\\Active',
      'Breadcrumbs' => 'DaveJamesMiller\\Breadcrumbs\\Facade',
      'Form' => 'Collective\\Html\\FormFacade',
      'Gravatar' => 'Creativeorange\\Gravatar\\Facades\\Gravatar',
      'Html' => 'Collective\\Html\\HtmlFacade',
      'Socialite' => 'Laravel\\Socialite\\Facades\\Socialite',
      'HTML' => 'Collective\\Html\\HtmlFacade',
    ),
  ),
  'auth' => 
  array (
    'defaults' => 
    array (
      'guard' => 'web',
      'passwords' => 'users',
    ),
    'guards' => 
    array (
      'web' => 
      array (
        'driver' => 'session',
        'provider' => 'users',
      ),
      'api' => 
      array (
        'driver' => 'token',
        'provider' => 'users',
      ),
    ),
    'providers' => 
    array (
      'users' => 
      array (
        'driver' => 'eloquent',
        'model' => 'App\\Models\\Access\\User\\User',
      ),
    ),
    'passwords' => 
    array (
      'users' => 
      array (
        'provider' => 'users',
        'email' => 'frontend.auth.emails.password',
        'table' => 'password_resets',
        'expire' => 60,
      ),
    ),
  ),
  'backend' => 
  array (
    'theme' => 'blue',
  ),
  'breadcrumbs' => 
  array (
    'view' => 'backend.includes.partials.breadcrumbs',
    'files' => '/srv/users/rvtdev/apps/epiccrm/routes/breadcrumbs.php',
    'unnamed-route-exception' => true,
    'missing-route-bound-breadcrumb-exception' => true,
    'invalid-named-breadcrumb-exception' => true,
    'manager-class' => 'DaveJamesMiller\\Breadcrumbs\\BreadcrumbsManager',
    'generator-class' => 'DaveJamesMiller\\Breadcrumbs\\BreadcrumbsGenerator',
  ),
  'broadcasting' => 
  array (
    'default' => 'pusher',
    'connections' => 
    array (
      'pusher' => 
      array (
        'driver' => 'pusher',
        'key' => NULL,
        'secret' => NULL,
        'app_id' => NULL,
        'options' => 
        array (
        ),
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'default',
      ),
      'log' => 
      array (
        'driver' => 'log',
      ),
    ),
  ),
  'cache' => 
  array (
    'default' => 'file',
    'stores' => 
    array (
      'apc' => 
      array (
        'driver' => 'apc',
      ),
      'array' => 
      array (
        'driver' => 'array',
      ),
      'database' => 
      array (
        'driver' => 'database',
        'table' => 'cache',
        'connection' => NULL,
      ),
      'file' => 
      array (
        'driver' => 'file',
        'path' => '/srv/users/rvtdev/apps/epiccrm/storage/framework/cache',
      ),
      'memcached' => 
      array (
        'driver' => 'memcached',
        'servers' => 
        array (
          0 => 
          array (
            'host' => '127.0.0.1',
            'port' => 11211,
            'weight' => 100,
          ),
        ),
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'default',
      ),
    ),
    'prefix' => 'laravel',
  ),
  'compile' => 
  array (
    'files' => 
    array (
    ),
    'providers' => 
    array (
    ),
  ),
  'crudgenerator' => 
  array (
    'custom_template' => false,
    'path' => '/srv/users/rvtdev/apps/epiccrm/resources/crud-generator/',
    'view_columns_number' => 3,
  ),
  'database' => 
  array (
    'fetch' => 8,
    'default' => 'mysql',
    'connections' => 
    array (
      'sqlite' => 
      array (
        'driver' => 'sqlite',
        'database' => 'epiccrm',
        'prefix' => '',
      ),
      'mysql' => 
      array (
        'driver' => 'mysql',
        'host' => 'localhost',
        'port' => '3306',
        'database' => 'epiccrm',
        'username' => '659a075b18f4',
        'password' => '3bcdc1f217ac72bb',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => '',
        'strict' => false,
        'engine' => NULL,
      ),
      'pgsql' => 
      array (
        'driver' => 'pgsql',
        'host' => 'localhost',
        'database' => 'epiccrm',
        'username' => '659a075b18f4',
        'password' => '3bcdc1f217ac72bb',
        'charset' => 'utf8',
        'prefix' => '',
        'schema' => 'public',
      ),
      'sqlsrv' => 
      array (
        'driver' => 'sqlsrv',
        'host' => 'localhost',
        'database' => 'epiccrm',
        'username' => '659a075b18f4',
        'password' => '3bcdc1f217ac72bb',
        'charset' => 'utf8',
        'prefix' => '',
      ),
    ),
    'migrations' => 'migrations',
    'redis' => 
    array (
      'cluster' => false,
      'default' => 
      array (
        'host' => 'localhost',
        'password' => NULL,
        'port' => '6379',
        'database' => 0,
      ),
    ),
  ),
  'debugbar' => 
  array (
    'enabled' => NULL,
    'except' => 
    array (
      0 => 'telescope*',
    ),
    'storage' => 
    array (
      'enabled' => true,
      'driver' => 'file',
      'path' => '/srv/users/rvtdev/apps/epiccrm/storage/debugbar',
      'connection' => NULL,
    ),
    'include_vendors' => true,
    'capture_ajax' => true,
    'add_ajax_timing' => false,
    'error_handler' => false,
    'clockwork' => false,
    'collectors' => 
    array (
      'phpinfo' => true,
      'messages' => true,
      'time' => true,
      'memory' => true,
      'exceptions' => true,
      'log' => true,
      'db' => true,
      'views' => true,
      'route' => true,
      'laravel' => false,
      'events' => false,
      'default_request' => false,
      'symfony_request' => true,
      'mail' => true,
      'logs' => false,
      'files' => false,
      'config' => false,
      'auth' => false,
      'gate' => false,
      'session' => true,
    ),
    'options' => 
    array (
      'auth' => 
      array (
        'show_name' => false,
      ),
      'db' => 
      array (
        'with_params' => true,
        'timeline' => false,
        'backtrace' => false,
        'explain' => 
        array (
          'enabled' => false,
          'types' => 
          array (
            0 => 'SELECT',
          ),
        ),
        'hints' => true,
      ),
      'mail' => 
      array (
        'full_log' => false,
      ),
      'views' => 
      array (
        'data' => false,
      ),
      'route' => 
      array (
        'label' => true,
      ),
      'logs' => 
      array (
        'file' => NULL,
      ),
    ),
    'inject' => true,
    'route_prefix' => '_debugbar',
    'route_domain' => NULL,
  ),
  'dompdf' => 
  array (
    'show_warnings' => false,
    'orientation' => 'portrait',
    'defines' => 
    array (
      'DOMPDF_FONT_DIR' => '/srv/users/rvtdev/apps/epiccrm/storage/fonts/',
      'DOMPDF_FONT_CACHE' => '/srv/users/rvtdev/apps/epiccrm/storage/fonts/',
      'DOMPDF_TEMP_DIR' => '/tmp',
      'DOMPDF_CHROOT' => '/srv/users/rvtdev/apps/epiccrm',
      'DOMPDF_UNICODE_ENABLED' => true,
      'DOMPDF_ENABLE_FONT_SUBSETTING' => false,
      'DOMPDF_PDF_BACKEND' => 'CPDF',
      'DOMPDF_DEFAULT_MEDIA_TYPE' => 'screen',
      'DOMPDF_DEFAULT_PAPER_SIZE' => 'a4',
      'DOMPDF_DEFAULT_FONT' => 'serif',
      'DOMPDF_DPI' => 96,
      'DOMPDF_ENABLE_PHP' => false,
      'DOMPDF_ENABLE_JAVASCRIPT' => true,
      'DOMPDF_ENABLE_REMOTE' => true,
      'DOMPDF_FONT_HEIGHT_RATIO' => 1.1,
      'DOMPDF_ENABLE_CSS_FLOAT' => false,
      'DOMPDF_ENABLE_HTML5PARSER' => false,
    ),
  ),
  'excel' => 
  array (
    'exports' => 
    array (
      'chunk_size' => 1000,
      'pre_calculate_formulas' => false,
      'csv' => 
      array (
        'delimiter' => ',',
        'enclosure' => '"',
        'line_ending' => '
',
        'use_bom' => false,
        'include_separator_line' => false,
        'excel_compatibility' => false,
      ),
    ),
    'imports' => 
    array (
      'read_only' => true,
      'heading_row' => 
      array (
        'formatter' => 'slug',
      ),
      'csv' => 
      array (
        'delimiter' => ',',
        'enclosure' => '"',
        'escape_character' => '\\',
        'contiguous' => false,
        'input_encoding' => 'UTF-8',
      ),
    ),
    'extension_detector' => 
    array (
      'xlsx' => 'Xlsx',
      'xlsm' => 'Xlsx',
      'xltx' => 'Xlsx',
      'xltm' => 'Xlsx',
      'xls' => 'Xls',
      'xlt' => 'Xls',
      'ods' => 'Ods',
      'ots' => 'Ods',
      'slk' => 'Slk',
      'xml' => 'Xml',
      'gnumeric' => 'Gnumeric',
      'htm' => 'Html',
      'html' => 'Html',
      'csv' => 'Csv',
      'tsv' => 'Csv',
      'pdf' => 'Dompdf',
    ),
    'value_binder' => 
    array (
      'default' => 'Maatwebsite\\Excel\\DefaultValueBinder',
    ),
    'transactions' => 
    array (
      'handler' => 'db',
    ),
    'temporary_files' => 
    array (
      'local_path' => '/tmp',
      'remote_disk' => NULL,
    ),
  ),
  'filesystems' => 
  array (
    'default' => 'local',
    'cloud' => 's3',
    'disks' => 
    array (
      'local' => 
      array (
        'driver' => 'local',
        'root' => '/srv/users/rvtdev/apps/epiccrm/storage/app',
      ),
      'public' => 
      array (
        'driver' => 'local',
        'root' => '/srv/users/rvtdev/apps/epiccrm/storage/app/public',
        'visibility' => 'public',
      ),
      's3' => 
      array (
        'driver' => 's3',
        'key' => 'your-key',
        'secret' => 'your-secret',
        'region' => 'your-region',
        'bucket' => 'your-bucket',
      ),
    ),
  ),
  'gravatar' => 
  array (
    'default' => 
    array (
      'size' => 80,
      'fallback' => 'mm',
      'secure' => false,
      'maximumRating' => 'g',
      'forceDefault' => false,
      'forceExtension' => 'jpg',
    ),
  ),
  'image' => 
  array (
    'driver' => 'gd',
  ),
  'javascript' => 
  array (
    'bind_js_vars_to_this_view' => 'frontend.layouts.master',
    'js_namespace' => 'window',
  ),
  'laravel-backup' => 
  array (
    'backup' => 
    array (
      'name' => 'http://crmtestdev.epictrainer.com',
      'source' => 
      array (
        'files' => 
        array (
          'include' => 
          array (
            0 => '/srv/users/rvtdev/apps/epiccrm',
          ),
          'exclude' => 
          array (
            0 => '/srv/users/rvtdev/apps/epiccrm/vendor',
            1 => '/srv/users/rvtdev/apps/epiccrm/node_modules',
            2 => '/srv/users/rvtdev/apps/epiccrm/storage/debugbar',
            3 => '/srv/users/rvtdev/apps/epiccrm/storage/framework',
            4 => '/srv/users/rvtdev/apps/epiccrm/storage/laravel-backup',
            5 => '/srv/users/rvtdev/apps/epiccrm/storage/logs',
          ),
        ),
        'databases' => 
        array (
          0 => 'mysql',
        ),
      ),
      'destination' => 
      array (
        'disks' => 
        array (
          0 => 'local',
        ),
      ),
    ),
    'cleanup' => 
    array (
      'strategy' => 'Spatie\\Backup\\Tasks\\Cleanup\\Strategies\\DefaultStrategy',
      'defaultStrategy' => 
      array (
        'keepAllBackupsForDays' => 7,
        'keepDailyBackupsForDays' => 16,
        'keepWeeklyBackupsForWeeks' => 8,
        'keepMonthlyBackupsForMonths' => 4,
        'keepYearlyBackupsForYears' => 2,
        'deleteOldestBackupsWhenUsingMoreMegabytesThan' => 5000,
      ),
    ),
    'monitorBackups' => 
    array (
      0 => 
      array (
        'name' => 'http://crmtestdev.epictrainer.com',
        'disks' => 
        array (
          0 => 'local',
        ),
        'newestBackupsShouldNotBeOlderThanDays' => 1,
        'storageUsedMayNotBeHigherThanMegabytes' => 5000,
      ),
    ),
    'notifications' => 
    array (
      'handler' => 'Spatie\\Backup\\Notifications\\Notifier',
      'events' => 
      array (
        'whenBackupWasSuccessful' => 
        array (
          0 => 'log',
        ),
        'whenCleanupWasSuccessful' => 
        array (
          0 => 'log',
        ),
        'whenHealthyBackupWasFound' => 
        array (
          0 => 'log',
        ),
        'whenBackupHasFailed' => 
        array (
          0 => 'log',
          1 => 'mail',
        ),
        'whenCleanupHasFailed' => 
        array (
          0 => 'log',
          1 => 'mail',
        ),
        'whenUnHealthyBackupWasFound' => 
        array (
          0 => 'log',
          1 => 'mail',
        ),
      ),
      'mail' => 
      array (
        'from' => NULL,
        'to' => 'your@email.com',
      ),
      'slack' => 
      array (
        'channel' => '#backups',
        'username' => 'Backup bot',
        'icon' => ':robot:',
      ),
    ),
  ),
  'locale' => 
  array (
    'status' => true,
    'languages' => 
    array (
      'da' => 
      array (
        0 => 'da',
        1 => 'da_DK',
      ),
      'de' => 
      array (
        0 => 'de',
        1 => 'de_DE',
      ),
      'en' => 
      array (
        0 => 'en',
        1 => 'en_US',
      ),
      'es' => 
      array (
        0 => 'es',
        1 => 'es_ES',
      ),
      'fr' => 
      array (
        0 => 'fr',
        1 => 'fr_FR',
      ),
      'it' => 
      array (
        0 => 'it',
        1 => 'it_IT',
      ),
      'pt-BR' => 
      array (
        0 => 'pt_BR',
        1 => 'pt_BR',
      ),
      'sv' => 
      array (
        0 => 'sv',
        1 => 'sv_SE',
      ),
    ),
  ),
  'log-viewer' => 
  array (
    'storage-path' => '/srv/users/rvtdev/apps/epiccrm/storage/logs',
    'pattern' => 
    array (
      'prefix' => 'laravel-',
      'date' => '[0-9][0-9][0-9][0-9]-[0-9][0-9]-[0-9][0-9]',
      'extension' => '.log',
    ),
    'locale' => 'auto',
    'theme' => 'bootstrap-4',
    'route' => 
    array (
      'enabled' => false,
      'attributes' => 
      array (
        'prefix' => 'log-viewer',
        'middleware' => NULL,
      ),
    ),
    'per-page' => 30,
    'download' => 
    array (
      'prefix' => 'laravel-',
      'extension' => 'log',
    ),
    'menu' => 
    array (
      'filter-route' => 'log-viewer::logs.filter',
      'icons-enabled' => true,
    ),
    'icons' => 
    array (
      'all' => 'fa fa-fw fa-list',
      'emergency' => 'fa fa-fw fa-bug',
      'alert' => 'fa fa-fw fa-bullhorn',
      'critical' => 'fa fa-fw fa-heartbeat',
      'error' => 'fa fa-fw fa-times-circle',
      'warning' => 'fa fa-fw fa-exclamation-triangle',
      'notice' => 'fa fa-fw fa-exclamation-circle',
      'info' => 'fa fa-fw fa-info-circle',
      'debug' => 'fa fa-fw fa-life-ring',
    ),
    'colors' => 
    array (
      'levels' => 
      array (
        'empty' => '#D1D1D1',
        'all' => '#8A8A8A',
        'emergency' => '#B71C1C',
        'alert' => '#D32F2F',
        'critical' => '#F44336',
        'error' => '#FF5722',
        'warning' => '#FF9100',
        'notice' => '#4CAF50',
        'info' => '#1976D2',
        'debug' => '#90CAF9',
      ),
    ),
    'highlight' => 
    array (
      0 => '^#\\d+',
      1 => '^Stack trace:',
    ),
    'facade' => 'LogViewer',
  ),
  'logging' => 
  array (
    'default' => 'stack',
    'channels' => 
    array (
      'stack' => 
      array (
        'driver' => 'stack',
        'channels' => 
        array (
          0 => 'single',
          1 => 'singleInfo',
          2 => 'singleAlert',
          3 => 'singleWarning',
          4 => 'singleWarning',
          5 => 'singleCritical',
          6 => 'singleEmergency',
          7 => 'activity',
        ),
        'ignore_exceptions' => false,
      ),
      'single' => 
      array (
        'driver' => 'single',
        'path' => '/srv/users/rvtdev/apps/epiccrm/storage/logs/laravel.log',
        'level' => 'debug',
      ),
      'daily' => 
      array (
        'driver' => 'daily',
        'path' => '/srv/users/rvtdev/apps/epiccrm/storage/logs/laravel.log',
        'level' => 'debug',
        'days' => 14,
      ),
      'activity' => 
      array (
        'driver' => 'daily',
        'path' => '/srv/users/rvtdev/apps/epiccrm/storage/logs/activity.log',
        'level' => 'debug',
        'days' => 14,
      ),
      'singleInfo' => 
      array (
        'driver' => 'daily',
        'path' => '/srv/users/rvtdev/apps/epiccrm/storage/logs/info.log',
        'level' => 'info',
        'days' => 14,
      ),
      'singleAlert' => 
      array (
        'driver' => 'daily',
        'path' => '/srv/users/rvtdev/apps/epiccrm/storage/logs/alert.log',
        'level' => 'alert',
        'days' => 14,
      ),
      'singleWarning' => 
      array (
        'driver' => 'daily',
        'path' => '/srv/users/rvtdev/apps/epiccrm/storage/logs/warning.log',
        'level' => 'warning',
        'days' => 14,
      ),
      'singleCritical' => 
      array (
        'driver' => 'daily',
        'path' => '/srv/users/rvtdev/apps/epiccrm/storage/logs/critical.log',
        'level' => 'critical',
        'days' => 14,
      ),
      'singleEmergency' => 
      array (
        'driver' => 'daily',
        'path' => '/srv/users/rvtdev/apps/epiccrm/storage/logs/emergency.log',
        'level' => 'emergency',
        'days' => 14,
      ),
      'slack' => 
      array (
        'driver' => 'slack',
        'url' => NULL,
        'username' => 'Laravel Log',
        'emoji' => ':boom:',
        'level' => 'critical',
      ),
      'papertrail' => 
      array (
        'driver' => 'monolog',
        'level' => 'debug',
        'handler' => 'Monolog\\Handler\\SyslogUdpHandler',
        'handler_with' => 
        array (
          'host' => NULL,
          'port' => NULL,
        ),
      ),
      'stderr' => 
      array (
        'driver' => 'monolog',
        'handler' => 'Monolog\\Handler\\StreamHandler',
        'formatter' => NULL,
        'with' => 
        array (
          'stream' => 'php://stderr',
        ),
      ),
      'syslog' => 
      array (
        'driver' => 'syslog',
        'level' => 'debug',
      ),
      'errorlog' => 
      array (
        'driver' => 'errorlog',
        'level' => 'debug',
      ),
    ),
  ),
  'mail' => 
  array (
    'driver' => 'log',
    'host' => 'mailtrap.io',
    'port' => '2525',
    'from' => 
    array (
      'address' => 'support@epictrainer.com',
      'name' => 'EPIC Trainer',
    ),
    'encryption' => NULL,
    'username' => NULL,
    'password' => NULL,
    'sendmail' => '/usr/sbin/sendmail -bs',
  ),
  'mail-pre-081116' => 
  array (
    'driver' => 'smtp',
    'host' => 'mailtrap.io',
    'port' => '2525',
    'from' => 
    array (
      'address' => 'support@epictrainer.com',
      'name' => 'EPIC Trainer',
    ),
    'encryption' => NULL,
    'username' => NULL,
    'password' => NULL,
    'sendmail' => '/usr/sbin/sendmail -bs',
  ),
  'queue' => 
  array (
    'default' => 'sync',
    'connections' => 
    array (
      'sync' => 
      array (
        'driver' => 'sync',
      ),
      'database' => 
      array (
        'driver' => 'database',
        'table' => 'jobs',
        'queue' => 'default',
        'expire' => 60,
      ),
      'beanstalkd' => 
      array (
        'driver' => 'beanstalkd',
        'host' => 'localhost',
        'queue' => 'default',
        'ttr' => 60,
      ),
      'sqs' => 
      array (
        'driver' => 'sqs',
        'key' => 'your-public-key',
        'secret' => 'your-secret-key',
        'prefix' => 'https://sqs.us-east-1.amazonaws.com/your-account-id',
        'queue' => 'your-queue-name',
        'region' => 'us-east-1',
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'default',
        'queue' => 'default',
        'expire' => 60,
      ),
    ),
    'failed' => 
    array (
      'database' => 'mysql',
      'table' => 'failed_jobs',
    ),
  ),
  'services' => 
  array (
    'mailgun' => 
    array (
      'domain' => NULL,
      'secret' => NULL,
    ),
    'ses' => 
    array (
      'key' => NULL,
      'secret' => NULL,
      'region' => 'us-east-1',
    ),
    'sparkpost' => 
    array (
      'secret' => NULL,
    ),
    'stripe' => 
    array (
      'model' => 'App\\Models\\Access\\User\\User',
      'key' => NULL,
      'secret' => NULL,
    ),
    'bitbucket' => 
    array (
      'client_id' => '',
      'client_secret' => '',
      'redirect' => '',
      'scopes' => 
      array (
      ),
      'with' => 
      array (
      ),
    ),
    'facebook' => 
    array (
      'client_id' => '',
      'client_secret' => '',
      'redirect' => '',
      'scopes' => 
      array (
      ),
      'with' => 
      array (
      ),
    ),
    'github' => 
    array (
      'client_id' => '',
      'client_secret' => '',
      'redirect' => '',
      'scopes' => 
      array (
      ),
      'with' => 
      array (
      ),
    ),
    'google' => 
    array (
      'client_id' => '',
      'client_secret' => '',
      'redirect' => '',
      'scopes' => 
      array (
        0 => 'https://www.googleapis.com/auth/plus.me',
        1 => 'https://www.googleapis.com/auth/plus.profile.emails.read',
      ),
      'with' => 
      array (
      ),
    ),
    'linkedin' => 
    array (
      'client_id' => '',
      'client_secret' => '',
      'redirect' => '',
      'scopes' => 
      array (
      ),
      'with' => 
      array (
      ),
    ),
    'twitter' => 
    array (
      'client_id' => NULL,
      'client_secret' => NULL,
      'redirect' => '',
      'scopes' => 
      array (
      ),
      'with' => 
      array (
      ),
    ),
  ),
  'session' => 
  array (
    'driver' => 'file',
    'lifetime' => 1440,
    'expire_on_close' => false,
    'encrypt' => false,
    'files' => '/srv/users/rvtdev/apps/epiccrm/storage/framework/sessions',
    'connection' => NULL,
    'table' => 'sessions',
    'lottery' => 
    array (
      0 => 2,
      1 => 100,
    ),
    'cookie' => 'lvapp_session',
    'path' => '/',
    'domain' => NULL,
    'secure' => false,
    'http_only' => false,
  ),
  'view' => 
  array (
    'paths' => 
    array (
      0 => '/srv/users/rvtdev/apps/epiccrm/resources/views',
    ),
    'compiled' => '/srv/users/rvtdev/apps/epiccrm/storage/framework/views',
  ),
  'backup' => 
  array (
    'backup' => 
    array (
      'name' => 'laravel-backup',
      'source' => 
      array (
        'files' => 
        array (
          'include' => 
          array (
            0 => '/srv/users/rvtdev/apps/epiccrm',
          ),
          'exclude' => 
          array (
            0 => '/srv/users/rvtdev/apps/epiccrm/vendor',
            1 => '/srv/users/rvtdev/apps/epiccrm/node_modules',
          ),
          'follow_links' => false,
        ),
        'databases' => 
        array (
          0 => 'mysql',
        ),
      ),
      'database_dump_compressor' => NULL,
      'destination' => 
      array (
        'filename_prefix' => '',
        'disks' => 
        array (
          0 => 'local',
        ),
      ),
      'temporary_directory' => '/srv/users/rvtdev/apps/epiccrm/storage/app/backup-temp',
    ),
    'notifications' => 
    array (
      'notifications' => 
      array (
        'Spatie\\Backup\\Notifications\\Notifications\\BackupHasFailed' => 
        array (
          0 => 'mail',
        ),
        'Spatie\\Backup\\Notifications\\Notifications\\UnhealthyBackupWasFound' => 
        array (
          0 => 'mail',
        ),
        'Spatie\\Backup\\Notifications\\Notifications\\CleanupHasFailed' => 
        array (
          0 => 'mail',
        ),
        'Spatie\\Backup\\Notifications\\Notifications\\BackupWasSuccessful' => 
        array (
          0 => 'mail',
        ),
        'Spatie\\Backup\\Notifications\\Notifications\\HealthyBackupWasFound' => 
        array (
          0 => 'mail',
        ),
        'Spatie\\Backup\\Notifications\\Notifications\\CleanupWasSuccessful' => 
        array (
          0 => 'mail',
        ),
      ),
      'notifiable' => 'Spatie\\Backup\\Notifications\\Notifiable',
      'mail' => 
      array (
        'to' => 'your@example.com',
        'from' => 
        array (
          'address' => 'hello@example.com',
          'name' => 'Example',
        ),
      ),
      'slack' => 
      array (
        'webhook_url' => '',
        'channel' => NULL,
        'username' => NULL,
        'icon' => NULL,
      ),
    ),
    'monitor_backups' => 
    array (
      0 => 
      array (
        'name' => 'laravel-backup',
        'disks' => 
        array (
          0 => 'local',
        ),
        'health_checks' => 
        array (
          'Spatie\\Backup\\Tasks\\Monitor\\HealthChecks\\MaximumAgeInDays' => 1,
          'Spatie\\Backup\\Tasks\\Monitor\\HealthChecks\\MaximumStorageInMegabytes' => 5000,
        ),
      ),
    ),
    'cleanup' => 
    array (
      'strategy' => 'Spatie\\Backup\\Tasks\\Cleanup\\Strategies\\DefaultStrategy',
      'default_strategy' => 
      array (
        'keep_all_backups_for_days' => 7,
        'keep_daily_backups_for_days' => 16,
        'keep_weekly_backups_for_weeks' => 8,
        'keep_monthly_backups_for_months' => 4,
        'keep_yearly_backups_for_years' => 2,
        'delete_oldest_backups_when_using_more_megabytes_than' => 5000,
      ),
    ),
  ),
);
