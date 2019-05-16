<?php

namespace App\Http\Controllers;

use App\Department;
use Illuminate\Http\Request;
use App\Doctype;
use App\Field;
use App\Http\Requests;
use App\Http\Requests\DoctypesRequest;

class DoctypesController extends Controller
{
    public function index()
    {
        $doctypes = Doctype::all();
        $departments = Department::lists('name','id');
        return view('doctypes.index', compact('doctypes','departments'));
    }

    public function show($id)
    {
        $doctype = Doctype::findOrFail($id);
        $fields = $doctype->getFields();
        return view('doctypes.show',compact('doctype','fields'));
    }

    public function create()
    {
        $fields = Field::lists('name','id');
        return view('doctypes.create', compact('fields'));
    }

    public function storeDoctype(Request $request)
    {
        $id = $this->createDoctype($request);
        return redirect('doctypes');
        //return redirect('doctypes/'.$id);
    }

    public function editDoctype(Request $request)
    {
        $doctype = Doctype::findOrFail($request->input('doctype_id'));
        $doctype->deleteAllFields();
        $newName = $request->input('name');
        $doctype->editName($newName);
        $actualFieldsName = $request->input('field_name');
        $actualFieldsType = $request->input('type');
        $doctype->addAllNewFields($actualFieldsName, $actualFieldsType);
        return redirect('doctypes');
    }

    public function createDoctype(Request $request)
    {
        $doctype = Doctype::create($request->all());
        for($i=0; $i<sizeof($request->input('field_name')); $i++)
        {
            $field = new Field();
            $field->name = $request->input('field_name')[$i];
            $field->type = $request->input('type')[$i];
            $field->doctype = $doctype->id;
            $field->save();
        }
        return $doctype;
    }

    public function destroy($id)
    {
        $doctype = Doctype::findOrFail($id);
        $doctype->deleteAllDocs();
        $doctype->delete();
        return redirect('doctypes');
    }

}
