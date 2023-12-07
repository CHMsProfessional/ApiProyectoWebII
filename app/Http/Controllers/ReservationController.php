<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Premise;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Reservation::with("client","premise")->get();
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
        return Reservation::with("client","premise")->where("client_id",$client_id)->get();
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
            'premises_id' => ['required', 'integer'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_final' => ['required', 'date'],
            'tarifa_airbnb' => ['required', 'numeric'],
            'costo_total' => ['required', 'numeric'],
        ]);
        if ($validator->fails()) {
            return response()->json($validator->messages(),
                ResponseAlias::HTTP_BAD_REQUEST);
        }
        $client = Client::find($request->json()->get("client_id"));
        if ($client == null) {
            return response()->json(
                array(
                    "message" => "Client no encontrado"
                ),
                ResponseAlias::HTTP_NOT_FOUND
            );
        }
        $premise = Premise::find($request->json()->get("premises_id"));
        if ($premise == null) {
            return response()->json(
                array(
                    "message" => "Premise no encontrado"
                ),
                ResponseAlias::HTTP_NOT_FOUND
            );
        }

        $reservation = Reservation::create($request->json()->all());

        return Reservation::with("client","premise")->find($reservation->id);
    }

    /**
     * Display the specified resource.
     */
    public function show($reservation_id)
    {
        $reservation = Reservation::with("client","premise")->find($reservation_id);
        if ($reservation == null) {
            return response()->json(
                array(
                    "message" => "Reservation not found"
                ),
                ResponseAlias::HTTP_NOT_FOUND
            );
        }
        return $reservation;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservation $reservation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $reservation_id)
    {
        $reservation = Reservation::find($reservation_id);
        if ($reservation == null) {
            return response()->json(
                array(
                    "message" => "Reservation not found"
                ),
                ResponseAlias::HTTP_NOT_FOUND
            );
        }

        if ($request->method() == "PUT") {
            $validator = Validator::make($request->json()->all(), [
                'client_id' => ['required', 'integer'],
                'premises_id' => ['required', 'integer'],
                'fecha_inicio' => ['required', 'date'],
                'fecha_final' => ['required', 'date'],
                'tarifa_airbnb' => ['required', 'numeric'],
                'costo_total' => ['required', 'numeric'],
            ]);
            if ($validator->fails()) {
                return response()->json($validator->messages(),
                    ResponseAlias::HTTP_BAD_REQUEST);
            }
        }

        $client = Client::find($request->json()->get("client_id"));
            if ($client == null) {
                return response()->json(
                    array(
                        "message" => "Client no encontrado"
                    ),
                    ResponseAlias::HTTP_NOT_FOUND
                );
            }

            $premise = Premise::find($request->json()->get("premises_id"));
            if ($premise == null) {
                return response()->json(
                    array(
                        "message" => "Premise no encontrado"
                    ),
                    ResponseAlias::HTTP_NOT_FOUND
                );
            }
            $reservation->fill($request->json()->all());
            $reservation->save();

            return Reservation::with("client","premise")->find($reservation->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($reservation_id)
    {
        $reservation = Reservation::find($reservation_id);
        if ($reservation == null) {
            return response()->json(
                array(
                    "message" => "Reservation not found"
                ),
                ResponseAlias::HTTP_NOT_FOUND
            );
        }
        $reservation->delete();

        return response()->json(
            array(
                "message" => "Reservation deleted successfully"
            )
        );
    }
}
