<?php

namespace ThePeach;

use yii\base\Component;

/**
 * Class Utils
 * This is a very generic component for Yii containing generic methods
 *
 * @package ThePeach
 */
class Utils extends Component
{

    /**
     * Decode the html entities even if in the wrong/partial format
     * (example: &#244 becomes ô)
     *
     * @param string $text
     *
     * @return mixed
     */
    public static function decodeBadHtmlEntities($text)
    {
        $text = html_entity_decode($text);

        return preg_replace_callback(
            '/(&#\d+);?/',
            function ($matches) {
                return (
                isset($matches[1])
                    ? mb_convert_encoding($matches[1].';', 'UTF-8', 'HTML-ENTITIES')
                    : ''
                );
            },
            $text
        );
    }

    /**
     * @param string $filePath
     *
     * @return int
     */
    public static function isRemoteUrl($filePath)
    {
        return preg_match('/^http[s]?\\:\\/\\//i', $filePath);
    }
    
} 