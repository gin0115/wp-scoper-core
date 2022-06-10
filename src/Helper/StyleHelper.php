<?php

declare(strict_types=1);

namespace Gin0115\WpScoper\Helper;

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

	 
					Whatever                                                                                                                                             
</>
LOGO;
    }
}
