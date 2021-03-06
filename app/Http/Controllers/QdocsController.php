<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use App\Make;
use App\Type;
use App\Qdocs;
use Session;

class QdocsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search = \Request::get('search');
        if(is_null($search)){
           $qdocs = Qdocs::orderBy('id', 'desc')->paginate(5); 
        }
        else{
           $qdocs = Qdocs::where('id', 'like', '%'.$search.'%')->orwhere('c_firstname', 'like', '%'.$search.'%')->orwhere('c_lastname', 'like', '%'.$search.'%')->orwhere('license', 'like', '%'.$search.'%')->orwhere('ordernumber', 'like', '%'.$search.'%')->paginate(5); 
        }

        return view('qdocs.index', compact('qdocs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $makes = Make::all();
        $names = Config::get('constants.qdoc_names');
        $items = Config::get('constants.qdoc_items');
        $cats = Config::get('constants.qdoc_cats');
        $elements = Config::get('constants.qdoc_elements');
        $comments = Config::get('constants.qdoc_comments');
        return view('qdocs.create', compact('makes', 'names', 'items', 'cats', 'elements', 'comments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validate the data - general information
        $data = array(
            'ordernumber' => 'required|integer|unique:qdocs',
            'e_firstname' => 'required|max:32|alpha_spaces',
            'e_lastname' => 'required|max:32|alpha_spaces',
            'c_firstname' => 'required|max:32|alpha_spaces',
            'c_lastname' => 'required|max:32|alpha_spaces',
            'email' => 'required|max:29|email',
            'email_alt' => 'nullable|email',
            'phone' => 'required|digits_between:7,11|numeric',
            'make' => 'required',
            'type' => 'required|not_in:0',
            'model' => 'required|max:10|digits:4',
            'license' => 'required|max:6|alpha_num|license_type',
            'mileage' => 'required|numeric',
            'comment1' => 'max:500',
            'comment2' => 'max:500',
            'comment3' => 'max:500',
            'comment4' => 'max:500',
            'n_mileage' => 'required|numeric|greater_than:mileage',
            'e_signature' => 'required'
        );

         //Validate the data - matrix information
        $names = Config::get('constants.qdoc_names');
        $elements = Config::get('constants.qdoc_elements');
        foreach ($names as $key => $value) {
            foreach ($elements[$key] as $mat => $name) {
                $data["$name"] = 'required';            
            }
        }

        $this->validate($request, $data);

        //Store in the database - general information
        $qdocs = new Qdocs;

        $qdocs->ordernumber = $request->ordernumber;
        $qdocs->e_firstname = ucwords(strtolower($request->e_firstname));
        $qdocs->e_lastname = ucwords(strtolower($request->e_lastname));
        $qdocs->c_firstname = ucwords(strtolower($request->c_firstname));
        $qdocs->c_lastname = ucwords(strtolower($request->c_lastname));
        $qdocs->email = strtolower($request->email);
        $qdocs->email_alt = strtolower($request->email_alt);
        $qdocs->phone = $request->phone;
        $qdocs->make = $request->make;
        $qdocs->type = $request->type;
        $qdocs->model = $request->model;
        $qdocs->license = strtoupper($request->license);
        $qdocs->mileage = $request->mileage;
        $qdocs->comment1 = preg_replace_callback('/([.!?])\s*(\w)/', function($matches) {
            return strtoupper($matches[1] . ' ' . $matches[2]);
        }, ucfirst(mb_strtolower($request->comment1)));
        $qdocs->comment2 = preg_replace_callback('/([.!?])\s*(\w)/', function($matches) {
            return strtoupper($matches[1] . ' ' . $matches[2]);
        }, ucfirst(mb_strtolower($request->comment2)));
        $qdocs->comment3 = preg_replace_callback('/([.!?])\s*(\w)/', function($matches) {
            return strtoupper($matches[1] . ' ' . $matches[2]);
        }, ucfirst(mb_strtolower($request->comment3)));
        $qdocs->comment4 = preg_replace_callback('/([.!?])\s*(\w)/', function($matches) {
            return strtoupper($matches[1] . ' ' . $matches[2]);
        }, ucfirst(mb_strtolower($request->comment4)));
        $qdocs->n_mileage = $request->n_mileage;
        $qdocs->e_signature = $request->e_signature;
        $qdocs->c_signature = $request->c_signature;

        //Store in the database - matrix information
        foreach ($names as $key => $value) {
            foreach ($elements[$key] as $mat => $name) {
                $qdocs->$name = $request->$name;            
            }
        }

        //Store status on submit
        $qdocs->status = 'ok';

        //Store column position in DB
        $qdocs->doc_number = Qdocs::count() + 1;

        $qdocs->save();

        //Display a flash message on succesfull submit
        Session::flash('success', 'El documento fue creado de forma exitosa');

        //Redirect to another page
        return redirect()->route('qdocs.show', $qdocs->id); 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //Show all view in blade page
        $qdoc = Qdocs::find($id);
        $make = Make::find($qdoc->make);
        $type = Type::find($qdoc->type);
        $names = Config::get('constants.qdoc_names');
        $items = Config::get('constants.qdoc_items');
        $cats = Config::get('constants.qdoc_cats');
        $elements = Config::get('constants.qdoc_elements');
        $comments = Config::get('constants.qdoc_comments');
        $doc = $qdoc->doc_number + 1115;
        return view('qdocs.show', compact('qdoc', 'make', 'type', 'names', 'items', 'cats', 'elements', 'comments', 'doc'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $qdoc = Qdocs::find($id);
        $make_id = Make::find($qdoc->make);
        $type = Type::find($qdoc->type);
        $makes = Make::all();
        $names = Config::get('constants.qdoc_names');
        $items = Config::get('constants.qdoc_items');
        $cats = Config::get('constants.qdoc_cats');
        $elements = Config::get('constants.qdoc_elements');
        $comments = Config::get('constants.qdoc_comments');
        $doc = $qdoc->doc_number + 1115;

        //Check if document is not cancelled
        if ($qdoc->status == 'ok') {
            return view('qdocs.edit', compact('qdoc', 'make_id', 'type', 'makes', 'names', 'items', 'cats', 'elements', 'comments', 'doc'));
        }
        else{
           Session::flash('warning', 'El certificado de control calidad No.'.' '.$doc.' '.' no se puede editar ya que se encuentra cancelado');

            return redirect()->back(); 
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $qdocs = Qdocs::find($id);
        $doc = $qdocs->doc_number + 1115;

        //Validate the data - general information
        //Check if ordernumber has been changed to verify if it's value is still unique
        if ($request->input('ordernumber') == $qdocs->ordernumber) {
                $data = array(
                'e_firstname' => 'required|max:32|alpha_spaces',
                'e_lastname' => 'required|max:32|alpha_spaces',
                'c_firstname' => 'required|max:32|alpha_spaces',
                'c_lastname' => 'required|max:32|alpha_spaces',
                'email' => 'required|max:29|email',
                'email_alt' => 'nullable|email',
                'phone' => 'required|digits_between:7,11|numeric',
                'make' => 'required',
                'type' => 'required|not_in:0',
                'model' => 'required|max:10|digits:4',
                'license' => 'required|max:6|alpha_num|license_type',
                'mileage' => 'required|numeric',
                'comment1' => 'max:500',
                'comment2' => 'max:500',
                'comment3' => 'max:500',
                'comment4' => 'max:500',
                'n_mileage' => 'required|numeric|greater_than:mileage',
                'e_signature' => 'required'
            );

            //Validate the data - matrix information
            $names = Config::get('constants.qdoc_names');
            $elements = Config::get('constants.qdoc_elements');
            foreach ($names as $key => $value) {
                foreach ($elements[$key] as $mat => $name) {
                    $data["$name"] = 'required';            
                }
            }

            $this->validate($request, $data);
        }
        else {
                $data = array(
                'ordernumber' => 'required|integer|unique:qdocs',
                'e_firstname' => 'required|max:32|alpha',
                'e_lastname' => 'required|max:32|alpha',
                'c_firstname' => 'required|max:32|alpha',
                'c_lastname' => 'required|max:32|alpha',
                'email' => 'required|email',
                'email_alt' => 'nullable|email',
                'phone' => 'required|digits_between:7,10|numeric',
                'make' => 'required',
                'type' => 'required|not_in:0',
                'model' => 'required|max:10|digits:4',
                'license' => 'required|max:6|alpha_num',
                'mileage' => 'required|numeric',
                'comment1' => 'max:500',
                'comment2' => 'max:500',
                'comment3' => 'max:500',
                'comment4' => 'max:500',
                'n_mileage' => 'required|numeric|greater_than:mileage',
                'e_signature' => 'required'
            );

            //Validate the data - matrix information
            $names = Config::get('constants.qdoc_names');
            $elements = Config::get('constants.qdoc_elements');
            foreach ($names as $key => $value) {
                foreach ($elements[$key] as $mat => $name) {
                    $data["$name"] = 'required';            
                }
            }

            $this->validate($request, $data);
        }

        //Save the data to the database - general information

        $qdocs->ordernumber = $request->input('ordernumber');
        $qdocs->e_firstname = ucwords(strtolower($request->input('e_firstname')));
        $qdocs->e_lastname = ucwords(strtolower($request->input('e_lastname')));
        $qdocs->c_firstname = ucwords(strtolower($request->input('c_firstname')));
        $qdocs->c_lastname = ucwords(strtolower($request->input('c_lastname')));
        $qdocs->email = strtolower($request->input('email'));
        $qdocs->email_alt = strtolower($request->input('email_alt'));
        $qdocs->phone = $request->input('phone');
        $qdocs->make = $request->input('make');
        $qdocs->type = $request->input('type');
        $qdocs->model = $request->input('model');
        $qdocs->license = strtoupper($request->input('license'));
        $qdocs->mileage = $request->input('mileage');
        $qdocs->comment1 = preg_replace_callback('/([.!?])\s*(\w)/', function($matches) {
            return strtoupper($matches[1] . ' ' . $matches[2]);
        }, ucfirst(mb_strtolower($request->input('comment1'))));
        $qdocs->comment2 = preg_replace_callback('/([.!?])\s*(\w)/', function($matches) {
            return strtoupper($matches[1] . ' ' . $matches[2]);
        }, ucfirst(mb_strtolower($request->input('comment2'))));
        $qdocs->comment3 = preg_replace_callback('/([.!?])\s*(\w)/', function($matches) {
            return strtoupper($matches[1] . ' ' . $matches[2]);
        }, ucfirst(mb_strtolower($request->input('comment3'))));
        $qdocs->comment4 = preg_replace_callback('/([.!?])\s*(\w)/', function($matches) {
            return strtoupper($matches[1] . ' ' . $matches[2]);
        }, ucfirst(mb_strtolower($request->input('comment4'))));
        $qdocs->n_mileage = $request->input('n_mileage');
        $qdocs->e_signature = $request->input('e_signature');
        $qdocs->c_signature = $request->input('c_signature');

        //Store in the database - matrix information
        foreach ($names as $key => $value) {
            foreach ($elements[$key] as $mat => $name) {
                $qdocs->$name = $request->input($name);            
            }
        }

        $qdocs->save();

        //Display a flash message on succesfull submit
        Session::flash('success', 'El certificado de control calidad No.'.' '.$doc.' '.'fue modificado de forma exitosa');

        //Redirect to another page
        return redirect()->route('qdocs.show', $qdocs->id); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $qdocs = Qdocs::find($id);
        $doc = $qdocs->doc_number + 1115;
        $qdocs->delete();

        Session::flash('success', 'El certificado de control calidad No.'.' '.$doc.' '.' fue eliminado de forma exitosa');

        return redirect()->route('qdocs.index');
    }

    /**
     * Cancel the specified resource from storage
     *
     * @param  int $id
     * @return  \Illuminate\Http\Response
     */
    public function cancel($id)
    {
        $qdocs = Qdocs::find($id);
        $doc = $qdocs->doc_number + 1115;

        //Check for resource status
        if ($qdocs->status == 'cancelled') {
            Session::flash('warning', 'El certificado de control calidad No.'.' '.$doc.' '.'ya se encuentra cancelado');

            return redirect()->back();
        }
        else {
            $qdocs->status = 'cancelled';

            $qdocs->save();

            Session::flash('success', 'El certificado de control calidad No.'.' '.$doc.' '.'fue cancelado de forma exitosa');

            return redirect()->back();
        }
    }
}
