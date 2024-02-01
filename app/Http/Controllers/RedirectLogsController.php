<?php
// app/Http/Controllers/RedirectLogsController.php

namespace App\Http\Controllers;

use App\Models\Redirect;
use Illuminate\Http\Request;

class RedirectLogsController extends Controller
{
    public function index(Request $request, Redirect $redirect)
    {
        // Implementação de exemplo:
        $logs = $redirect->logs()->latest()->get();
        // Supondo que você tenha um relacionamento "logs" definido no modelo Redirect

        return response()->json(['logs' => $logs]);
    }
}
