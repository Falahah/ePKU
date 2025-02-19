<?php
// app/Http/Controllers/ThankYouController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ThankYouController extends Controller
{
    public function show()
    {
        return view('thankyou');
    }
}
