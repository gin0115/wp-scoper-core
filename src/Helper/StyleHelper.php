<?php

declare(strict_types=1);

namespace Gin0115\WpScoper\Helper;

use Symfony\Component\Console\Style\SymfonyStyle;

class StyleHelper
{
    /**
     * Returns the Application Logos
     *
     * @return string
     */
    public static function getLogo(): string
    {
        return <<<LOGO
<fg=bright-magenta>
	
                 _    _______   _____                           
                | |  | | ___ \ /  ___|                          
                | |  | | |_/ / \ `--.  ___ ___  _ __   ___ _ __ 
                | |/\| |  __/   `--. \/ __/ _ \| '_ \ / _ | '__|
                \  /\  | |     /\__/ | (_| (_) | |_) |  __| |   
                 \/  \/\_|     \____/ \___\___/| .__/ \___|_|   
                                               | |              
                                               |_|              

</>
LOGO;
    }

    /**
     * Renders the header for a commands output
     * pass the subtitle as as if it were hierarchal.
     *
     * @param \Symfony\Component\Console\Style\SymfonyStyle $style
     * @param array $subTitle
     * @return void
     */
    public static function commandHeader(SymfonyStyle $style, array $subTitle = [], bool $clear = false): void
    {
        if ($clear) {
            $style->write(sprintf("\033\143"));
        }
        $style->writeln(StyleHelper::getLogo());
        $style->block(join(' :: ', $subTitle), null, 'info', '    ');
    }
}
