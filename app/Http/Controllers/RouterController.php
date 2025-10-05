<?php

namespace App\Http\Controllers;

use App\Models\Router;
use App\Http\Requests\StorerouterRequest;
use App\Http\Requests\UpdaterouterRequest;
use Illuminate\Http\Request;

class RouterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request )
    {
        $perPage = $request->integer('per_page', 5);

        if ($request->has('search')) {
            $keyword = $request->query('search');
            return Router::search($keyword)->paginate($perPage);
        }

        if($request->has('mac_address')){
            return Router::where('mac_address', $request->query('mac_address'))->firstOrFail();

        }

        return Router::paginate($perPage);
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
    public function store(StorerouterRequest $request)
    {
        $router = Router::create($request->validated()); // validate là dữ liệu đã được xác thực
        return response() ->json($router, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Router $router)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Router $router)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdaterouterRequest $request, Router $router)
    {
        $router->update($request->validated());
        return response()->json($router->fresh());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Router $router)
    {
        $router->delete();
       return response()->noContent(); 
    }
}





