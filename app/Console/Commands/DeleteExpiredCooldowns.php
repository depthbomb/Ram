<?php namespace Ram\Console\Commands;

use Ram\Models\Cooldown;
use Illuminate\Console\Command;

class DeleteExpiredCooldowns extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'cooldowns:purge';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Deletes cooldowns that have expired';

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
	public function handle()
	{
		$cooldowns = Cooldown::where('expired', true)->get();
		foreach ($cooldowns as $cd) {
			$cd->delete();
			
		}
	}
}
