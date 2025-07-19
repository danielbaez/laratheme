<?php

namespace DanielBaez\LaraTheme;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\View\Factory;
use Illuminate\View\View;

class LaraThemeManager
{
    protected Factory $view;
    protected UrlGenerator $url;

    public function __construct(Factory $view, UrlGenerator $url)
    {
        $this->view = $view;
        $this->url = $url;
    }

    public function getActiveTheme(): string
    {
        return config('laratheme.active');
    }

    public function view(string $view, array $data = []): View
    {
        $view = "laratheme::{$view}";
        //return view($view, $data);
        return $this->view->make($view, $data);
    }

    public function asset(string $path): string
    {
        $activeTheme = $this->getActiveTheme();
        $assetsPath = config("laratheme.paths.assets");

        $path = "{$assetsPath}/{$activeTheme}/".ltrim($path, '/');
        $path = str_replace('public/', '', $path);

        return $this->url->asset($path);
    }
}