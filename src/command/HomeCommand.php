<?php

/**
 * Main build command.
 */

namespace PinkCrab\Plugin_Boilerplate_Builder\Command;

use Silly\Command\Command;

class HomeCommand extends Command {


	/**
	 * @var string
	 */
	private string $logo = <<<LOGO
 _    _______   _____                           
| |  | | ___ \ /  ___|                          
| |  | | |_/ / \ `--.  ___ ___  _ __   ___ _ __ 
| |/\| |  __/   `--. \/ __/ _ \| '_ \ / _ | '__|
\  /\  | |     /\__/ | (_| (_) | |_) |  __| |   
 \/  \/\_|     \____/ \___\___/| .__/ \___|_|   
                               | |              
                               |_|              
                                                                                                                                 
LOGO;

	/**
	 * @return string
	 */
	private function title(): string {
		return <<<TITLE
            	                                                                                             v{$this->settings->getAppVersion()}
TITLE;
	}
}
