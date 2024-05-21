<?php

namespace DigitalBrew\ThemosisDirectives\Providers;

use Illuminate\Support\ServiceProvider;
use DigitalBrew\ThemosisDirectives\Directives\ACF;
use DigitalBrew\ThemosisDirectives\Directives\Helpers;
use DigitalBrew\ThemosisDirectives\Directives\WordPress;

class DirectiveServiceProvider extends ServiceProvider
{
    public function boot() {
        ACF::directives();
        Helpers::directives();
        WordPress::directives();
    }
}