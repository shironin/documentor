<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Field;
use App\Department;

class Doctype extends Model
{
    protected $fillable = [
        'name',
        'department_id'
    ];

    public function getFields()
    {
        return Field::all()->where('doctype',$this->id);
    }

    public function editName(String $newName)
    {
        $this->name = $newName;
        $this->save();
    }

    public function deleteAllFields()
    {
        $fields = Field::all()->where('doctype',$this->id);
        foreach ($fields as $field)
        {
            $field->delete();
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDepartment()
    {
        return $this->department_id;
    }

    public function getDepartmentName()
    {
        return Department::findOrFail($this->department_id)->name;
    }

    public function addAllNewFields(array $actualFieldsName, array $actualFieldsType)
    {
        for($i=0; $i<sizeof($actualFieldsName); $i++)
        {
            $field = new Field();
            $field->name = $actualFieldsName[$i];
            $field->type = $actualFieldsType[$i];
            $field->doctype = $this->id;
            $field->save();
        }
    }

    public function deleteAllDocs()
    {
        $documents = Document::all()->where('doctype_id',$this->id);
        foreach($documents as $doc)
        {
            $doc->deleteFile();
            $doc->delete();
        }
    }

    public function getNumberOfDocuments()
    {
        return Document::all()->where('status',100)->where('doctype_id',$this->id)->count();
    }
}
