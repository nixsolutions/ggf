<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NationalTeamsFranceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $league = \App\League::where([
            'name' => 'National teams',
        ])->firstOrFail();

        DB::table('teams')->insert([
            'leagueId' => $league->id,
            'name' => 'France',
            'logoPath' => 'teams-logo/france.png',
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now()
        ]);
    }
}
