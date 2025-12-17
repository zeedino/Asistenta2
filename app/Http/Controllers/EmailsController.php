<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;

class EmailsController extends Controller
{
    public function welcomeEmail()
    {
        Mail::to('recipent@example.com')->send(new WelcomeEmail());

        return "Email berhasil dikirim";
    }
}
