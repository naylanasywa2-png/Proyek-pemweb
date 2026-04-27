<?php

namespace App\Controllers;

class User extends BaseController
{
    public function index()
    {
        return "<h1>Selamat Datang User!</h1><p>Halaman ini lagi dibangun sama tim Frontend. <a href='/logout'>Logout</a></p>";
    }
}