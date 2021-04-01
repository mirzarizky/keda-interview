<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (empty($user)) {
            throw ValidationException::withMessages(['email' => 'Incorrect email or password.']);
        } else {
            if (!Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages(['email' => 'Incorrect email or password.']);
            }
            else {
                return response()->json([
                    'user' => $user,
                    'access_token' => $this->generateToken($user)
                ]);
            }
        }
    }

    protected function generateToken(User $user)
    {
        $token = $user->createToken('Authentication Token');

        return $token->plainTextToken;
    }
}
