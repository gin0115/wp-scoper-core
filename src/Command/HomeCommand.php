<?php

declare(strict_types=1);

namespace Gin0115\WpScoper\Command;

use Silly\Command\Command;
use Silly\Input\InputArgument;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Output\OutputInterface;

class HomeCommand extends Command {

	/** @inheritDoc */
	protected static $defaultDescription = 'Home command for WP Scoper.';

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

	 
					Whatever                                                                                                                                             
LOGO;

	/**
	 * @return string
	 */
	private function title(): string {
		return 'HI';
	}

	/**
	 * The menu items
	 *
	 * @var array{key:string,title:string,description:string}[]
	 */
	private $menuItems = [
		['key' => 's', 'command' => 'status', 'description' => 'Show status of WP Scoper.'],
		['key' => 'n', 'command' => 'new', 'description' => 'Sets up a new WP Scoper project'],
		['key' => 'h', 'command' => 'help', 'description' => 'Show Help'],
		['key' => 'q', 'command' => 'quit', 'description' => 'Quit WP Scoper.'],
	];

	/**
	 * Configure the command.
	 *
	 * @return void
	 */
	protected function configure(): void {
		// $this->addArgument( 'password', $this->requirePassword ? InputArgument::REQUIRED : InputArgument::OPTIONAL, 'User password' );
	}

	public function execute( InputInterface $input, OutputInterface $output ): int {
		$style = new SymfonyStyle( $input, $output );
		
		$style->writeln( $this->logo );
		$style->table(
            ['Key', 'Command', 'Description'],
            $this->menuItems
        );


		$value = $this->awaitMenuInput($style);

		// If quit
		if ( $value === 'q' || $value === 'quit' ) {
			return 0;
		}
		dump($value);
return Command::SUCCESS;
	}

	private function awaitMenuInput( SymfonyStyle $style ): string {
		$value = $style->ask( 'Please enter a key or a command and press enter.','quit' );
		
		if(
			!in_array($value, \array_column($this->menuItems, 'key'))
			&& !in_array($value, \array_column($this->menuItems, 'command'))
		) {
			$style->error( 'Invalid input' );
			return $this->awaitMenuInput( $style );
		}
		
		return $value;
	}


	// invoke command
	public function __invoke(
		OutputInterface $output, //phpcs:disable PEAR.Functions.ValidDefaultValue.NotAtEnd -- no control over arg order
		Input $input
	) {

		$output->writeln( $this->title() );
	}
}
