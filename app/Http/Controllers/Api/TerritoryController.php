<?php

namespace App\Http\Controllers\Api;

use App\Barrio;
use App\Canton;
use App\Distrito;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Provincia;

class TerritoryController extends Controller
{
    public function index() {
        return Provincia::all(['id','name']);
    }

    public function cantones($provinciaId) {
        return Canton::where('provincia_id', $provinciaId)->get(['id','name']);
    }

    public function distritos($cantonId) {
        return Distrito::where('canton_id', $cantonId)->get(['id','name']);
    }

    public function barrios($distritoId) {
        return Barrio::where('distrito_id', $distritoId)->get(['id','name']);
    }
}
