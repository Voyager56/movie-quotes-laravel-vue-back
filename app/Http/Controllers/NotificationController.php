<?php

namespace App\Http\Controllers;

use App\Events\NotificationUpdate;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index($id)
    {
	    NotificationUpdate::dispatch($id);
    }
}
