<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\User;
use Illuminate\Http\Request;

class Usercontroller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('test');
        /* return response()->json(compact([
             'users'
         ]));*/
    }

    public function users(Request $request)
    {
        if($request->id){
            return User::query()->findOrFail($request->id);
        }else{
            return User::query()
                ->when($request->addressId, function ($query) use ($request) {
                    $query->whereHas('addresses', function ($query) use ($request) {
                        $query->where('id', $request->addressId);
                    });
                })
                ->when($request->userId, function ($q) use ($request) {
                    $q->where('id', $request->userId);
                })
                ->when($request->search, function ($q) use ($request) {

                    $q->where('name', 'like', '%' . $request->search . '%');

                })
                ->orderBy('name', 'ASC')->paginate(10);
        }



    }

   /* public function getItem(Request $request)
    {

        return User::query()->findOrFail($request->id);
/*        return User::query()
            ->when($request->addressId, function ($query) use ($request) {
                $query->whereHas('addresses', function ($query) use ($request) {
                    $query->where('id', $request->addressId);
                });
            })->first();
    }*/

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
