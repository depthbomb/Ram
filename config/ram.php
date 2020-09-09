<?php

	return [
		'assets' => json_decode(file_get_contents(base_path('assets.json')), true),

		'server' => [
			'address' => env('SERVER_ADDRESS', null),
			'port' => env('SERVER_PORT', null),
			'rcon' => env('SERVER_RCON', null),
		],

		'features' => [

			'map' => [
				'cooldown' => 4320,	//	In minutes
				'available' => [
					['category' => 'Control Point', 'title' => 'cp_degrootcreep_b5'],
					['category' => 'Control Point', 'title' => 'cp_redfort_b3'],
					['category' => 'Control Point', 'title' => 'cp_smbcastle2'],
					['category' => 'Control Point', 'title' => 'cp_helmsdeep_v2'],
					// ['category' => 'Capture the Flag', 'title' => 'ctf_turbine_maze'],
					['category' => 'Capture the Flag', 'title' => 'ctf_aerospace_b3'],
					['category' => 'Capture the Flag', 'title' => 'ctf_facing_worlds_2011'],
					['category' => 'Capture the Flag', 'title' => 'ctf_badlands_classicb5'],
					['category' => 'Capture the Flag', 'title' => 'ctf_minecraft_town_a3a'],
					['category' => 'Capture the Flag', 'title' => 'ctf_2fooooooooooooooooort_rc3'],
					['category' => 'Capture the Flag', 'title' => 'ctf_turrrrrrrrrrrrrrrrrbine_fix3'],
					['category' => 'Capture the Flag', 'title' => 'ctf_fockgodiayeet'],
					['category' => 'Payload', 'title' => 'pl_rainbowride_b7e'],
					['category' => 'Payload', 'title' => 'pl_simpson_house_b3'],
					['category' => 'Payload', 'title' => 'pl_yamashiro_medieval_b3'],
					['category' => 'Payload', 'title' => 'pkmn_victoryroad_pl'],
					['category' => 'King of the Hill', 'title' => 'koth_wubwubwub_remix_vip'],
					['category' => 'King of the Hill', 'title' => 'koth_trainsawlaser_rc2'],
					['category' => 'Deathmatch', 'title' => 'pkmn_goldenrodcity_b3'],
					['category' => 'Deathmatch', 'title' => 'dm_halo_bloodgulch_v3'],
					['category' => 'Deathmatch', 'title' => 'walmart_bettermeme_v11b'],
					['category' => 'Surf', 'title' => 'surf_11x_tf2_complete_beta4'],
					['category' => 'Surf', 'title' => 'surf_akai_f1n4l'],
					['category' => 'Surf', 'title' => 'surf_greatfissure'],
					['category' => 'Surf', 'title' => 'surf_tf_japan_v1'],
					['category' => 'Racing', 'title' => 'balloon_race_v3_t7'],
					['category' => 'Racing', 'title' => 'wacky_races_v2']
				]
			]
		]
	];