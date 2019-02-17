<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function verify(Request $request)
    {
        $email = $request->input('email');
        $token = $request->input('token');

        if (!$email || !$token) {
            throw new \Exception('验证连接不正确');
        }

        if ($token != \Cache::get('email_verification_'.$email)) {
            throw new \Exception('邮箱连接不正确或已过期');
        }

        if (!$user = User::where('email', $email)->first()) {
            throw new \Exception('用户不存在');
        }

        \Cache::forget('email_verification_'.$email);

        $user->update([
            'email_verified' => true
        ]);

        $msg = '邮箱验证成功';
        return view('pages.success', compact('msg'));
    }

    public function send(Request $request)
    {
        $user = $request->user();

        if ($user->email_verified) {
            throw new \Exception('你已经验证过邮箱了');
        }

        $user->notify(new EmailVerificationNotification());

        $msg = '邮箱发送成功';

        return view('pages.success', compact('msg'));
    }
}
