<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class SystemLevel extends Model
{
    protected $table = 'system_levels';

    protected $fillable = [
        'level',
        'score'
    ];

    public function getLevelNameFromUser($lvl)
    {
        // Get Level list
        $getLevel = self::where('level', $lvl)->orderBy('level', 'desc')->first();

        //$getLevelName = 'starter';

        /**
         *   Level's settings
         *
         *   0-1 - Iniciante
         *   2-10 - Bronze
         *   11-24 - Prata
         *   25-34 - Ouro
         *   35-45 - Platina
         */

        switch ($getLevel->level) {
            case 0:
            case 1:
                $getLevelName = "starter";
                break;
            case 2:
            case 3:
            case 4:
            case 5:
            case 6:
            case 7:
            case 8:
            case 9:
            case 10:
                $getLevelName = "bronze";
                break;
            case 11:
            case 12:
            case 13:
            case 14:
            case 15:
            case 16:
            case 17:
            case 18:
            case 19:
            case 20:
            case 21:
            case 22:
            case 23:
            case 24:
                $getLevelName = "silver";
                break;
            case 25:
            case 26:
            case 27:
            case 28:
            case 29:
            case 30:
            case 31:
            case 32:
            case 33:
            case 34:
                $getLevelName = "gold";
                break;
            case 35:
            case 36:
            case 37:
            case 38:
            case 39:
            case 40:
            case 41:
            case 42:
            case 43:
            case 44:
                $getLevelName = "platinum";
                break;
            default:
                $getLevelName = "none";
        }

        return $getLevelName;
        //return array($getLevelName, $getLevel->score/*, $user->score_level*/);
    }

    public function setLevelScoreForUser($u, $value = 0)
    {
        // Calc the percent of SCORE for every value BET
        $wonScore = $this->calcScoreForBET($value);

        // Search user
        $user = User::where('id', $u->id)->first();

        // Get another LEVEL
        $getAnotherLevelByScore = self::where('score', '>', $user->score_level_current)
            ->orderBy('level', 'asc')
            ->first();

        $calcForNextLevel = $user->score_level_current - $getAnotherLevelByScore->score;

        // Check 'score_level' from user and reset (Max. is 100.00%)
        if ($user->score_level >= 100.00 || ($user->score_level + $wonScore) >= 100.00) {

            // Level UP and reset progress score
            $user->level = $getAnotherLevelByScore->level; // UP new level
            $user->score_level = $wonScore; // reset progress
            $user->score_level_current += $wonScore; // Never reset!
            $user->save();
        } else {

            // Only add new score
            $user->score_level += $wonScore; // progress
            $user->score_level_current += $wonScore; // Never reset!
            $user->save();
        }

        /*return response()->json(['success' => true, 'CalcForNextLevel' => $calcForNextLevel,
            'User' => [
                'score_level' => $user->score_level,
                'score_level_current' => $user->score_level_current,
                'level' => $user->level
            ]
        ]);*/
    }

    public function calcScoreForBET($value)
    {
        /**
         * <= R$ 1.00 - 0.25%
         * >= R$ 1.01 - 0.50%
         * >= R$ 5.00 - 2.50%
         * >= R$ 10.00 - 5.00%
         * >= R$ 100.00 - 50.00%
         */

        if ($value >= 0.01 && $value <= 1.50)
            return 0.25;

		 if ($value >= 1.51 && $value <= 5.00)
            return 2.50;

        if ($value >= 5.01 && $value <= 8.00)
            return 3.75;

        if ($value >= 8.01 && $value <= 10.00)
            return 5.00;

        if ($value >= 10.01 && $value <= 15.00)
            return 7.50;

		   if ($value >= 15.01 && $value <= 20.00)
            return 10.00;

		 if ($value >= 20.01 && $value <= 30.00)
            return 15.00;

		 if ($value >= 30.01 && $value <= 40.00)
            return 20.00;

		 if ($value >= 40.01 && $value <= 50.00)
            return 25.00;

		 if ($value >= 50.01 && $value <= 60.00)
            return 30.00;

		 if ($value >= 60.01 && $value <= 70.00)
            return 40.00;

		 if ($value >= 70.01 && $value <= 80.00)
            return 45.00;

		 if ($value >= 80.01 && $value <= 100.00)
            return 50.00;


        //return $this->settings->level_point_withdraw;
    }
}
