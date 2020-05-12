<?php

namespace Rafflex\ThemosisDirectives\Providers;

use Illuminate\Support\ServiceProvider;
use Rafflex\ThemosisDirectives\Directives\ACF;
use Rafflex\ThemosisDirectives\Directives\Helpers;
use Rafflex\ThemosisDirectives\Directives\WordPress;

class DirectiveServiceProvider extends ServiceProvider
{
    public function boot() {
        ACF::directives();
        Helpers::directives();
        WordPress::directives();
    }
}