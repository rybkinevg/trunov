<?php

/**
 * Cuts the specified text up to specified number of characters.
 * Strips any of shortcodes.
 *
 * @author Kama (wp-kama.ru)
 *
 * @version 2.7.1
 *
 * @param string|array $args {
 *     Optional. Arguments to customize output.
 *
 *     @type int       $maxchar            Макс. количество символов.
 *     @type string    $text               Текст который нужно обрезать. По умолчанию post_excerpt, если нет post_content.
 *                                         Если в тексте есть `<!--more-->`, то `maxchar` игнорируется и берется
 *                                         все до `<!--more-->` вместе с HTML.
 *     @type bool      $autop              Заменить переносы строк на `<p>` и `<br>` или нет?
 *     @type string    $more_text          Текст ссылки `Читать дальше`.
 *     @type string    $save_tags          Теги, которые нужно оставить в тексте. Например `'<strong><b><a>'`.
 *     @type string    $sanitize_callback  Функция очистки текста.
 *     @type bool      $ignore_more        Нужно ли игнорировать <!--more--> в контенте.
 *
 * }
 *
 * @return string HTML
 */

function kama_excerpt($args = '')
{
    global $post;

    if (is_string($args)) {
        parse_str($args, $args);
    }

    $rg = (object) array_merge([
        'maxchar'           => 350,
        'text'              => '',
        'autop'             => true,
        'more_text'         => 'Читать дальше...',
        'ignore_more'       => false,
        'save_tags'         => '',
        'sanitize_callback' => 'strip_tags',
    ], $args);

    $rg = apply_filters('kama_excerpt_args', $rg);

    if (!$rg->text) {
        $rg->text = $post->post_excerpt ?: $post->post_content;
    }

    $text = $rg->text;
    // strip content shortcodes: [foo]some data[/foo]. Consider markdown
    $text = preg_replace('~\[([a-z0-9_-]+)[^\]]*\](?!\().*?\[/\1\]~is', '', $text);
    // strip others shortcodes: [singlepic id=3]. Consider markdown
    $text = preg_replace('~\[/?[^\]]*\](?!\()~', '', $text);
    $text = trim($text);

    // <!--more-->
    if (!$rg->ignore_more && strpos($text, '<!--more-->')) {
        preg_match('/(.*)<!--more-->/s', $text, $mm);

        $text = trim($mm[1]);

        $text_append = ' <a href="' . get_permalink($post) . '#more-' . $post->ID . '">' . $rg->more_text . '</a>';
    }
    // text, excerpt, content
    else {

        $text = 'strip_tags' === $rg->sanitize_callback
            ? strip_tags($text, $rg->save_tags)
            : call_user_func($rg->sanitize_callback, $text, $rg);

        $text = trim($text);

        // cut
        if (mb_strlen($text) > $rg->maxchar) {
            $text = mb_substr($text, 0, $rg->maxchar);
            $text = preg_replace('/(.*)\s[^\s]*$/s', '\\1...', $text); // del last word, it not complate in 99%
        }
    }

    // add <p> tags. Simple analog of wpautop()
    if ($rg->autop) {
        $text = preg_replace(
            ["/\r/", "/\n{2,}/", "/\n/", '~</p><br ?/?>~'],
            ['', '</p><p>', '<br />', '</p>'],
            $text
        );
    }

    $text = apply_filters('kama_excerpt', $text, $rg);

    if (isset($text_append)) {
        $text .= $text_append;
    }

    return ($rg->autop && $text) ? "<p>$text</p>" : $text;
}

/* Сhangelog:
 * 2.7.0 - Параметр sanitize_callback
 * 2.6.5 - Параметр ignore_more
 * 2.6.4 - Убрал пробел между словом и многоточием
 * 2.6.3 - Рефакторинг
 * 2.6.2 - Добавил регулярку для удаления блочных шорткодов вида: [foo]some data[/foo]
 * 2.6   - Удалил параметр 'save_format' и заменил его на два параметра 'autop' и 'save_tags'.
 *       - Немного изменил логику кода.
 */