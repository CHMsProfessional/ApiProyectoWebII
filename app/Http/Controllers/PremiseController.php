<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Premise;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class PremiseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Premise::with("client")->get();
    }

    public function indexByClient($client_id)
    {
        $client = Client::find($client_id);
        if ($client == null) {
            return response()->json(
                array(
                    "message" => "Client not found"
                ),
                ResponseAlias::HTTP_NOT_FOUND
            );
        }
        if (Premise::with("client")->where("client_id",$client_id)->get() == "[]") {
            return response()->json(
                array(
                    "message" => "Client has no Premises"
                ),
                ResponseAlias::HTTP_NOT_FOUND
            );
        }
        return Premise::with("client","reservations")->where("client_id",$client_id)->get();
    }

    public function indexByPremise($premise_id)
    {
        $premise = Premise::find($premise_id);
        if ($premise == null) {
            return response()->json(
                array(
                    "message" => "Premise not found"
                ),
                ResponseAlias::HTTP_NOT_FOUND
            );
        }

        if (Reservation::with("client")->where("premises_id",$premise_id)->get() == "[]") {
            return response()->json(
                array(
                    "message" => "Premise has no Reservations"
                ),
                ResponseAlias::HTTP_NOT_FOUND
            );
        }

        return Reservation::with("client","premise")->where("premises_id",$premise_id)->get();
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
        $validator = Validator::make($request->json()->all(), [
            'client_id' => ['required', 'integer'],
            'titulo' => ['required', 'string'],
            'descripcion' => ['required', 'string'],
            'cantidad_habitaciones' => ['required', 'integer'],
            'cantidad_camas' => ['required', 'integer'],
            'cantidad_banos' => ['required', 'integer'],
            'max_personas' => ['required', 'integer'],
            'tiene_wifi' => ['required', 'boolean'],
            'tipo_propiedad' => ['required', 'in:0,1,2,3,4,5,6'],
            'precio_por_noche' => ['required', 'numeric'],
            'ubicacion_lat' => ['required', 'numeric'],
            'ubicacion_long' => ['required', 'numeric'],
            'ubicacion_ciudad' => ['required', 'string'],
            'tarifa_limpieza' => ['required', 'numeric'],
        ]);
        if ($validator->fails()) {
            return response()->json($validator->messages(),
                ResponseAlias::HTTP_BAD_REQUEST);
        }

        $client = Client::find($request->json()->get("client_id"));
        if ($client == null) {
            return response()->json(
                array(
                    "message" => "Client not found"
                ),
                ResponseAlias::HTTP_NOT_FOUND
            );
        }

        $premise = Premise::create($request->json()->all());

        return Premise::with("client")->find($premise->id);
    }

    /**
     * Display the specified resource.
     */
    public function show($premise_id)
    {
        $premise = Premise::with("client")->find($premise_id);
        if ($premise == null) {
            return response()->json(
                array(
                    "message" => "Premise not found"
                ),
                ResponseAlias::HTTP_NOT_FOUND
            );
        }
        return $premise;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Premise $premise)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $premise_id)
    {
        if ($request->method() == "PUT") {
            $validator = Validator::make($request->json()->all(), [
                'titulo' => ['required', 'string'],
                'descripcion' => ['required', 'string'],
                'cantidad_habitaciones' => ['required', 'integer'],
                'cantidad_camas' => ['required', 'integer'],
                'cantidad_banos' => ['required', 'integer'],
                'max_personas' => ['required', 'integer'],
                'tiene_wifi' => ['required', 'boolean'],
                'tipo_propiedad' => ['required', 'in:0,1,2,3,4,5,6'],
                'precio_por_noche' => ['required', 'numeric'],
                'ubicacion_lat' => ['required', 'numeric'],
                'ubicacion_long' => ['required', 'numeric'],
                'ubicacion_ciudad' => ['required', 'string'],
                'tarifa_limpieza' => ['required', 'numeric'],
            ]);
            if ($validator->fails()) {
                return response()->json($validator->messages(),
                    ResponseAlias::HTTP_BAD_REQUEST);
            }
        }
            $premise = Premise::find($premise_id);
            if ($premise == null) {
                return response()->json(
                    array(
                        "message" => "Premise not found"
                    ),
                    ResponseAlias::HTTP_NOT_FOUND
                );
            }
            try {
                $premise->fill($request->json()->all());
                $premise->save();
            } catch (\Exception $e) {
                return response()->json(
                    array(
                        "message" => "Error updating premise"
                    ),
                    ResponseAlias::HTTP_INTERNAL_SERVER_ERROR
                );
            }
        return Premise::with("client")->find($premise->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($premise_id)
    {
        $premise = Premise::find($premise_id);
        if ($premise == null) {
            return response()->json(
                array(
                    "message" => "Premise not found"
                ),
                ResponseAlias::HTTP_NOT_FOUND
            );
        }
        try {
            $premise->delete();
        } catch (\Exception $e) {
            return response()->json(
                array(
                    "message" => "Error deleting premise"
                ),
                ResponseAlias::HTTP_INTERNAL_SERVER_ERROR
            );
        }
        return response()->json(
            array(
                "message" => "Premise deleted successfully"
            )
        );
    }

    public function profilePicture(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            "image" => ['required', 'image']
        ]);
        if ($validator->fails()) {
            return response()->json($validator->messages(),
                ResponseAlias::HTTP_BAD_REQUEST);
        }
        $premise = Premise::find($id);
        if ($premise == null) {
            return response()->json(
                array(
                    "message" => "Premise not found"
                ),
                ResponseAlias::HTTP_NOT_FOUND
            );
        }
        $file     = $request->file('image');
        $filename = $id.'.jpg';
        $file->move('uploads/premises/', $filename);


        return Premise::with("client")->find($premise->id);
    }
}
