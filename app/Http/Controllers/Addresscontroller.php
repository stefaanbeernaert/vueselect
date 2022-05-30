<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class Addresscontroller extends Controller
{
    //
    public function addresses(Request $request){



        return Address::query()

            ->when($request->search,function ($q) use ($request) {

                $q->where('address', 'like', '%' . $request->search . '%');

            })
            ->orderBy('address', 'ASC')->paginate(10);


    }
}
