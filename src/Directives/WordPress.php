<?php

namespace Rafflex\ThemosisDirectives\Directives;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use Rafflex\ThemosisDirectives\Helpers\Utilities;

class WordPress
{
    public static function directives()
    {

        /*
        |--------------------------------------------------------------------------
        | WordPress Directives
        |--------------------------------------------------------------------------
        |
        | Directives for various WordPress use-cases.
        |
        */

        /*
        |---------------------------------------------------------------------
        | @posts / @endposts
        |---------------------------------------------------------------------
        */

        Blade::directive('posts', function ($expression) {
            if (! empty($expression)) {
                return "<?php \$posts = collect(); ?>" .

                    "<?php if (is_a({$expression}, 'WP_Post') || is_numeric({$expression})) : ?>" .
                    "<?php \$posts->put('p', is_a({$expression}, 'WP_Post') ? ({$expression})->ID : {$expression}); ?>" .
                    "<?php endif; ?>" .

                    "<?php if (is_array({$expression})) : ?>" .
                    "<?php \$posts
                       ->put('ignore_sticky_posts', true)
                       ->put('posts_per_page', -1)
                       ->put('post__in', collect({$expression})
                           ->map(function (\$post) {
                               return is_a(\$post, 'WP_Post') ? \$post->ID : \$post;
                           })->all())
                       ->put('orderby', 'post__in');
                   ?>" .
                    "<?php endif; ?>" .

                    "<?php \$query = \$posts->isNotEmpty() ? new WP_Query(\$posts->all()) : {$expression}; ?>" .
                    "<?php if (\$query->have_posts()) : while (\$query->have_posts()) : \$query->the_post(); ?>";
            }

            return "<?php if (empty(\$query)) : ?>" .
                "<?php global \$wp_query; ?>" .
                "<?php \$query = \$wp_query; ?>" .
                "<?php endif; ?>" .

                "<?php if (\$query->have_posts()) : ?>" .
                "<?php while (\$query->have_posts()) : \$query->the_post(); ?>";
        });

        Blade::directive('endposts', function () {
            return "<?php endwhile; wp_reset_postdata(); endif; ?>";
        });

        /*
        |---------------------------------------------------------------------
        | @hasposts / @endhasposts / @noposts / @endnoposts
        |---------------------------------------------------------------------
        */

        Blade::directive('hasposts', function ($expression) {
            if (! empty($expression)) {
                return "<?php \$posts = collect(); ?>" .

                    "<?php if (is_a({$expression}, 'WP_Post') || is_numeric({$expression})) : ?>" .
                    "<?php \$posts->put('p', is_a({$expression}, 'WP_Post') ? ({$expression})->ID : {$expression}); ?>" .
                    "<?php endif; ?>" .

                    "<?php if (is_array({$expression})) : ?>" .
                    "<?php \$posts
                       ->put('ignore_sticky_posts', true)
                       ->put('posts_per_page', -1)
                       ->put('post__in', collect({$expression})
                           ->map(function (\$post) {
                               return is_a(\$post, 'WP_Post') ? \$post->ID : \$post;
                           })->all())
                       ->put('orderby', 'post__in');
                   ?>" .
                    "<?php endif; ?>" .

                    "<?php \$query = \$posts->isNotEmpty() ? new WP_Query(\$posts->all()) : {$expression}; ?>" .
                    "<?php if (\$query->have_posts()) : ?>";
            }

            return "<?php if (empty(\$query)) : ?>" .
                "<?php global \$wp_query; ?>" .
                "<?php \$query = \$wp_query; ?>" .
                "<?php endif; ?>" .

                "<?php if (\$query->have_posts()) : ?>";
        });

        Blade::directive('endhasposts', function () {
            return "<?php wp_reset_postdata(); endif; ?>";
        });

        Blade::directive('noposts', function ($expression) {
            if (! empty($expression)) {
                return "<?php \$posts = collect(); ?>" .

                    "<?php if (is_a({$expression}, 'WP_Post') || is_numeric({$expression})) : ?>" .
                    "<?php \$posts->put('p', is_a({$expression}, 'WP_Post') ? ({$expression})->ID : {$expression}); ?>" .
                    "<?php endif; ?>" .

                    "<?php if (is_array({$expression})) : ?>" .
                    "<?php \$posts
                       ->put('ignore_sticky_posts', true)
                       ->put('posts_per_page', -1)
                       ->put('post__in', collect({$expression})
                           ->map(function (\$post) {
                               return is_a(\$post, 'WP_Post') ? \$post->ID : \$post;
                           })->all())
                       ->put('orderby', 'post__in');
                   ?>" .
                    "<?php endif; ?>" .

                    "<?php \$query = \$posts->isNotEmpty() ? new WP_Query(\$posts->all()) : {$expression}; ?>" .
                    "<?php if (! \$query->have_posts()) :";
            }

            return "<?php if (empty(\$query)) : ?>" .
                "<?php global \$wp_query; ?>" .
                "<?php \$query = \$wp_query; ?>" .
                "<?php endif; ?>" .

                "<?php if (! \$query->have_posts()) : ?>";
        });

        Blade::directive('endnoposts', function () {
            return "<?php wp_reset_postdata(); endif; ?>";
        });

        /*
        |---------------------------------------------------------------------
        | @title / @content / @excerpt / @permalink / @thumbnail
        |---------------------------------------------------------------------
        */

        Blade::directive('title', function ($expression) {
            if (! empty($expression)) {
                return "<?php get_the_title({$expression}); ?>";
            }

            return "<?php get_the_title(); ?>";
        });

        Blade::directive('content', function () {
            return "<?php the_content(); ?>";
        });

        Blade::directive('excerpt', function () {
            return "<?php the_excerpt(); ?>";
        });

        Blade::directive('permalink', function ($expression) {
            return "<?php get_permalink({$expression}); ?>";
        });

        Blade::directive('thumbnail', function ($expression) {
            if (! empty($expression)) {
                $expression = Utilities::parse($expression);

                if (! empty($expression->get(2))) {
                    if ($expression->get(2) === 'false') {
                        return "<?php get_the_post_thumbnail_url({$expression->get(0)}, is_numeric({$expression->get(1)}) ? [{$expression->get(1)}, {$expression->get(1)}] : {$expression->get(1)}); ?>"; // phpcs:ignore
                    }

                    return "<?php get_the_post_thumbnail({$expression->get(0)}, is_numeric({$expression->get(1)}) ? [{$expression->get(1)}, {$expression->get(1)}] : {$expression->get(1)}); ?>"; // phpcs:ignore
                }

                if (! empty($expression->get(1))) {
                    if ($expression->get(1) === 'false') {
                        return "<?php get_the_post_thumbnail_url(get_the_ID(), {$expression->get(0)}); ?>";
                    }

                    return "<?php get_the_post_thumbnail({$expression->get(0)}, is_numeric({$expression->get(1)}) ? [{$expression->get(1)}, {$expression->get(1)}] : {$expression->get(1)}); ?>"; // phpcs:ignore
                }

                if (! empty($expression->get(0))) {
                    if ($expression->get(0) === 'false') {
                        return "<?php get_the_post_thumbnail_url(get_the_ID(), 'thumbnail'); ?>";
                    }

                    if (is_numeric($expression->get(0))) {
                        return "<?php get_the_post_thumbnail({$expression->get(0)}, 'thumbnail'); ?>";
                    }

                    return "<?php get_the_post_thumbnail(get_the_ID(), {$expression->get(0)}); ?>";
                }
            }

            return "<?php get_the_post_thumbnail(get_the_ID(), 'thumbnail'); ?>";
        });

        /*
        |---------------------------------------------------------------------
        | @author / @authorurl / @published / @modified
        |---------------------------------------------------------------------
        */

        Blade::directive('author', function ($expression) {
            if (! empty($expression)) {
                return "<?php get_the_author_meta('display_name', {$expression}); ?>";
            }

            return "<?php get_the_author_meta('display_name'); ?>";
        });

        Blade::directive('authorurl', function ($expression) {
            if (! empty($expression)) {
                return "<?php get_author_posts_url({$expression}, get_the_author_meta('user_nicename', {$expression})); ?>";
            }

            return "<?php get_author_posts_url(get_the_author_meta('ID'), get_the_author_meta('user_nicename')); ?>";
        });

        Blade::directive('published', function ($expression) {
            if (! empty($expression)) {
                return "<?php if (is_a({$expression}, 'WP_Post') || is_int({$expression})) : ?>" .
                    "<?php get_the_date('', {$expression}); ?>" .
                    "<?php else : ?>" .
                    "<?php get_the_date({$expression}); ?>" .
                    "<?php endif; ?>";
            }

            return "<?php get_the_date(); ?>";
        });

        Blade::directive('modified', function ($expression) {
            if (! empty($expression)) {
                return "<?php if (is_a({$expression}, 'WP_Post') || is_numeric({$expression})) : ?>" .
                    "<?php get_the_modified_date('', {$expression}); ?>" .
                    "<?php else : ?>" .
                    "<?php get_the_modified_date({$expression}); ?>" .
                    "<?php endif; ?>";
            }

            return "<?php get_the_modified_date(); ?>";
        });

        /*
        |---------------------------------------------------------------------
        | @category / @categories / @term / @terms
        |---------------------------------------------------------------------
        */

        Blade::directive('category', function ($expression) {
            $expression = Utilities::parse($expression);

            if ($expression->get(1) === 'true') {
                return "<?php if (collect(get_the_category({$expression->get(0)}))->isNotEmpty()) : ?>" .
                    "<a href=\"<?php get_category_link(collect(get_the_category({$expression->get(0)}))->shift()->cat_ID); ?>\">" . // phpcs:ignore
                    "<?php collect(get_the_category({$expression->get(0)}))->shift()->name; ?>" .
                    "</a>" .
                    "<?php endif; ?>";
            }

            if (! empty($expression->get(0))) {
                if ($expression->get(0) === 'true') {
                    return "<?php if (collect(get_the_category())->isNotEmpty()) : ?>" .
                        "<a href=\"<?php get_category_link(collect(get_the_category())->shift()->cat_ID); ?>\">" .
                        "<?php collect(get_the_category())->shift()->name; ?>" .
                        "</a>" .
                        "<?php endif; ?>";
                }

                return "<?php if (collect(get_the_category({$expression->get(0)}))->isNotEmpty()) : ?>" .
                    "<?php collect(get_the_category({$expression->get(0)}))->shift()->name; ?>" .
                    "<?php endif; ?>";
            }

            return "<?php if (collect(get_the_category())->isNotEmpty()) : ?>" .
                "<?php collect(get_the_category())->shift()->name; ?>" .
                "<?php endif; ?>";
        });

        Blade::directive('categories', function ($expression) {
            $expression = Utilities::parse($expression);

            if ($expression->get(1) === 'true') {
                return "<?php get_the_category_list(', ', '', {$expression->get(0)}); ?>";
            }

            if ($expression->get(0) === 'true') {
                return "<?php get_the_category_list(', ', '', get_the_ID()); ?>";
            }


            if (is_numeric($expression->get(0))) {
                return "<?php strip_tags(get_the_category_list(', ', '', {$expression->get(0)})); ?>";
            }

            return "<?php strip_tags(get_the_category_list(', ', '', get_the_ID())); ?>";
        });

        Blade::directive('term', function ($expression) {
            $expression = Utilities::parse($expression);

            if (! empty($expression->get(2))) {
                return "<?php if (collect(get_the_terms({$expression->get(1)}, {$expression->get(0)}))->isNotEmpty()) : ?>" . // phpcs:ignore
                    "<a href=\"<?php get_term_link(collect(get_the_terms({$expression->get(1)}, {$expression->get(0)}))->shift()->term_ID); ?>\">" . // phpcs:ignore
                    "<?php collect(get_the_terms({$expression->get(1)}, {$expression->get(0)}))->shift()->name(); ?>" .
                    "</a>" .
                    "<?php endif; ?>";
            }

            if (! empty($expression->get(1))) {
                if ($expression->get(1) === 'true') {
                    return "<?php if (collect(get_the_terms(get_the_ID(), {$expression->get(0)}))->isNotEmpty()) : ?>" .
                        "<a href=\"<?php get_term_link(collect(get_the_terms(get_the_ID(), {$expression->get(0)}))->shift()->term_ID); ?>\">" . // phpcs:ignore
                        "<?php collect(get_the_terms(get_the_ID(), {$expression->get(0)}))->shift()->name(); ?>" .
                        "</a>" .
                        "<?php endif; ?>";
                }

                return "<?php if (collect(get_the_terms({$expression->get(1)}, {$expression->get(0)}))->isNotEmpty()) : ?>" . // phpcs:ignore
                    "<?php collect(get_the_terms({$expression->get(1)}, {$expression->get(0)}))->shift()->name(); ?>" .
                    "<?php endif; ?>";
            }

            if (! empty($expression->get(0))) {
                return "<?php if (collect(get_the_terms(get_the_ID(), {$expression->get(0)}))->isNotEmpty()) : ?>" .
                    "<?php collect(get_the_terms(get_the_ID(), {$expression->get(0)}))->shift()->name; ?>" .
                    "<?php endif; ?>";
            }
        });

        Blade::directive('terms', function ($expression) {
            $expression = Utilities::parse($expression);

            if ($expression->get(2) === 'true') {
                return "<?php get_the_term_list({$expression->get(1)}, {$expression->get(0)}, '', ', '); ?>";
            }

            if (! empty($expression->get(1))) {
                if ($expression->get(1) === 'true') {
                    return "<?php get_the_term_list(get_the_ID(), {$expression->get(0)}, '', ', '); ?>";
                }

                return "<?php strip_tags(get_the_term_list({$expression->get(1)}, {$expression->get(0)}, '', ', ')); ?>";
            }

            if (! empty($expression->get(0))) {
                return "<?php strip_tags(get_the_term_list(get_the_ID(), {$expression->get(0)}, '', ', ')); ?>";
            }
        });

        /*
        |---------------------------------------------------------------------
        | @image
        |---------------------------------------------------------------------
        */

        Blade::directive('image', function ($expression) {
            $expression = Utilities::parse($expression);
            $image = Utilities::strip($expression->get(0));

            if (
                is_string($image) &&
                ! is_numeric($image) &&
                $image = Utilities::field($image)
            ) {
                $expression = $expression->put(0, is_array($image) && ! empty($image['id']) ? $image['id'] : $image);
            }

            if (Utilities::strip($expression->get(1)) == 'raw') {
                return "<?php echo wp_get_attachment_url({$expression->get(0)}); ?>";
            }

            if (! empty($expression->get(3))) {
                $expression = $expression->put(2, Utilities::clean($expression->slice(2)->all()));
            }

            if (! empty($expression->get(2)) && ! Utilities::isArray($expression->get(2))) {
                $expression = $expression->put(2, Utilities::toString(['alt' => $expression->get(2)]));
            }

            if ($expression->get(1)) {
                return "<?php echo wp_get_attachment_image(
                {$expression->get(0)},
                {$expression->get(1)},
                false,
                {$expression->get(2)}
            ); ?>";
            }

            return "<?php echo wp_get_attachment_image(
            {$expression->get(0)},
            'thumbnail',
            false,
            {$expression->get(2)}
        ); ?>";
        });

        /*
        |---------------------------------------------------------------------
        | @role / @endrole / @user / @enduser / @guest / @endguest
        |---------------------------------------------------------------------
        */

        Blade::directive('role', function ($expression) {
            $expression = Utilities::parse($expression);

            return "<?php if (is_user_logged_in() && in_array(strtolower({$expression->get(0)}), (array) wp_get_current_user()->roles)) : ?>"; // phpcs:ignore
        });

        Blade::directive('endrole', function () {
            return "<?php endif; ?>";
        });

        Blade::directive('user', function () {
            return "<?php if (is_user_logged_in()) : ?>";
        });

        Blade::directive('enduser', function () {
            return "<?php endif; ?>";
        });

        Blade::directive('guest', function () {
            return "<?php if (! is_user_logged_in()) : ?>";
        });

        Blade::directive('endguest', function () {
            return "<?php endif; ?>";
        });

        /*
        |---------------------------------------------------------------------
        | @shortcode
        |---------------------------------------------------------------------
        */

        Blade::directive('shortcode', function ($expression) {
            return "<?php do_shortcode({$expression}); ?>";
        });

        /*
        |---------------------------------------------------------------------
        | @wpautop / @wpautokp
        |---------------------------------------------------------------------
        */

        Blade::directive('wpautop', function ($expression) {
            return "<?php wpautop({$expression}); ?>";
        });

        Blade::directive('wpautokp', function ($expression) {
            return "<?php wpautop(wp_kses_post({$expression})); ?>";
        });

        /*
        |---------------------------------------------------------------------
        | @thoption
        |---------------------------------------------------------------------
        */
        Blade::directive('thoption', function ($expression) {
            return "<?php get_option({$expression}); ?>";
        });

    }
}