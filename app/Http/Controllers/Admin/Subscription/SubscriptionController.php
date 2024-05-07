<?php

namespace App\Http\Controllers\Admin\Subscription;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        return view('admin-views.subscription.index');
    }
}
