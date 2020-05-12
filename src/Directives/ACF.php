<?php

namespace Rafflex\ThemosisDirectives\Directives;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use Rafflex\ThemosisDirectives\Helpers\Utilities;

class ACF
{
    public static function directives()
    {

        /*
        |--------------------------------------------------------------------------
        | ACF Directives
        |--------------------------------------------------------------------------
        |
        | Directives specific to Advance Custom Fields.
        |
        */

        /*
        |---------------------------------------------------------------------
        | @fields / @endfields
        |---------------------------------------------------------------------
        */

        Blade::directive('fields', function ($expression) {
            if (Str::contains($expression, ',')) {
                $expression = Utilities::parse($expression);

                return "<?php if (have_rows({$expression->get(0)}, {$expression->get(1)})) : ?>" .
                    "<?php while (have_rows({$expression->get(0)}, {$expression->get(1)})) : the_row(); ?>";
            }

            return "<?php if (have_rows({$expression})) : ?>" .
                "<?php while (have_rows({$expression})) : the_row(); ?>";
        });

        Blade::directive('endfields', function () {
            return "<?php endwhile; endif; ?>";
        });

        /*
        |---------------------------------------------------------------------
        | @field / @hasfield / @isfield / @endfield
        |---------------------------------------------------------------------
        */

        Blade::directive('field', function ($expression) {
            if (Str::contains($expression, ',')) {
                $expression = Utilities::parse($expression);

                if (! empty($expression->get(2)) && ! is_string($expression->get(2))) {
                    return "<?php echo get_field({$expression->get(0)}, {$expression->get(2)})[{$expression->get(1)}]; ?>";
                }

                if (! is_string($expression->get(1))) {
                    return "<?php echo get_field({$expression->get(0)}, {$expression->get(1)}); ?>";
                }

                return "<?php echo get_field({$expression->get(0)})[{$expression->get(1)}]; ?>";
            }

            return "<?php echo get_field({$expression}); ?>";
        });

        Blade::directive('hasfield', function ($expression) {
            if (Str::contains($expression, ',')) {
                $expression = Utilities::parse($expression);

                if (! empty($expression->get(2)) && ! is_string($expression->get(2))) {
                    return "<?php if (echo get_field({$expression->get(0)}, {$expression->get(2)})[{$expression->get(1)}]) : ?>";
                }

                if (! is_string($expression->get(1))) {
                    return "<?php if (echo get_field({$expression->get(0)}, {$expression->get(1)})) : ?>";
                }

                return "<?php if (echo get_field({$expression->get(0)})[{$expression->get(1)}]) : ?>";
            }

            return "<?php if (echo get_field({$expression})) : ?>";
        });

        Blade::directive('isfield', function ($expression) {
            if (Str::contains($expression, ',')) {
                $expression = Utilities::parse($expression);

                if (! empty($expression->get(3)) && ! is_string($expression->get(2))) {
                    return "<?php if (echo get_field({$expression->get(0)}, {$expression->get(3)})[{$expression->get(1)}] === {$expression->get(2)}) : ?>"; // phpcs:ignore
                }

                if (! empty($expression->get(2)) && ! is_string($expression->get(2))) {
                    return "<?php if (echo get_field({$expression->get(0)}, {$expression->get(2)}) === {$expression->get(1)}) : ?>"; // phpcs:ignore
                }

                if (! empty($expression->get(2)) && is_string($expression->get(2))) {
                    return "<?php if (echo get_field({$expression->get(0)})[{$expression->get(2)}] === {$expression->get(1)}) : ?>"; // phpcs:ignore
                }

                return "<?php if (echo get_field({$expression->get(0)}) === {$expression->get(1)}) : ?>";
            }
        });

        Blade::directive('endfield', function () {
            return "<?php endif; ?>";
        });

        /*
        |---------------------------------------------------------------------
        | @sub / @hassub / @issub / @endsub
        |---------------------------------------------------------------------
        */

        Blade::directive('sub', function ($expression) {
            if (Str::contains($expression, ',')) {
                $expression = Utilitiesities::parse($expression);

                if (! empty($expression->get(2))) {
                    return "<?php get_sub_field({$expression->get(0)})[{$expression->get(1)}][{$expression->get(2)}]; ?>";
                }

                return "<?php get_sub_field({$expression->get(0)})[{$expression->get(1)}]; ?>";
            }

            return "<?php get_sub_field({$expression}); ?>";
        });

        Blade::directive('hassub', function ($expression) {
            if (Str::contains($expression, ',')) {
                $expression = Utilities::parse($expression);

                if (! empty($expression->get(2))) {
                    return "<?php if (get_sub_field({$expression->get(0)})[{$expression->get(1)}][{$expression->get(2)}]) : ?>"; // phpcs:ignore
                }

                return "<?php if (get_sub_field({$expression->get(0)})[{$expression->get(1)}]) : ?>";
            }

            return "<?php if (get_sub_field({$expression})) : ?>";
        });

        Blade::directive('issub', function ($expression) {
            if (Str::contains($expression, ',')) {
                $expression = Utilities::parse($expression);

                if (! empty($expression->get(2))) {
                    return "<?php if (get_sub_field({$expression->get(0)})[{$expression->get(1)}] === {$expression->get(2)}) : ?>"; // phpcs:ignore
                }

                return "<?php if (get_sub_field({$expression->get(0)}) === {$expression->get(1)}) : ?>";
            }
        });

        Blade::directive('endsub', function () {
            return "<?php endif; ?>";
        });

        /*
        |---------------------------------------------------------------------
        | @layouts / @endlayouts
        |---------------------------------------------------------------------
        */

        Blade::directive('layouts', function ($expression) {
            return "<?php if (get_row_layout() === {$expression}) : ?>";
        });

        Blade::directive('endlayouts', function () {
            return "<?php endif; ?>";
        });

        /*
        |---------------------------------------------------------------------
        | @group / @endgroup
        |---------------------------------------------------------------------
        */

        Blade::directive('group', function ($expression) {
            if (Str::contains($expression, ',')) {
                $expression = Utilities::parse($expression);

                return "<?php if (have_rows({$expression->get(0)}, {$expression->get(1)})) : ?>" .
                    "<?php while (have_rows({$expression->get(0)}, {$expression->get(1)})) : the_row(); ?>";
            }

            return "<?php if (have_rows({$expression})) : ?>" .
                "<?php while (have_rows({$expression})) : the_row(); ?>";
        });

        Blade::directive('endgroup', function () {
            return "<?php endwhile; endif; ?>";
        });

        /*
        |---------------------------------------------------------------------
        | @options / @endoptions
        |---------------------------------------------------------------------
        */

        Blade::directive('options', function ($expression) {
            return "<?php if (have_rows({$expression}, 'option')) : ?>" .
                "<?php while (have_rows({$expression}, 'option')) : the_row(); ?>";
        });

        Blade::directive('endoptions', function () {
            return "<?php endwhile; endif; ?>";
        });

        /*
        |---------------------------------------------------------------------
        | @option / @hasoption / @isoption / @endoption
        |---------------------------------------------------------------------
        */

        Blade::directive('option', function ($expression) {
            if (Str::contains($expression, ',')) {
                $expression = Utilities::parse($expression);

                return "<?php echo get_field({$expression->get(0)}, 'option')[{$expression->get(1)}]; ?>";
            }

            return "<?php echo get_field({$expression}, 'option'); ?>";
        });

        Blade::directive('hasoption', function ($expression) {
            if (Str::contains($expression, ',')) {
                $expression = Utilities::parse($expression);

                return "<?php if (echo get_field({$expression->get(0)}, 'option')[{$expression->get(1)}]) : ?>";
            }

            return "<?php if (echo get_field({$expression}, 'option')) : ?>";
        });

        Blade::directive('isoption', function ($expression) {
            if (Str::contains($expression, ',')) {
                $expression = Utilities::parse($expression);

                if (! empty($expression->get(2))) {
                    return "<?php if (echo get_field({$expression->get(0)}, 'option')[{$expression->get(1)}] === {$expression->get(2)}) : ?>"; // phpcs:ignore
                }

                return "<?php if (echo get_field({$expression->get(0)}, 'option') === {$expression->get(1)}) : ?>";
            }
        });

        Blade::directive('endoption', function () {
            return "<?php endif; ?>";
        });

    }
}