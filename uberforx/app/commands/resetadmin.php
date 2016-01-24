<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class resetadmin extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'resetadmin';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Resets Admin credentials';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$this->info('Working');
		$password = "'$2y$10\$hw5JGc8Sb8kSNrwqBf9TxulmWqTafnhlhOC8PY/.hkKS.EK2nrmCK'";
		DB::update("UPDATE admin SET password = $password WHERE username='admin@taxinow.com';");
		$this->info('Admin admin@taxinow.com reset done.');
	}


}
