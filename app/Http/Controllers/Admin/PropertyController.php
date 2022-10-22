<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Property;
use App\Feature;
use App\PropertyImageGallery;
use App\Comment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Provincia;
use Yajra\Datatables\Datatables;
use Illuminate\Http\JsonResponse;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;
use Toastr;
use Auth;
use File;

class PropertyController extends Controller
{

    public function index(Request $request)
    {
        $properties = Property::latest()->withCount('comments')->take(5)->get();

        return view('admin.properties.index',compact('properties'));
    }

    public function allProperties() {
        /*$query = DB::table('properties')->select([
            'id',
            'image',
            'title',
            'created_at',
            'updated_at']
        );*/

        $query = Property::latest()->withCount('comments')->with('user')->get();

        return Datatables::of($query)
            ->addColumn('title', function($property) {
                return Str::limit($property->title, 10);
            })
            ->addColumn('author', function($property) {
                return $property->user->name;
            })
            ->addColumn('image', function ($property) {
                // return '<a href="#edit-'.$property->id.'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
                return '<img src="/storage/property/'.$property->image.'" alt="'.$property->title.'" width="60" class="img-responsive img-rounded">';
            })
            ->addColumn('comments_count', function($property) {
                return '<span class="badge bg-indigo">'.$property->comments_count.'</span>';
            })
            ->addColumn('star', function($property) {
                if($property->featured == true ) {
                    return '<span class="badge bg-indigo"><i class="material-icons small">star</i></span>';
                }
            })
            ->addColumn('action', function($property) {
                return $property->slug;
                /*return "<a href=\"$server/admin/properties/$property->slug\" class=\"btn btn-success btn-sm waves-effect\">
                            <i class=\"material-icons\">visibility</i>
                        </a>";*/
            })
            ->rawColumns(['image','comments_count','star','action'])
            ->make(true);
    }


    public function create()
    {   
        $features = Feature::all();
        $provincias = Provincia::all();

        return view('admin.properties.create',compact('features','provincias'));
    }


    public function store(Request $request)
    {
        define('IMG_TYPE','file|mimes:jpeg,jpg,png');
        
        $request->validate([
            'title'         => 'required|unique:properties|max:255',
            'price'         => 'required',
            'purpose'       => 'required',
            'type'          => 'required',
            'bedroom'       => 'required',
            'bathroom'      => 'required',
            'provincia_id'  => 'required',
            'canton_id'     => 'required',
            'numero_finca'  => 'required',
            'address'       => 'required',
            'area'          => 'required',
            'image'         => 'required|'.IMG_TYPE,
            //'floor_plan'=> IMG_TYPE,
            'description'   => 'required'
        ]);

        $image = $request->file('image');
        $slug  = Str::slug($request->title);

        if(isset($image)){
            $currentDate = Carbon::now()->toDateString();
            $imagename = $slug.'-'.$currentDate.'-'.uniqid().'.'.$image->getClientOriginalExtension();

            if(!Storage::disk('public')->exists('property')){
                Storage::disk('public')->makeDirectory('property');
            }
            $propertyimage = Image::make($image)->stream();
            Storage::disk('public')->put('property/'.$imagename, $propertyimage);

        }

        $floor_plan = $request->file('floor_plan');
        if(isset($floor_plan)){
            $currentDate = Carbon::now()->toDateString();
            $imagefloorplan = 'floor-plan-'.$currentDate.'-'.uniqid().'.'.$floor_plan->getClientOriginalExtension();

            if(!Storage::disk('public')->exists('property')){
                Storage::disk('public')->makeDirectory('property');
            }
            $propertyfloorplan = Image::make($floor_plan)->stream();
            Storage::disk('public')->put('property/'.$imagefloorplan, $propertyfloorplan);

        }else{
            $imagefloorplan = 'default.png';
        }

        $property = new Property();
        $property->title    = $request->title;
        $property->slug     = $slug;
        $property->price    = $request->price;
        $property->purpose  = $request->purpose;
        $property->type     = $request->type;
        $property->image    = $imagename;
        $property->bedroom  = $request->bedroom;
        $property->provincia_id  = $request->provincia_id;
        $property->canton_id  = $request->canton_id;
        $property->distrito_id  = $request->distrito_id;
        $property->barrio_id  = $request->barrio_id;
        $property->bathroom = $request->bathroom;
        $property->numero_finca     = $request->numero_finca;

        $ciudad = Provincia::findOrFail($request->provincia_id);

        $property->city = $ciudad->name;
        $property->city_slug= Str::slug($ciudad->name);
        $property->address  = $request->address;
        $property->area     = $request->area;

        if(isset($request->featured)){
            $property->featured = true;
        }
        $property->agent_id = Auth::id();
        $property->description          = $request->description;
        $property->video                = $request->video;
        $property->floor_plan           = $imagefloorplan;
        $property->location_latitude    = $request->location_latitude;
        $property->location_longitude   = $request->location_longitude;
        $property->nearby               = $request->nearby;
        $property->save();

        $property->features()->attach($request->features);


        $gallary = $request->file('gallaryimage');

        if($gallary)
        {
            foreach($gallary as $images)
            {
                $currentDate = Carbon::now()->toDateString();
                $galimage['name'] = 'gallary-'.$currentDate.'-'.uniqid().'.'.$images->getClientOriginalExtension();
                $galimage['size'] = $images->getClientSize();
                $galimage['property_id'] = $property->id;
                
                if(!Storage::disk('public')->exists('property/gallery')){
                    Storage::disk('public')->makeDirectory('property/gallery');
                }
                $propertyimage = Image::make($images)->stream();
                Storage::disk('public')->put('property/gallery/'.$galimage['name'], $propertyimage);

                $property->gallery()->create($galimage);
            }
        }

        Toastr::success('message', 'Property created successfully.');
        return redirect()->route('admin.properties.index');
    }


    public function show(Property $property)
    {
        $property = Property::withCount('comments')->find($property->id);

        $videoembed = $this->convertYoutube($property->video, 560, 315);

        return view('admin.properties.show',compact('property','videoembed'));
    }


    public function edit(Property $property)
    {
        $features = Feature::all();
        $property = Property::find($property->id);

        $videoembed = $this->convertYoutube($property->video);

        return view('admin.properties.edit',compact('property','features','videoembed'));
    }


    public function update(Request $request, $property)
    {
        $request->validate([
            'title'     => 'required|max:255',
            'price'     => 'required',
            'purpose'   => 'required',
            'type'      => 'required',
            'bedroom'   => 'required',
            'bathroom'  => 'required',
            'city'      => 'required',
            'address'   => 'required',
            'area'      => 'required',
            //'image'     => 'image|mimes:jpeg,jpg,png',
            //'floor_plan'=> 'image|mimes:jpeg,jpg,png',  
            'description'        => 'required',
            'location_latitude'  => 'required',
            'location_longitude' => 'required'
        ]);

        $image = $request->file('image');
        $slug  = Str::slug($request->title);

        $property = Property::find($property->id);

        if(isset($image)){
            $currentDate = Carbon::now()->toDateString();
            $imagename = $slug.'-'.$currentDate.'-'.uniqid().'.'.$image->getClientOriginalExtension();

            if(!Storage::disk('public')->exists('property')){
                Storage::disk('public')->makeDirectory('property');
            }
            if(Storage::disk('public')->exists('property/'.$property->image)){
                Storage::disk('public')->delete('property/'.$property->image);
            }
            $propertyimage = Image::make($image)->stream();
            Storage::disk('public')->put('property/'.$imagename, $propertyimage);

        }else{
            $imagename = $property->image;
        }


        $floor_plan = $request->file('floor_plan');
        if(isset($floor_plan)){
            $currentDate = Carbon::now()->toDateString();
            $imagefloorplan = 'floor-plan-'.$currentDate.'-'.uniqid().'.'.$floor_plan->getClientOriginalExtension();

            if(!Storage::disk('public')->exists('property')){
                Storage::disk('public')->makeDirectory('property');
            }
            if(Storage::disk('public')->exists('property/'.$property->floor_plan)){
                Storage::disk('public')->delete('property/'.$property->floor_plan);
            }

            $propertyfloorplan = Image::make($floor_plan)->stream();
            Storage::disk('public')->put('property/'.$imagefloorplan, $propertyfloorplan);

        }else{
            $imagefloorplan = $property->floor_plan;
        }

        $property->title        = $request->title;
        $property->slug         = $slug;
        $property->price        = $request->price;
        $property->purpose      = $request->purpose;
        $property->type         = $request->type;
        $property->image        = $imagename;
        $property->bedroom      = $request->bedroom;
        $property->bathroom     = $request->bathroom;
        $property->city         = $request->city;
        $property->city_slug    = Str::slug($request->city);
        $property->address      = $request->address;
        $property->area         = $request->area;

        if(isset($request->featured)){
            $property->featured = true;
        }else{
            $property->featured = false;
        }

        $property->description  = $request->description;
        $property->video        = $request->video;
        $property->floor_plan   = $imagefloorplan;
        $property->location_latitude  = $request->location_latitude;
        $property->location_longitude = $request->location_longitude;
        $property->nearby             = $request->nearby;
        $property->save();

        $property->features()->sync($request->features);

        $gallary = $request->file('gallaryimage');
        if($gallary){
            foreach($gallary as $images){
                if(isset($images))
                {
                    $currentDate = Carbon::now()->toDateString();
                    $galimage['name'] = 'gallary-'.$currentDate.'-'.uniqid().'.'.$images->getClientOriginalExtension();
                    $galimage['size'] = $images->getClientSize();
                    $galimage['property_id'] = $property->id;
                    
                    if(!Storage::disk('public')->exists('property/gallery')){
                        Storage::disk('public')->makeDirectory('property/gallery');
                    }
                    $propertyimage = Image::make($images)->stream();
                    Storage::disk('public')->put('property/gallery/'.$galimage['name'], $propertyimage);

                    $property->gallery()->create($galimage);
                }
            }
        }

        Toastr::success('message', 'Property updated successfully.');
        return redirect()->route('admin.properties.index');
    }

 
    public function destroy(Property $property)
    {
        $property = Property::find($property->id);

        if(Storage::disk('public')->exists('property/'.$property->image)){
            Storage::disk('public')->delete('property/'.$property->image);
        }
        if(Storage::disk('public')->exists('property/'.$property->floor_plan)){
            Storage::disk('public')->delete('property/'.$property->floor_plan);
        }

        $property->delete();
        
        $galleries = $property->gallery;
        if($galleries)
        {
            foreach ($galleries as $key => $gallery) {
                if(Storage::disk('public')->exists('property/gallery/'.$gallery->name)){
                    Storage::disk('public')->delete('property/gallery/'.$gallery->name);
                }
                PropertyImageGallery::destroy($gallery->id);
            }
        }

        $property->features()->detach();
        $property->comments()->delete();

        Toastr::success('message', 'Property deleted successfully.');
        return back();
    }


    public function galleryImageDelete(Request $request){
        
        $gallaryimg = PropertyImageGallery::find($request->id)->delete();

        if(Storage::disk('public')->exists('property/gallery/'.$request->image)){
            Storage::disk('public')->delete('property/gallery/'.$request->image);
        }

        if($request->ajax()){

            return response()->json(['msg' => $gallaryimg]);
        }
    }

    // YOUTUBE LINK TO EMBED CODE
    private function convertYoutube($youtubelink, $w = 250, $h = 140) {
        return preg_replace(
            "/\s*[a-zA-Z\/\/:\.]*youtu(be.com\/watch\?v=|.be\/)([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i",
            "<iframe width=\"$w\" height=\"$h\" src=\"//www.youtube.com/embed/$2\" frameborder=\"0\" allowfullscreen></iframe>",
            $youtubelink
        );
    }
}
