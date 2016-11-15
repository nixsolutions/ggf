<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Ligue1Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $league = \App\League::firstOrNew([
            'name' => 'Ligue 1',
            'logoPath' => 'leagues-logo/ligue1.png'
        ]);
        $league->save();

        DB::table('teams')->insert([
            'leagueId' => $league->id,
            'name' => 'AS Monaco FC',
            'logoPath' => 'teams-logo/monaco.png',
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now()
        ]);

        DB::table('teams')->insert([
            'leagueId' => $league->id,
            'name' => 'Paris Saint-Germain',
            'logoPath' => 'teams-logo/psg.png',
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now()
        ]);
    }
}
