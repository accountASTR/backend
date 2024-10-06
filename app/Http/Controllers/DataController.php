<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DataController extends Controller
{
    public function getData()
    {
        // Sample data for testing
        return response()->json(['message' => 'Hello from Laravel!']);
    }
}
