<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Document;
use App\Department;
use App\Doctype;
use App\Field;
use App\Http\Requests;
use Illuminate\Filesystem\Filesystem;
use Auth;
use App\User;
use Storage;
use Session;
use DB;

class AjaxController extends Controller
{
    //
    public function getDoctypeFields(Request $request)
    {
        $return = array();
        $doctype = Doctype::findOrFail($request->input('id'));
        $fields = Field::all()->where('doctype', $doctype->id);
        foreach ($fields as $field)
        {
            array_push($return, $field);
        }
        return $return;
    }

    public function getAllDepartments()
    {
        $departmentsReturn = '';
        $departments = Department::all();
        foreach ($departments as $department)
        {
            $departmentsReturn .= '<option value="'.$department->id.'">'.$department->name.'</option>';
        }
        return $departmentsReturn;
    }

    public function getDepartmentDoctypes(Request $request)
    {
        $doctypesReturn = '';
        $doctypes = Doctype::all()->where("department_id",(int)$request->input('department'));
        //dd($doctypes);
        foreach ($doctypes as $doctype)
        {
            $doctypesReturn .= '<option value="'.$doctype->id.'">'.$doctype->name.'</option>';
        }
        return $doctypesReturn;
    }

    public function initiateSearchTool()
    {
        $return = array();
        $doctypesReturn = '';
        $departmentsReturn = '';
        $doctypes = Doctype::all();
        $departments = Department::all();
        foreach ($doctypes as $doctype)
        {
            $doctypesReturn .= '<option value="'.$doctype->id.'">'.$doctype->name.'</option>';
        }
        foreach ($departments as $department)
        {
            $departmentsReturn .= '<option value="'.$department->id.'">'.$department->name.'</option>';
        }
        array_push($return, $doctypesReturn);
        array_push($return, $departmentsReturn);
        return $return;
    }

    public function getSearchResult(Request $request)
    {
        $moves = '';
        $specificSearch = false;
        $searchDoctype = (int)$request->input('doctype');
        $searchDepartment = (int)$request->input('department');
        $searchNumber = (int)$request->input('document_number');
        $searchQuick = $request->input('quick_search');
        $specificFields = $request->input('specific_fields');
        $myDocs = (int)$request->input('mydocs');
        $withFiles = (int)$request->input('withfiles');

        $docs = Document::all()->where('status',100)->sortByDesc('created_at');
        if($searchDoctype != '')
        {
            $docs = $docs->where('doctype_id',$searchDoctype);
            $moves .= 'Type['.Doctype::findOrFail($searchDoctype)->name.'] ';
        }
        if($searchDepartment != '')
        {
            $docs = $docs->where('department_id',$searchDepartment);
            $moves .= 'Department['.Department::findOrFail($searchDepartment)->name.'] ';
        }
        if($searchNumber != '')
        {
            $docs = $docs->where('id',$searchNumber);
            $moves .= 'Doc.Nr['.$searchNumber.'] ';
        }
        if($searchQuick != '')
        {
            $docsIds = array();
            $ids = DB::table('documents')->where([
                ['full_content', 'LIKE', '%'.$searchQuick.'%'],
            ])->select('id')->get();
            if(sizeof($ids) == 0) return 0;
            foreach ($ids as $id)
            {
                array_push($docsIds,$id->id);
            }
            if(sizeof($docsIds)>0) $docs = $docs->whereIn('id',$docsIds);
            $moves .= 'QuickSearch['.$searchQuick.'] ';
        }


        $totalSpecificResults = 0;
        if($specificFields)
        {
            $i = 0;
            foreach (Doctype::findOrFail($searchDoctype)->getFIelds() as $field)
            {
                $docsIds = array();
                if($specificFields[$i] != '')
                {
                    $specificSearch = true;
                    if($field->type == 0) //if text field
                    {
                        $ids = DB::table('document_field')->where([
                            ['field_id', '=', $field->id],
                            ['value', 'LIKE', '%'.$specificFields[$i].'%'],
                        ])->select('document_id')->get();
                    }
                    else
                    {
                        $ids = DB::table('document_field')->where([
                            ['field_id', '=', $field->id],
                            ['value', '=', $specificFields[$i]],
                        ])->select('document_id')->get();
                    }
                    if(sizeof($ids) == 0) return 0;
                    foreach ($ids as $id)
                    {
                        if(!in_array($id->document_id,$docsIds))
                        {
                            array_push($docsIds,$id->document_id);
                            $totalSpecificResults++;
                        }
                    }
                }
                $i++;
                if(sizeof($docsIds)>0) $docs = $docs->whereIn('id',$docsIds);
            }
        }

        if($myDocs)
        {
            $docs = $docs->where('user_id',Auth::user()->id);
        }
        if($withFiles)
        {
            //$docs = $docs->where('file','1_2_9.pdf','<>');
            $docs = $docs->where('is_file',1);
        }

        if(($specificSearch && $totalSpecificResults == 0) || $docs->count() == 0) return 0;


        $page = $request->input('page');
        $docsPerPage = 20;
        $totalPages = floor($docs->count()/$docsPerPage);
        if($docs->count()/$docsPerPage > $totalPages)
        {
            $totalPages += 1;
        }

        $documents = $docs->slice(($page-1)*$docsPerPage,$docsPerPage);

        $return = '<span class="badge badge-info">'.trans('dictionary.found_docs_badge').' '.$docs->count().'</span><br><br>';

        $return .= '<div class="card" style="overflow-x:auto;"><table class="table table-hover"><colgroup>
                      <col span="1" style="width: 15%;">
                      <col span="1" style="width: 70%;">
                      <col span="1" style="width: 10%;">
                      <col span="1" style="width: 5%;">
                  </colgroup><tbody>
            <tr class="card-header">
                <td>
                    '.trans('dictionary.docs_type_header').'
                </td>
                <td>
                    '.trans('dictionary.docs_content_header').'
                </td>
                <td>
                    '.trans('dictionary.docs_user_header').'
                </td>
                <td>
                </td>
                <td>
                </td>
            </tr>';

        foreach ($documents as $document)
        {
            $icon = '';
            if($document->is_file)
            {
                $icon = '<i class="far fa-file-pdf fa-2x" style="text-align:center;display: inline-block;width: 100%;"  data-toggle="tooltip" data-placement="top" title="'.trans("dictionary.has_file_tooltip").'"></i>';
            }
            $return .= '
                <tr class="item">
                    <td>
                        '.$document->getDoctypeName().'
                    </td>
                    <td>
                        '.$document->content_string.'
                    </td>
                    <td>
                        '.$document->getUserName().'
                    </td>
                    <td>
                        '.$icon.'
                    </td>
                    <td>
                        <a href="documents/'.$document->id.'" class="btn btn-outline-primary btn-sm">'.trans('dictionary.open_doc_button').'</a>
                    </td>
                </tr>';
        }

        $return .='</tbody></table></div><br>';

        if($totalPages > 1)
        {
            $return .= $this->paginateResult($totalPages,$page);
        }

        if($moves != '') Auth::user()->addMoves(4,$moves);

        return $return;
    }

    public function paginateResult(int $totalPages, int $currentPage)
    {
        $return = '<nav aria-label="Page navigation example"><ul class="pagination">';
        if($totalPages < 10)
        {
            for($i=0;$i<$totalPages;$i++)
            {
                if($i==$currentPage-1)
                {
                    $return .= '<li class="page-item active"><a href="javascript:void(0)" class="page-link">'.strval($i+1).'</a></li>';
                }
                else
                {
                    $return .= '<li class="page-item"><a href="javascript:void(0)" onclick="doSearch('.strval($i+1).')" class="page-link">'.strval($i+1).'</a></li>';
                }
            }
        }
        else{
            if($currentPage < 6)
            {
                for($i=0;$i<$currentPage;$i++)
                {
                    if($i==$currentPage-1)
                    {
                        $return .= '<li class="page-item active"><a href="javascript:void(0)" class="page-link">'.strval($i+1).'</a></li>';
                    }
                    else
                    {
                        $return .= '<li class="page-item"><a href="javascript:void(0)" onclick="doSearch('.strval($i+1).')" class="page-link">'.strval($i+1).'</a></li>';
                    }
                }
            }
            else
            {
                $return .= '<li class="page-item"><a href="javascript:void(0)" class="page-link" onclick="doSearch('.strval(1).')">'.strval(1).'</a></li>';
                $return .= '<li class="page-item"><a class="page-link">-</a></li>';
                $return .= '<li class="page-item"><a href="javascript:void(0)" onclick="doSearch('.strval($currentPage-2).')" class="page-link">'.strval($currentPage-2).'</a></li>';
                $return .= '<li class="page-item"><a href="javascript:void(0)" onclick="doSearch('.strval($currentPage-1).')" class="page-link">'.strval($currentPage-1).'</a></li>';
                $return .= '<li class="page-item active"><a href="javascript:void(0)" class="page-link">'.strval($currentPage).'</a></li>';
            }
            if($currentPage > $totalPages-5)
            {
                for($i=$currentPage;$i<$totalPages;$i++)
                {
                    $return .= '<li class="page-item"><a href="javascript:void(0)" onclick="doSearch('.strval($i+1).')" class="page-link">'.strval($i+1).'</a></li>';
                }
            }
            else
            {
                $return .= '<li class="page-item"><a href="javascript:void(0)" onclick="doSearch('.strval($currentPage+1).')" class="page-link">'.strval($currentPage+1).'</a></li>';
                $return .= '<li class="page-item"><a href="javascript:void(0)" onclick="doSearch('.strval($currentPage+2).')" class="page-link">'.strval($currentPage+2).'</a></li>';
                $return .= '<li class="page-item"><a class="page-link">-</a></li>';
                $return .= '<li class="page-item"><a href="javascript:void(0)" class="page-link" onclick="doSearch('.strval($totalPages).')">'.strval($totalPages).'</a></li>';
            }
        }

        $return .='</ul></nav>';
        return $return;
    }

    public function getDocumentFile(Request $request)
    {
        //Storage::delete('file.jpg');
        $document = Document::findOrFail($request->input('id'));
        $path = 'documents_files/'.$document->department_id.'/'.$document->doctype_id.'/'.$document->id.'/';
        $filename = $path.$document->file;
        $pathTo = 'documents_files/'.$document->department_id.'/'.$document->doctype_id.'/'.$document->id.'/';
        $filename = $document->department_id.'_'.$document->doctype_id.'_'.$document->id.'.pdf';
        //$filename = 'ww.png';
        if (Storage::disk('local')->exists($pathTo.$filename))
        {
            $file = Storage::disk('local')->get($pathTo.$filename);
            return base64_encode($file);
        }
        else
        {
            return 'File not fount';
        }
        //return response($file, 200)->header('Content-Type', 'application/pdf');
        //return file_get_contents($pathTo.$filename);
        //return response()->download(storage_path("app/".$pathTo.$filename));
    }

//    public function attachFileToDocument(Request $request)
//    {
//        $data = base64_decode($request->input('data'));
//        $document = Document::findOrFail($request->input('id'));
//        $pathToUpload = 'documents_files/'.$document->department_id.'/'.$document->doctype_id.'/'.$document->id.'/';
//        //$originalFilename = $request->file('file')->getClientOriginalName();
//        $originalFilename = 'some.pdf';
//        //$content = file_get_contents($request->file('file')->getRealPath());
//        Storage::disk('local')->put($pathToUpload.$originalFilename, $data);
//        return $pathToUpload.$originalFilename;
//
//    }

    public function addPageToDocument(Request $request)
    {
        $document = Document::findOrFail($request->input('id'));
        if($document->file == '')
        {
            if($document->temp_file == '')
            {
                $document->addTempFileToDocument($request->input('file'));
            }
            else
            {
                $document->addTempPagesToDocument($request->input('file'));
            }
        }
        else
        {
            $document->addPagesToDocument($request->input('file'));
        }

    }

    public function deleteDocumentFile(Request $request)
    {
        $document = Document::findOrFail($request->input('id'));
        $document->deleteFile();
        $document->deleteTempFile();
    }

    public function makeOcrforDocument(Request $request)
    {
        $document = Document::findOrFail($request->input('id'));
        $url = 'https://api.ocr.space/parse/image';
        $data = array('base64Image' => 'data:application/pdf;base64,'.$request->input('file'), 'apikey' => '7b745dae7e88957', 'isOverlayRequired' => 'False');
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $json = json_decode($result,true);
        $content_string = '';
        foreach ($json['ParsedResults'] as $obj)
        {
            $content_string .= $obj['ParsedText'];
        }
        $document->setFullContentString($content_string);
        Auth::user()->addMoves(6,$request->input('id'));
    }

    public function getDocumentActivity(Request $request)
    {
        $return = '<ul class="list-group list-group-flush">';
        $actions = DB::table('moves')
            ->where('doc_number', $request->input('id'))
            ->whereIn('action', [1,2,3,6])
            ->orderBy('time', 'desc')
            ->get();
        foreach ($actions as $action)
        {
            $return .= '<li class="list-group-item"><small><span style="color:cadetblue">'.User::findOrFail($action->user_id)->name.'</span> ';
            if($action->action == 1)
            {
                $return .= trans('dictionary.created_this_doc');
            }
            else if($action->action == 2)
            {
                $return .= trans('dictionary.viewed_this_doc');
            }
            else if($action->action == 3)
            {
                $return .= trans('dictionary.edited_this_doc');
            }
            else if($action->action == 6)
            {
                $return .= trans('dictionary.ocr_this_doc');
            }
            $return .= '<span style="float:right">'.$action->time.'</span></small></li>';
        }
        $return .= '</ul>';
        return $return;
    }

    public function sendDocument(Request $request)
    {
        //dd($request->input('user'));
        foreach ($request->input('user') as $user)
        {
            DB::table('messages')->insert(
                array(
                    'sender_id' => Auth::user()->id,
                    'receiver_id' => $user,
                    'doc_number' => $request->input('id'),
                    'message' => $request->input('message')
                )
            );
        }

        return '<div class="alert alert-success" role="alert">'.trans('dictionary.doc_was_sent').'</div>';
    }

    public function readMessage(Request $request)
    {
        DB::table('messages')
            ->where('id', $request->input('id'))
            ->where('receiver_id', Auth::user()->id)
            ->update(['status' => 1]);
        return 1;
    }
}
