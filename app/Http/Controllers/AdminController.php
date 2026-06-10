<?php

namespace App\Http\Controllers;

class AdminController extends Controller
{
    public function login()
    {
        // Kalau sudah ada token di cookie, redirect ke app
        // Token check dilakukan di sisi client (Alpine.js)
        return view('admin.login');
    }

    public function app()
    {
        return view('admin.app');
    }
}
