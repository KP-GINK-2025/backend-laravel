<?php

namespace App\Http\Controllers;

use App\Models\Setting;

class FrontendController extends Controller {
    public function index() {
        $data = Setting::whereIn('name', ['web_title', 'web_description'])->get();
        $identity = Setting::whereIn('name', ['logo_one', 'logo_two', 'title_one', 'title_two'])->get();
        $config = [
            'title' => $data[0]->value,
            'description' => $data[1]->value,
            'first_logo' => $identity[0]->value,
            'second_logo' => $identity[1]->value,
            'first_title' => $identity[2]->value,
            'second_title' => $identity[3]->value
        ];
        return view('index', compact('config', 'data'));
    }

    public function login() {
        return redirect()->route('admin.login');
    }
}