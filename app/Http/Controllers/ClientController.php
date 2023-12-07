<?php

namespace App\Http\Controllers;


use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ClientController extends Controller
{

    public function login(Request $request)
    {
        $validator = Validator::make($request->json()->all(), [
            "username"    => ['required', 'string'],
            "password" => ['required', 'string']
        ]);
        if ($validator->fails()) {
            return response()->json($validator->messages(),
                ResponseAlias::HTTP_BAD_REQUEST);
        }

        $client = Client::where('username', $request->json()->get('username'))->first();

        if (!$client) {
            return response()->json([
                "message" => "Cliente no encontrado"
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        $user = $client->user;

        $request->request->remove('username');
        $request->request->add([
            'email' => $user->email
        ]);

        if (!auth()->attempt($request->json()->all())) {
            return response()->json(
                array(
                    "message" => "Credenciales incorrectas"
                ),
                ResponseAlias::HTTP_UNAUTHORIZED
            );
        }

        $accessToken = auth()->user()->createToken("authToken")->plainTextToken;

        return response()->json([
            "access_token" => $accessToken,
            "propietario" => $client->propietario,
            "client_id" => $client->id,
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Client::with("user")->get();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => ['required', 'string'],
            'apellido' => ['required', 'string'],
            'username' => ['required', 'string'],
            'propietario' => ['required', 'boolean'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(),
                ResponseAlias::HTTP_BAD_REQUEST);
        }

        $clientExist = Client::where('username', $request->json()->get('username'))->first();
        if ($clientExist) {
            return response()->json([
                'message' => 'Username already exists'
            ], ResponseAlias::HTTP_CONFLICT);
        }

        $userExist = User::where('email', $request->json()->get('email'))->first();
        if ($userExist) {
            return response()->json([
                'message' => 'Email already exists'
            ], ResponseAlias::HTTP_CONFLICT);
        }

        try {
            $client = Client::create([
                'nombre' => $request->json()->get('nombre'),
                'apellido' => $request->json()->get('apellido'),
                'username' => $request->json()->get('username'),
                'propietario' => $request->json()->get('propietario'),
                'user_id' => User::create([
                    'name' => $request->json()->get('nombre'),
                    'email' => $request->json()->get('email'),
                    'password' => bcrypt($request->json()->get('password'))
                ])->id
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Client not created'
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        return Client::with('user')->find($client->id);
    }

    /**
     * Display the specified resource.
     */
    public function show($client_id)
    {
        try {
            $client = Client::findOrFail($client_id);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Client not found'
            ], ResponseAlias::HTTP_NOT_FOUND);
        }
        return Client::with('user')->find($client->id);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $client_id)
    {

        if ($request->method() == 'PUT') {
            $validator = Validator::make($request->json()->all(), [
                'nombre' => ['required', 'string'],
                'apellido' => ['required', 'string'],
                'username' => ['required', 'string'],
                //'user_id' => ['required','integer'],
                'propietario' => ['required', 'boolean']
            ]);

            if ($validator->fails()) {
                return response()->json($validator->messages(),
                    ResponseAlias::HTTP_BAD_REQUEST);
            }
        }

        $clientx = Client::where('username', $request->json()->get('username'))->first();
        if ($clientx) {
            return response()->json([
                'message' => 'Username already exists'
            ], ResponseAlias::HTTP_CONFLICT);
        }

        try {
            $client = Client::findOrFail($client_id);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Client not found'
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        if ($request->json()->get('user_id') != null){
            try {
                User::findOrFail($request->json()->get('user_id'));
            } catch (\Throwable $th) {
                return response()->json([
                    'message' => 'User not found'
                ], ResponseAlias::HTTP_NOT_FOUND);
            }
        }

        try {
            $client->fill($request->json()->all());
            $client->save();
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Client not updated'
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        return $client;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($client_id)
    {
        try {
            $client = Client::findOrFail($client_id);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Client not found'
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        try {
            $client->user()->delete();
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'User not deleted',
                'error' => $th
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        return response()->json([
            'message' => 'Client deleted'
        ], ResponseAlias::HTTP_OK);
    }

}
