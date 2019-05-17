<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'name',
        'address',
        'comment',
        'contact'
    ];

    public function editName(String $newName)
    {
        $this->name = $newName;
        $this->save();
    }

    public function editContact(String $newContact)
    {
        $this->contact = $newContact;
        $this->save();
    }

    public function editAddress(String $newAddress)
    {
        $this->address = $newAddress;
        $this->save();
    }

    public function editComment(String $newComment)
    {
        $this->comment = $newComment;
        $this->save();
    }

    public function getAllDoctypes()
    {
        return Doctype::all()->where('department_id',$this->id);
    }

    public function getNumberOfDocuments()
    {
        return Document::all()->where('department_id',$this->id)->count();
    }
}
