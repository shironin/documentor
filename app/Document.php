<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Storage;
use PDFMerger;

class Document extends Model
{
    protected $fillable = [
        'type',
        'modified',
        'department',
        'comment',
        'user_id'
    ];

    public function fields()
    {
        return $this->belongsToMany('App\Field', 'document_field', 'document_id', 'field_id')
            ->withPivot('value')
            ->withTimestamps();
    }

    public function getDoctypeId()
    {
        return $this->doctype_id;
    }

    public function getDoctypeName()
    {
        return Doctype::findOrFail($this->doctype_id)->name;
    }

    public function getDepartmentName()
    {
        return Department::findOrFail($this->department_id)->name;
    }

    public function getUserName()
    {
        return User::findOrFail($this->user_id)->name;
    }

    public function getFileName()
    {
        return $this->file;
    }

    public function getDoctypeFields()
    {
        return Field::all()->where('doctype',$this->doctype_id);
    }

    public function addFileToDocument(String $file)
    {
        if($file != '')
        {
            $data = base64_decode($file);
            $pathToUpload = 'documents_files/'.$this->department_id.'/'.$this->doctype_id.'/'.$this->id.'/';
            $originalFilename = $this->department_id.'_'.$this->doctype_id.'_'.$this->id.'.pdf';
            Storage::disk('local')->put($pathToUpload.$originalFilename, $data);
            $this->file = $originalFilename;
        }
    }

    public function addPagesToDocument(String $file)
    {
        if($file != '')
        {
            $data = base64_decode($file);
            //dd($request->input('data'));
            $pathToUpload = 'documents_files/'.$this->department_id.'/'.$this->doctype_id.'/'.$this->id.'/';
            $originalFilename1 = $this->department_id.'_'.$this->doctype_id.'_'.$this->id.'.pdf';
            $originalFilename2 = 'adding_pages.pdf';
            Storage::disk('local')->put($pathToUpload.$originalFilename2, $data);

            $existingDocument = Storage::disk('local')->url($pathToUpload.$originalFilename1);
            $addingPages = Storage::disk('local')->url($pathToUpload.'adding_pages.pdf');
            $storagePath = str_replace("\\","/",Storage::disk('local')->getAdapter()->getPathPrefix());
            $existingDocument = str_replace("/storage/","",$existingDocument);
            $addingPages = str_replace("/storage/","",$addingPages);
            $pdf = new PDFMerger();
            $pdf->addPDF($storagePath.$existingDocument, 'all');
            $pdf->addPDF($storagePath.$addingPages, 'all');
            $binaryContent = $pdf->merge('string', "mergedpdf.pdf");
            Storage::disk('local')->put($pathToUpload.$originalFilename1, $binaryContent);
            Storage::disk('local')->delete($pathToUpload.$originalFilename2);
        }
    }

    public function addTempFileToDocument(String $file)
    {
        if($file != '')
        {
            $data = base64_decode($file);
            $pathToUpload = 'documents_files/temp/'.$this->id.'/';
            $originalFilename = 'temp_'.$this->id.'.pdf';
            Storage::disk('local')->put($pathToUpload.$originalFilename, $data);
            $this->temp_file = $originalFilename;
            $this->save();
        }
    }

    public function addTempPagesToDocument(String $file)
    {
        if($file != '')
        {
            $data = base64_decode($file);
            //dd($request->input('data'));
            $pathToUpload = 'documents_files/temp/'.$this->id.'/';
            $originalFilename1 = 'temp_'.$this->id.'.pdf';
            $originalFilename2 = 'adding_pages.pdf';
            Storage::disk('local')->put($pathToUpload.$originalFilename2, $data);

            $existingDocument = Storage::disk('local')->url($pathToUpload.$originalFilename1);
            $addingPages = Storage::disk('local')->url($pathToUpload.'adding_pages.pdf');
            $storagePath = str_replace("\\","/",Storage::disk('local')->getAdapter()->getPathPrefix());
            $existingDocument = str_replace("/storage/","",$existingDocument);
            $addingPages = str_replace("/storage/","",$addingPages);
            $pdf = new PDFMerger();
            $pdf->addPDF($storagePath.$existingDocument, 'all');
            $pdf->addPDF($storagePath.$addingPages, 'all');
            $binaryContent = $pdf->merge('string', "mergedpdf.pdf");
            Storage::disk('local')->put($pathToUpload.$originalFilename1, $binaryContent);
            Storage::disk('local')->delete($pathToUpload.$originalFilename2);
        }
    }

    public function linkTempFileToDoc()
    {
        if($this->temp_file != '')
        {
            $oldPath = 'documents_files/temp/'.$this->id.'/';
            $oldFilename = $this->temp_file;
            $pathToUpload = 'documents_files/'.$this->department_id.'/'.$this->doctype_id.'/'.$this->id.'/';
            $originalFilename = $this->department_id.'_'.$this->doctype_id.'_'.$this->id.'.pdf';
            Storage::disk('local')->move($oldPath.$oldFilename, $pathToUpload.$originalFilename);
            Storage::disk('local')->delete($oldPath.$oldFilename);
            $this->temp_file = '';
            $this->file = $originalFilename;
            $this->is_file = 1;
            $this->save();
        }
    }

    public function deleteFile()
    {
        if($this->file != '')
        {
            $pathToFile = 'documents_files/'.$this->department_id.'/'.$this->doctype_id.'/'.$this->id.'/';
            $filename = $this->department_id.'_'.$this->doctype_id.'_'.$this->id.'.pdf';
            Storage::disk('local')->delete($pathToFile.$filename);
            $this->file = '';
            $this->save();
        }
    }

    public function deleteTempFile()
    {
        if($this->temp_file != '')
        {
            $pathToFile = 'documents_files/temp/'.$this->id.'/';
            $filename = $this->temp_file;
            Storage::disk('local')->delete($pathToFile.$filename);
            $this->temp_file = '';
            $this->save();
        }
    }

    public function createContentString()
    {
        $str = '<small>';
        foreach ($this->fields as $field)
        {
            if($field->pivot->value != '')
            {
                if(strlen($str)>130)
                {
                    $str .= '[....]';
                    $str .= '</small>';
                    $this->content_string = $str;
                    return 0;
                }
                $str .= $field->name.': <b>'.$field->pivot->value.'</b>; ';
            }
        }
        $str .= '</small>';
        $this->content_string = $str;
        //$this->save();
    }

    public function setFullContentString(String $lines)
    {
        $this->full_content = $lines;
        $this->ocr_status = 1;
        $this->save();
    }

}
