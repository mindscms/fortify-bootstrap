<?php

namespace Mindscms\FortifyBootstrap;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class InstallFortifyBootstrap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fortify:bootstrap {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scaffold Fortify authentication views and routes';

    protected $views = [
        'home.blade.php' => 'home.blade.php',
        'profile.blade.php' => 'profile.blade.php',
        'layouts/app.blade.php' => 'layouts/app.blade.php',
        'profile/two-factor-authentication-form.blade.php' => 'profile/two-factor-authentication-form.blade.php',
        'profile/update-password-form.blade.php' => 'profile/update-password-form.blade.php',
        'profile/update-profile-information-form.blade.php' => 'profile/update-profile-information-form.blade.php',
        'auth/login.blade.php' => 'auth/login.blade.php',
        'auth/register.blade.php' => 'auth/register.blade.php',
        'auth/confirm-password.blade.php' => 'auth/confirm-password.blade.php',
        'auth/forgot-password.blade.php' => 'auth/forgot-password.blade.php',
        'auth/reset-password.blade.php' => 'auth/reset-password.blade.php',
        'auth/two-factor-challenge.blade.php' => 'auth/two-factor-challenge.blade.php',
        'auth/verify-email.blade.php' => 'auth/verify-email.blade.php',
    ];

    protected $resourcesFiles = [
        'sass/app.scss' => 'sass/app.scss',
        'sass/_variables.scss' => 'sass/_variables.scss',
        'js/app.js' => 'js/app.js',
        'js/bootstrap.js' => 'js/bootstrap.js',
        'js/components/ExampleComponent.vue' => 'js/components/ExampleComponent.vue',
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        if (! Schema::hasTable('sessions'))
        {
            Artisan::call('session:table');
            Artisan::call('migrate');
        }

        $this->ensureDirectoriesExist();
        $this->exportViews();

        $this->updateRoute();

        $this->exportAssets();
        $this->updatePackage();
        $this->updateWebpackMix();

        $this->info('Bootstrap scaffolding installed successfully.');
        $this->warn('Run "npm install && npm run dev" to compile resources.');
    }

    protected function ensureDirectoriesExist()
    {
        $directories = [
            $this->getViewPath('layouts'),
            $this->getViewPath('auth'),
            $this->getViewPath('profile'),
            resource_path('sass'),
            resource_path('js'),
            resource_path('js/components'),
        ];

        foreach ($directories as $directory) {
            if (! is_dir($directory)) {
                mkdir($directory, 0755, true);
            }
        }
    }

    protected function exportViews()
    {
        foreach ($this->views as $key => $value) {
            if (file_exists($view = $this->getViewPath($value)) && ! $this->option('force')) {
                if (! $this->confirm("The [{$value}] view already exists. Do you want to replace it?")) {
                    continue;
                }
            }

            copy(__DIR__.'/../resources/views/'.$key, $view);
        }
    }

    protected function getViewPath($path)
    {
        return implode(DIRECTORY_SEPARATOR, [
            config('view.paths')[0] ?? resource_path('views'), $path,
        ]);
    }

    protected function updateRoute()
    {
        file_put_contents(
            base_path('routes/web.php'),
            file_get_contents(__DIR__ . '/../routes/web.php'),
            FILE_APPEND
        );
    }

    protected function exportAssets()
    {
        foreach ($this->resourcesFiles as $sourcePath => $destinationPath) {
            copy(__DIR__.'/../resources/'.$sourcePath, resource_path($destinationPath));
        }
    }

    protected function updatePackage($dev = true)
    {
        if (! file_exists(base_path('package.json'))) {
            return;
        }

        $configurationKey = $dev ? 'devDependencies' : 'dependencies';

        $packages = json_decode(file_get_contents(base_path('package.json')), true);

        $bootstrapPackages = [
            "bootstrap" => "^4.5.3",
            "jquery" => "^3.5.1",
            "popper.js" => "^1.16.1",
            "sass" => "^1.27.0",
            "sass-loader" => "^8.0.2",
            "vue" => "^2.6.12",
            "vue-template-compiler" => "^2.6.12",
        ];

        $packages[$configurationKey] = array_merge(
            array_key_exists($configurationKey, $packages) ? $packages[$configurationKey] : [],
            $bootstrapPackages
        );

        ksort($packages[$configurationKey]);

        file_put_contents(
            base_path('package.json'),
            json_encode($packages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT).PHP_EOL
        );

    }

    protected function updateWebpackMix()
    {
        if (! file_exists(base_path('webpack.mix.js'))) {
            return;
        }

        file_put_contents(
            base_path('webpack.mix.js'),
            file_get_contents(__DIR__.'/../resources/webpack.mix.js'),
        );
    }

}
