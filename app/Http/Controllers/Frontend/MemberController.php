<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Requests\MemberAvatarChangeRequest;
use App\Http\Requests\MemberChangePassword;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class MemberController extends BaseController
{

    public function index()
    {
        return view('frontend.member.index');
    }

    public function showPasswordChangePage()
    {
        return view('frontend.member.password_change');
    }

    public function passwordChangeHandler(MemberChangePassword $request)
    {
        $oldPassword = $request->input('old_password');
        $newPassword = $request->input('new_password');

        if (! Hash::check($oldPassword, $this->user->getAuthPassword())) {
            flash()->error('原密码错误');
            return back();
        }

        $user = Auth::user();
        $user->password = bcrypt($newPassword);
        $user->save();

        flash()->success('密码修改成功');
        return back();
    }

    public function showAvatarChangePage()
    {
        return view('frontend.member.avatar_change');
    }

    public function avatarChangeHandler(MemberAvatarChangeRequest $request)
    {
        $image = $request->file('file');
        $path = $image->store('/avatar', ['disk' => 'public']);
        $url = Storage::disk('public')->url($path);

        $user = Auth::user();
        $user->avatar = $url;
        $user->save();

        flash()->success('头像修改成功');
        return back();
    }

}
