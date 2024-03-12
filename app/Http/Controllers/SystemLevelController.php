<?php

namespace App\Http\Controllers;

use App\CrashBets;
use Illuminate\Http\Request;

use App\User;
use App\SystemLevel;

class SystemLevelController extends Controller
{
    public function teste()
    {

        $Levels = SystemLevel::all();

        foreach ($Levels as $level) {
            echo 'Level: <b>' . $level->level . '</b> | Score Required: <b>' . $level->score . '</b>';
            echo "</br>";
        }
        echo "</br><hr></br>";

        $userScore = User::select('score_level')->where('id', 44)->first();

        echo "My User Score: <b>" . $userScore->score_level . "</b>";
        echo "<br>";

        $userLevel = SystemLevel::where('score', '<', $userScore->score_level)->orderBy('level', 'desc')->first();

        echo "My User Level: <b>" . $userLevel->level . "</b>";
        echo "<br>";

    }

    public function teste2()
    {
        //dd($this->user->level);

        /*$level = (new SystemLevel())->getLevelNameFromUser($this->user->level);
        dd($level);*/

        $users = User::where('fake', 1)->get();

        foreach ($users as $user) {

            $bets = CrashBets::where([
                'user_id' => $user->id,
                'round_id' => 50150
            ])->get();

            foreach ($bets as $bet) {
                echo $bet->price . '<br>';
            }
        }
    }
}
