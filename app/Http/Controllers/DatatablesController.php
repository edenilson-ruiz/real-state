<?php

namespace App\Http\Controllers;

use App\Property;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use Illuminate\Http\JsonResponse;

class DatatablesController extends Controller
{
    /**
     * Displays datatables front end view
     *
     * @return \Illuminate\View\View
     */
    public function getIndex()
    {
        return view('datatables.index');
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function anyData()
    {
        return Datatables::of(User::query())->make(true);
    }

    public function allProperties() {
        $query = DB::table('properties')->select(['id', 'image', 'title', 'created_at', 'updated_at']);

        return Datatables::of($query)
            ->addColumn('action', function ($property) {
                // return '<a href="#edit-'.$property->id.'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
                return '<img src="/storage/property/'.$property->image.'" alt="'.$property->title.'" width="60" class="img-responsive img-rounded">';
            })->make(true);
    }
}
