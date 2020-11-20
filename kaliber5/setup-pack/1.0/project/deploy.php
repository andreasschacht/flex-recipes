<?php
namespace Deployer;

require 'recipe/symfony4.php';

// set by recipe
#set('shared_dirs', ['var/log', 'var/sessions']);
#set('shared_files', ['.env.local.php', '.env.local']);
#set('writable_dirs', ['var']);
#set('migrations_config', '');


// Project name
set('application', 'PROJECT');

// Shared files/dirs between deploys
add('shared_dirs', ['public/uploads']);

// Writable dirs by web server
add('writable_dirs', ['public/uploads']);

set('allow_anonymous_stats', false);
set('keep_releases', 5);
set('writable_mode', 'chmod');

set('bin/php', '/opt/php74/bin/php -d memory_limit=1024M');

// Hosts

host('production')
    ->hostname(getenv('DEPLOY_HOST'))
    ->stage('production')
    ->user(getenv('DEPLOY_USER'))
    ->set('deploy_path', getenv('DEPLOY_PATH'))
    ->set('env', [
        'APP_ENV'     => 'prod',

    ]);

// Tasks

// Migrate database before symlink new release.

before('deploy:symlink', 'database:migrate');

task('upload', function() {
    upload(__DIR__.'/', '{{release_path}}');
});

desc('Deploy project');
task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'upload',
    'deploy:shared',
    'deploy:writable',
    'deploy:cache:clear',
    'deploy:cache:warmup',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
]);
