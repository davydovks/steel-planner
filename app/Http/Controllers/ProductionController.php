<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class ProductionController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Production/Index');
    }
}
