<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use App\Models\Pengampu;
use App\Models\Soal;
use Illuminate\Http\Request;

class DebugController extends Controller
{
    public function index()
    {

        $query = Pengampu

        return response()->json($query);
    }
}
