<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

        $data=Category::paginate(10);
        $response=[
            'status' => Response::HTTP_CREATED,
            'message'=>'data has been retrived successfully',
            'data'=>$data
        ];
        return response()->json($response);
         //code...
        } catch (\Throwable $th) {
            $response=[
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message'=>'data has been retrived successfully',
                'data'=>$data
            ];
        }

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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
