<?php

namespace DanielBaez\LaraTheme\Console\Commands;

use DragonCode\PrettyArray\Services\File;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeThemeCommand extends Command
{
    protected Filesystem $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:theme {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new theme';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $viewsPath = base_path(config('laratheme.paths.views')."/{$name}");
        $assetsPath = base_path(config('laratheme.paths.assets')."/{$name}");

        if ($this->files->isDirectory($viewsPath)) {
            $this->error("Theme '{$name}' already exists in views.");
            return 1;
        }
        if ($this->files->isDirectory($assetsPath)) {
            $this->error("Theme '{$name}' already exists in assets.");            
            return 1;
        }

        $this->createThemeDirectory($name, $viewsPath, $assetsPath);
        $this->createThemeFiles($name, $viewsPath, $assetsPath);

        $this->info("Theme '{$name}' created successfully.");
        $this->info('Views path: '. $viewsPath);
        $this->info('Assets path: '. $assetsPath);

        return 0;
    }

    public function createThemeDirectory($name, $viewsPath, $assetsPath)
    {
        $viewsPath = base_path(config('laratheme.paths.views')."/{$name}");
        $assetsPath = base_path(config('laratheme.paths.assets')."/{$name}");

        $this->files->makeDirectory($viewsPath, 0755, true);
        $this->files->makeDirectory("{$viewsPath}/layouts", 0755, true);
        $this->files->makeDirectory("{$viewsPath}/components", 0755, true);

        $this->files->makeDirectory($assetsPath, 0755, true);
        $this->files->makeDirectory("{$assetsPath}/css", 0755, true);
        $this->files->makeDirectory("{$assetsPath}/js", 0755, true);
        $this->files->makeDirectory("{$assetsPath}/image", 0755, true);
    }

    public function createThemeFiles($name, $viewsPath, $assetsPath)
    {
        $welcomeStub = $this->getStubContent('views/welcome.blade.php.stub', [
            'themeName' => $name
        ]);
        $this->files->put("{$viewsPath}/welcome.blade.php", $welcomeStub);

        $layoutStub = $this->getStubContent('views/layouts/app.blade.php.stub', [
            'themeName' => $name
        ]);
        $this->files->put("{$viewsPath}/layouts/app.blade.php", $layoutStub);

        $cssStub = $this->getStubContent('assets/css/app.css.stub', [
            'themeName' => $name
        ]);
        $this->files->put("{$assetsPath}/css/app.css", $cssStub);

        $jsStub = $this->getStubContent('assets/js/app.js.stub', [
            'themeName' => $name
        ]);
        $this->files->put("{$assetsPath}/js/app.js", $jsStub);
    }

    public function getStubContent(string $path, array $data)
    {
        //$path = __DIR__.'/../../Stubs/'.$path;
        $path = $this->getStubPath($path);
        $stub = $this->files->get($path);

        foreach ($data as $key => $value) {
            $stub = str_replace("{{ {$key} }}", $value, $stub);
        }

        return $stub;   
    }

    public function getStubPath(string $path): string
    {
        $customPath = base_path(config('laratheme.paths.stubs')."/{$path}");

        if ($this->files->exists($customPath)) {
            return $customPath;
        }

        return __DIR__.'/../../Stubs/'.$path;
    }
}
