<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\View;

class AuthController
{
    public function loginForm(): void
    {
        if (Auth::check()) {
            redirect('/admin');
        }
        View::render('auth/login', ['title' => 'تسجيل الدخول'], 'layouts/auth');
    }

    public function login(): void
    {
        Csrf::validate();
        $email = trim((string) input('email'));
        $password = (string) input('password');
        store_old(['email' => $email]);

        if ($email === '' || $password === '') {
            flash('error', 'يرجى إدخال البريد وكلمة المرور');
            redirect('/login');
        }

        if (!Auth::attempt($email, $password)) {
            flash('error', 'بيانات الدخول غير صحيحة');
            redirect('/login');
        }

        clear_old();
        redirect('/admin');
    }

    public function logout(): void
    {
        Auth::logout();
        redirect('/login');
    }
}
