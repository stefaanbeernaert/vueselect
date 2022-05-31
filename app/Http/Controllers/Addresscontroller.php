<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class Addresscontroller extends Controller
{
    //
    public function addresses(Request $request){


       // dd($request->searchParams);
        return Address::query()
            ->when($request->userId,function ($q) use ($request){
                $q->where('user_id', 'like', '%' . $request->userId . '%');
            })
            ->when($request->search,function ($q) use ($request) {

                $q->where('address', 'like', '%' . $request->search . '%');

            })
            ->orderBy('address', 'ASC')->paginate(10);


    }
}
