<?php

namespace App\Http\Controllers;

use App\Models\Redirect;
use App\Http\Requests\RedirectCreateRequest;
use Illuminate\Http\Request;
use App\Models\RedirectLog;
use Illuminate\Support\Facades\Http;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Redirect as LaravelRedirect;


class RedirectController extends Controller
{

    public function redirectToDestination(Request $request, Redirect $redirect)
    {
        // Registro de acesso no RedirectLog
        $logData = [
            'redirect_id' => $redirect->id,
            'ip' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'referer' => $request->header('Referer'),
            'query_params' => $this->mergeQueryParams($redirect, $request),
            'accessed_at' => now(),
        ];

        RedirectLog::create($logData);

        // Construção da URL de destino com os parâmetros da request
        $destinationUrl = $this->buildDestinationUrl($redirect, $request);

        // Redirecionamento
        return LaravelRedirect::away($destinationUrl);
    }

    public function update(Request $request, $id)
    {
        $redirect = Redirect::findOrFail($id);

        // Validar se a URL de destino está retornando status 200
        $response = Http::get($request->url);
        if ($response->status() !== 200) {
            return response()->json(['error' => 'A URL de destino não retornou um status 200.'], 400);
        }

        $redirect->update([
            'url' => $request->url,
        ]);

        return response()->json($redirect);
    }

    public function activate($id)
    {
        $redirect = Redirect::findOrFail($id);

        $redirect->update([
            'status' => true,
        ]);

        return response()->json($redirect);
    }

    public function deactivate($id)
    {
        $redirect = Redirect::findOrFail($id);

        $redirect->update([
            'status' => false,
        ]);

        return response()->json($redirect);
    }

    public function destroy($id)
    {
        $redirect = Redirect::findOrFail($id);

        $redirect->delete();

        return response()->json(['message' => 'Redirect deletado com sucesso.']);
    }


    public function index()
    {
        $redirects = Redirect::select('code', 'status', 'url', 'last_access', 'created_at', 'updated_at')->get();

        return response()->json($redirects);
    }


    public function store(RedirectCreateRequest $request)
    {
        // Validar se a URL de destino está retornando status 200
        $response = Http::get($request->url);
        if ($response->status() !== 200) {
            return response()->json(['error' => 'A URL de destino não retornou um status 200.'], 400);
        }

        $redirect = Redirect::create([
            'url' => $request->url,
            'status' => true, // Definir como ativo
        ]);

        $redirect->code = Hashids::encode($redirect->id);
        $redirect->last_access = null; // Nenhum acesso ainda
        $redirect->save();

        return response()->json($redirect, 201);
    }
}
