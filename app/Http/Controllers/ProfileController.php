<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function myProfile()
    {
        $user = User::where('id', $this->user->id)->first();

        return view('pages.user.profile', compact('user' ));
    }

    public function changePassword(Request $request)
    {
        $user = User::where('id', $this->user->id)->first();

        // Check if password is match.
        if (!Hash::check($request->password, $user->password))
            return redirect()->route('profile.index')->with('error', 'Senha antiga incorreta!');

        $newPwd = Hash::make($request->newPassword);

        if (strlen($newPwd) <= 5)
            return redirect()->route('profile.index')->with('error', 'Sua deve conter 6 ou mais caracteres!');

        $user->password = $newPwd;
        $user->save();

        return redirect()->route('profile.index')->with('success', 'Senha alterada com sucesso!');
    }
}
