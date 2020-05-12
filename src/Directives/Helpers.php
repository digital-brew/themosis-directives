<?php

namespace Rafflex\ThemosisDirectives\Directives;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use Rafflex\ThemosisDirectives\Helpers\Utilities;

class Helpers
{
    public static function directives()
    {

        /*
        |--------------------------------------------------------------------------
        | Helper Directives
        |--------------------------------------------------------------------------
        |
        | Simple helper directives for various functions used in views.
        |
        */

        /*
        |---------------------------------------------------------------------
        | @istrue / @isfalse
        |---------------------------------------------------------------------
        */

        Blade::directive('istrue', function ($expression) {
            if (Str::contains($expression, ',')) {
                $expression = Utilities::parse($expression);

                return "<?php if (isset({$expression->get(0)}) && (bool) {$expression->get(0)} === true) : ?>" .
                    "<?php echo {$expression->get(1)}; ?>" .
                    "<?php endif; ?>";
            }

            return "<?php if (isset({$expression}) && (bool) {$expression} === true) : ?>";
        });

        Blade::directive('endistrue', function () {
            return "<?php endif; ?>";
        });

        Blade::directive('isfalse', function ($expression) {
            if (Str::contains($expression, ',')) {
                $expression = Utilities::parse($expression);

                return "<?php if (isset({$expression->get(0)}) && (bool) {$expression->get(0)} === false) : ?>" .
                    "<?php echo {$expression->get(1)}; ?>" .
                    "<?php endif; ?>";
            }

            return "<?php if (isset({$expression}) && (bool) {$expression} === false) : ?>";
        });

        Blade::directive('endisfalse', function () {
            return "<?php endif; ?>";
        });

        /*
        |---------------------------------------------------------------------
        | @isnull / @endisnull / @isnotnull / @endisnotnull
        |---------------------------------------------------------------------
        */

        Blade::directive('isnull', function ($expression) {
            if (Str::contains($expression, ',')) {
                $expression = Utilities::parse($expression);

                return "<?php if (is_null({$expression->get(0)})) : ?>" .
                    "<?php echo {$expression->get(1)}; ?>" .
                    "<?php endif; ?>";
            }

            return "<?php if (is_null({$expression})) : ?>";
        });

        Blade::directive('endisnull', function () {
            return '<?php endif; ?>';
        });

        Blade::directive('isnotnull', function ($expression) {
            if (Str::contains($expression, ',')) {
                $expression = Utilities::parse($expression);

                return "<?php if (! is_null({$expression->get(0)})) : ?>" .
                    "<?php echo {$expression->get(1)}; ?>" .
                    "<?php endif; ?>";
            }

            return "<?php if (! is_null({$expression})) : ?>";
        });

        Blade::directive('endisnotnull', function () {
            return '<?php endif; ?>';
        });

        /*
        |---------------------------------------------------------------------
        | @notempty / @endnotempty
        |---------------------------------------------------------------------
        */

        Blade::directive('notempty', function ($expression) {
            if (Str::contains($expression, ',')) {
                $expression = Utilities::parse($expression);

                return "<?php if (! empty({$expression->get(0)})) : ?>" .
                    "<?php echo {$expression->get(1)}; ?>" .
                    "<?php endif; ?>";
            }

            return "<?php if (! empty({$expression})) : ?>";
        });

        Blade::directive('endnotempty', function () {
            return '<?php endif; ?>';
        });

        /*
        |---------------------------------------------------------------------
        | @instanceof / @endinstanceof
        |---------------------------------------------------------------------
        */

        Blade::directive('instanceof', function ($expression) {
            if (Str::contains($expression, ',')) {
                $expression = Utilities::parse($expression);

                return "<?php if ({$expression->get(0)} instanceof {$expression->get(1)}) : ?>";
            }
        });

        Blade::directive('endinstanceof', function () {
            return '<?php endif; ?>';
        });

        /*
        |-`--------------------------------------------------------------------
        | @typeof / @endtypeof
        |---------------------------------------------------------------------
        */

        Blade::directive('typeof', function ($expression) {
            if (Str::contains($expression, ',')) {
                $expression = Utilities::parse($expression);

                return "<?php if (gettype({$expression->get(0)}) == {$expression->get(1)}) : ?>";
            }
        });

        Blade::directive('endtypeof', function () {
            return '<?php endif; ?>';
        });

        /*
        |---------------------------------------------------------------------
        | @global
        |---------------------------------------------------------------------
        */

        Blade::directive('global', function ($expression) {
            return "<?php global {$expression}; ?>";
        });

        /*
        |---------------------------------------------------------------------
        | @set / @unset
        |---------------------------------------------------------------------
        */

        Blade::directive('set', function ($expression) {
            if (Str::contains($expression, ',')) {
                $expression = Utilities::parse($expression, 2);

                return "<?php {$expression->get(0)} = {$expression->get(1)}; ?>";
            }
        });

        Blade::directive('unset', function ($expression) {
            return "<?php unset({$expression}); ?>";
        });

        /*
        |---------------------------------------------------------------------
        | @extract / @implode
        |---------------------------------------------------------------------
        */

        Blade::directive('extract', function ($expression) {
            return "<?php extract({$expression}); ?>";
        });

        Blade::directive('implode', function ($expression) {
            if (Str::contains($expression, ',')) {
                $expression = Utilities::parse($expression);

                return "<?= implode({$expression->get(0)}, {$expression->get(1)}); ?>";
            }
        });

        /*
        |---------------------------------------------------------------------
        | @repeat / @endrepeat
        |---------------------------------------------------------------------
        */

        Blade::directive('repeat', function ($expression) {
            return "<?php for (\$iteration = 0 ; \$iteration < (int) {$expression}; \$iteration++) : ?>" .
                "<?php \$loop = (object) [
                   'index' => \$iteration,
                   'iteration' => \$iteration + 1,
                   'remaining' =>  (int) {$expression} - \$iteration,
                   'count' => (int) {$expression},
                   'first' => \$iteration === 0,
                   'last' => \$iteration + 1 === (int) {$expression}
               ]; ?>";
        });

        Blade::directive('endrepeat', function () {
            return '<?php endfor; ?>';
        });

        /*
        |---------------------------------------------------------------------
        | @style / @endstyle
        |---------------------------------------------------------------------
        */

        Blade::directive('style', function ($expression) {
            if (! empty($expression)) {
                return '<link rel="stylesheet" href="' . Utilities::strip($expression) . '">';
            }

            return '<style>';
        });

        Blade::directive('endstyle', function () {
            return '</style>';
        });

        /*
        |---------------------------------------------------------------------
        | @script / @endscript
        |---------------------------------------------------------------------
        */

        Blade::directive('script', function ($expression) {
            if (! empty($expression)) {
                return '<script src="' . Utilities::strip($expression) . '"></script>';
            }

            return '<script>';
        });

        Blade::directive('endscript', function () {
            return '</script>';
        });

        /*
        |---------------------------------------------------------------------
        | @js
        |---------------------------------------------------------------------
        */

        Blade::directive('js', function ($expression) {
            $expression = Utilities::parse($expression);
            $variable = Utilities::strip($expression->get(0));

            return "<script>\n" .
                "window.{$variable} = <?php echo is_array({$expression->get(1)}) ? json_encode({$expression->get(1)}) : '\'' . {$expression->get(1)} . '\''; ?>;\n" . // phpcs:ignore
                "</script>";
        });

        /*
        |---------------------------------------------------------------------
        | @inline
        |---------------------------------------------------------------------
        */

        Blade::directive('script', function ($expression) {
            $output = "/* {$expression} */\n" .
                "<?php include get_theme_file_path({$expression}) ?>\n";

            if (ends_with($expression, ".html'")) {
                return $output;
            }

            if (ends_with($expression, ".css'")) {
                return "<style>\n" . $output . '</style>';
            }

            if (ends_with($expression, ".js'")) {
                return "<script>\n" . $output . '</script>';
            }
        });

        /*
        |---------------------------------------------------------------------
        | @fa / @fas / @far / @fal / @fab
        |---------------------------------------------------------------------
        */

        Blade::directive('fa', function ($expression) {
            $expression = Utilities::parse($expression);

            return '<i class="fa fa-' . Utilities::strip($expression->get(0)) . ' ' . Utilities::strip($expression->get(1)) . '"></i>'; // phpcs:ignore
        });

        Blade::directive('fas', function ($expression) {
            $expression = Utilities::parse($expression);

            return '<i class="fas fa-' . Utilities::strip($expression->get(0)) . ' ' . Utilities::strip($expression->get(1)) . '"></i>'; // phpcs:ignore
        });

        Blade::directive('far', function ($expression) {
            $expression = Utilities::parse($expression);

            return '<i class="far fa-' . Utilities::strip($expression->get(0)) . ' ' . Utilities::strip($expression->get(1)) . '"></i>'; // phpcs:ignore
        });

        Blade::directive('fal', function ($expression) {
            $expression = Utilities::parse($expression);

            return '<i class="fal fa-' . Utilities::strip($expression->get(0)) . ' ' . Utilities::strip($expression->get(1)) . '"></i>'; // phpcs:ignore
        });

        Blade::directive('fab', function ($expression) {
            $expression = Utilities::parse($expression);

            return '<i class="fab fa-' . Utilities::strip($expression->get(0)) . ' ' . Utilities::strip($expression->get(1)) . '"></i>'; // phpcs:ignore
        });

        /*
        |---------------------------------------------------------------------
        | @ico
        |---------------------------------------------------------------------
        */

        Blade::directive('ico', function ($expression) {
            $expression = Utilities::parse($expression);

            return '<i class="ico ico-' . Utilities::strip($expression->get(0)) . ' ' . Utilities::strip($expression->get(1)) . '"></i>'; // phpcs:ignore
        });

    }
}