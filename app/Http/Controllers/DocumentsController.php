<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Document;
use App\Doctype;
use App\Http\Requests;
use App\Field;
use Storage;
use Illuminate\Support\Facades\Auth;
use DB;

class DocumentsController extends Controller
{
    //
    public function index()
    {
        $documents = Document::where('status',100)->paginate(2);
        return view('documents.index', compact('documents'));
    }

    public function edit($id)
    {
        $document = Document::findOrFail($id);
        return view('documents.edit', compact('document'));
    }

    public function show($id)
    {
        $document = Document::findOrFail($id);
        $users = DB::table('users')->where('id','<>',Auth::user()->id)->lists('name','id');
        Auth::user()->addMoves(2,$id);
        return view('documents.show', compact('document','users'));
    }

    public function create()
    {
        $doctypes = Doctype::lists('name','id');
        $document = new Document;
        $document->status = 0;
        $document->ocr_status = 0;
        $document->user_id = Auth::user()->id;
        $document->save();
        return view('documents.create', compact('doctypes','document'));
    }

    public function editDocument(Request $request)
    {
        $document = Document::findOrFail($request->input('document_id'));
        $fieldsOfDocument = $document->getDoctypeFields();
        $document->fields()->where('document_id', '=', $document->id)->detach();
        $i = 0;
        foreach ($fieldsOfDocument as $field_id)
        {
            $document->fields()->attach($field_id->id, ['document_id' => $document->id, 'value' => $request->input('fields_content')[$i]]);
            $i++;
        }
        $document->comment = $request->input('comment');
        $document->linkTempFileToDoc();
        $document->createContentString();
        $document->save();
        Auth::user()->addMoves(3,$request->input('document_id'));
        return redirect('documents/'.$document->id);
    }

    public function storeDocument(Request $request)
    {
        //dd(1);
        Auth::user()->addMoves(1,$request->input('document_id'));
        $this->fillDocument($request);
        return redirect('documents/'.$request->input('document_id'));
    }

    public function fillDocument(Request $request)
    {
        //dd($request);
        //$order = Order::findOrFail($request->input('order'));
        $doctype = Doctype::findOrFail($request->input('doctype'));
        $document = Document::findOrFail($request->input('document_id'));
        $document->doctype_id = $doctype->getId();
        $document->department_id = $doctype->getDepartment();
        $fieldsOfDocument = $doctype->getFields();
        $document->fields()->where('document_id', '=', $document->id)->detach();
        $i = 0;
        //dd($fieldsOfDocument);
        foreach ($fieldsOfDocument as $field_id)
        {
            //dd($request->input('fields_content'));
            $document->fields()->attach($field_id->id, ['document_id' => $document->id, 'value' => $request->input('fields_content')[$i]]);
            $i++;
        }
        //dd($request);
//        if($request->file('file')->isValid()) {
//            $pathToUpload = 'documents_files/'.$document->department_id.'/'.$document->doctype_id.'/'.$document->id.'/';
//            $originalFilename = $request->file('file')->getClientOriginalName();
//            $content = file_get_contents($request->file('file')->getRealPath());
//            Storage::disk('local')->put($pathToUpload.$originalFilename, $content);
//        }
//
//        $document->file = $originalFilename;
        //$document->addFileToDocument($request->input('data'));
        $document->comment = $request->input('comment');
        $document->linkTempFileToDoc();
        $document->createContentString();
        $document->status = 100;
        $document->save();


//        for($i=0; $i<sizeof($request->input('field_name')); $i++)
//        {
//            $field = new Field();
//            $field->name = $request->input('field_name')[$i];
//            $field->type = $request->input('type')[$i];
//            $field->doctype = $doctype->id;
//            $field->save();
//        }
        //return $document;
    }

    public function destroy($id)
    {
        $document = Document::findOrFail($id);
        $document->deleteFile();
        $document->delete();
    }

}
