<?php

namespace App;

class ShipyardTheme
{
    #region theme
    /**
     * Available themes:
     * - origin - separated cells, clean background, contents floating in the middle
     * - austerity - broad background, main sections spread out
     */
    public const THEME = "origin";
    #endregion

    #region colors
    /**
     * App accent colors:
     * - primary - for background, primary (disruptive) actions and important text
     * - secondary - for default buttons and links
     * - tertiary - for non-disruptive interactive elements
     */
    public const COLORS = [
        "primary" => "#9679e6",
        "secondary" => "#ff9900",
        "tertiary" => "#ff80c0",
    ];

    public static function getColors(): array
    {
        return self::COLORS;
    }

    public static function getGhostColors(): array
    {
        return array_map(
            fn ($clr) => $clr . "77",
            self::COLORS
        );
    }
    #endregion

    #region fonts
    /**
     * type in the fonts as an array
     */
    public const FONTS = [
        "base" => ["Kurale", "sans-serif"],
        "heading" => ["Dancing Script", "sans-serif"],
        "mono" => ["Ubuntu Mono", "monospace"],
    ];

    // if fonts come from Google Fonts, add the URL here
    public const FONT_IMPORT_URL = 'https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&family=Kurale&display=swap';
    #endregion
}
