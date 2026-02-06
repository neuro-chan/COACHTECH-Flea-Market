<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * プロフィール登録・編集画面
     */
    public function edit(): View
    {
        $user = auth()->user()->load('profile');

        return view('mypage.edit', compact('user'));  // mypage.profile.edit → mypage.edit
    }

    /**
     * プロフィール登録処理
     */
    public function store(ProfileRequest $request): RedirectResponse
    {
        $user = auth()->user();

        // 画像アップロード処理
        $imageUrl = null;
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profiles', 'public');
            $imageUrl = Storage::url($path);
        }

        // プロフィール作成
        $user->profile()->create([
            'profile_image_url' => $imageUrl,
            'postal_code'       => $request->postal_code,
            'address'           => $request->address,
            'building'          => $request->building,
        ]);

        return redirect()->route('mypage.index')
            ->with('success', 'プロフィールを登録しました');
    }

    /**
     * プロフィール更新処理
     */
    public function update(ProfileRequest $request): RedirectResponse
    {
        $user = auth()->user();
        $profile = $user->profile;

        $data = [
            'postal_code' => $request->postal_code,
            'address'     => $request->address,
            'building'    => $request->building,
        ];

        // 画像アップロード処理
        if ($request->hasFile('profile_image')) {
            // 既存画像を削除
            if ($profile->profile_image_url) {
                $oldPath = str_replace('/storage/', '', $profile->profile_image_url);
                Storage::disk('public')->delete($oldPath);
            }

            // 新しい画像を保存
            $path = $request->file('profile_image')->store('profiles', 'public');
            $data['profile_image_url'] = Storage::url($path);
        }

        // プロフィール更新
        $profile->update($data);

        return redirect()->route('mypage.index')
            ->with('success', 'プロフィールを更新しました');
    }
}
