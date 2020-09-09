<?php namespace Ram\Console\Commands;

use Ram\Models\Cooldown;
use Illuminate\Console\Command;

class ExpireCooldowns extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'cooldowns:expire';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Expires outstanding cooldowns';

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
		$cooldowns = Cooldown::select('id', 'user_steamid', 'type', 'expires', 'expired')
			->where('expires', '<=', time())
			->where('expired', false)
			->get();

		if ($cooldowns->count() > 0) {
			foreach ($cooldowns as $cd) {
				$cd->expired = true;
				if ($cd->save()) {
					$this->info("Expired {$cd->type} cooldown {$cd->id} for {$cd->donor}!");
				} else {
					$this->error("Failed to expire {$cd->type} cooldown {$cd->id}");
				}
			}
		} else {
			$this->info('No outstanding cooldowns.');
		}
	}
}
