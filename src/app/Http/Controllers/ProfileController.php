<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{

    public function edit(): View
    {
        $user = auth()->user()->load('profile');

        return view('mypage.edit', compact('user'));  // mypage.profile.edit → mypage.edit
    }

    // プロフィール登録処理
    public function store(ProfileRequest $request): RedirectResponse
    {
        $user = auth()->user();

        $imageUrl = null;
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profiles', 'public');
            $imageUrl = Storage::url($path);
        }

        $user->profile()->create([
            'profile_image_url' => $imageUrl,
            'postal_code'       => $request->postal_code,
            'address'           => $request->address,
            'building'          => $request->building,
        ]);

        return redirect()->route('mypage.index')
            ->with('success', 'プロフィールを登録しました');
    }


    // プロフィール更新処理
    public function update(ProfileRequest $request): RedirectResponse
    {
        $user = auth()->user();
        $profile = $user->profile;

        $data = [
            'postal_code' => $request->postal_code,
            'address'     => $request->address,
            'building'    => $request->building,
        ];

        if ($request->hasFile('profile_image')) {

            if ($profile->profile_image_url) {
                $oldPath = str_replace('/storage/', '', $profile->profile_image_url);
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('profile_image')->store('profiles', 'public');
            $data['profile_image_url'] = Storage::url($path);
        }

        $profile->update($data);

        return redirect()->route('mypage.index')
            ->with('success', 'プロフィールを更新しました');
    }
}
