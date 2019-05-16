<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Department;

class DepartmentsController extends Controller
{
    public function index()
    {
        $departments = Department::all();
        return view('departments.index', compact('departments'));
    }

    //   public function show($id)
    //   {
    //       $route = Route::findOrFail($id);
    // return view('routes.show',compact('route'));
    //   }

    public function show($id)
    {
        $department = Department::findOrFail($id);
        return view('departments.show',compact('department'));
    }

    public function create(Request $request)
    {
        $this->createDepartment($request);
        return view('departments');
    }

    public function storeDepartment(Request $request)
    {
        $this->createDepartment($request);
        return redirect('departments');
    }

    public function editDepartment(Request $request)
    {
        $department = Department::findOrFail($request->input('department_id'));
        $department->editName($request->input('name'));
        $department->editContact($request->input('contact'));
        $department->editAddress($request->input('address'));
        $department->editComment($request->input('comment'));
        return redirect('departments');
    }

    public function destroy($id)
    {
        $department = Department::findOrFail($id);
        $doctypes = $department->getAllDoctypes();
        foreach ($doctypes as $doctype)
        {
            $doctype->deleteAllDocs();
            $doctype->delete();
        }
        $department->delete();
        return redirect('departments');
    }

    private function createDepartment(Request $request)
    {
        $department = Department::create($request->all());
        return $department;
    }
}
