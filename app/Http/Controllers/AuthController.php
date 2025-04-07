<?php

namespace App\Http\Controllers;

use App\Jobs\SendWelcomeEmail;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use App\Events\OurExampleEvent;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $incomingFields = $request->validate([
            'username' => 'required| min:3| max:32',
            Rule::unique('users', 'username'),
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'min: 8', 'confirmed']
        ]);

        // Create User.
        $user = User::create($incomingFields);

        // Add welcome email to queue
        dispatch(new SendWelcomeEmail([
            'username' => $user->username
        ]));
        
        // Log user in.
        auth()->login($user, true);

        return redirect('/')->with('success', 'Thank you for creating an account.');
    }

    public function login(Request $request)
    {
        $userCredentials = $request->validate([
            'loginusername' => ['required'],
            'loginpassword' => ['required']
        ]);

        if (
            auth()->attempt(
                ['username' => $userCredentials['loginusername'], 'password' => $userCredentials['loginpassword']]
            )
        ) {
            $request->session()->regenerate();

            event(new OurExampleEvent(['username' => auth()->user()->username, 'action' => 'login']));

            return redirect('/')->with('success', 'You have successfully logged in.');
        } else {
            return redirect('/')->with('fail', 'Invalid username or password.');
        }
    }

    public function logout(Request $request)
    {
        event(new OurExampleEvent(['username' => auth()->user()->username, 'action' => 'login']));

        auth()->logout();

        return redirect('/')->with('success', 'You are logged out.');
    }

    public function showAvatarForm()
    {
        return view('avatar-form');
    }

    public function saveAvatar(Request $request)
    {

        $request->validate([
            'avatar' => 'required | image | max: 8012', // max: 8012 KILOBYTES (8MB) - file size
        ]);

        $user = auth()->user();
        $upload = $request->file('avatar');

        // Resize to 120x120
        $image = Image::read($upload)->resize(120, 120);
        $filename = $user->username . uniqid() . ".{$upload->getClientOriginalExtension()}";

        // encode by file's extension (get file extension from file's getCLientOriginalExtension function)
        Storage::disk('public')
            ->put("avatars/{$filename}", $image->encodeByExtension($upload->getClientOriginalExtension(), quality: 50));

        $oldAvatar = $user->avatar;
        // Saving avatar to DB
        $user->avatar = $filename;
        $user->save();

        // Check if avatar was updated successfully if yes, delete old avatar
        if ($oldAvatar !== '/fallback-avatar.jpg') {
            Storage::disk('public')->delete(str_replace('/storage/', '', $oldAvatar));
        }

        // Create a new redirect response to the previous location.
        return back()->with('success', 'Avatar was updated successfully');
    }


    public function LoginApi(Request $request)
    {
        $userData = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if (!auth()->attempt($userData)) {
            return response()->json([
                'message' => 'Invalid Credentials'
            ]);
        }
        
        // Get user by username (if validation succeded)
        $user = User::where('username', '=', $userData['username'])->first();
        $token = $user->createToken('bloggertoken')->plainTextToken;
        return $token;
    }
}
