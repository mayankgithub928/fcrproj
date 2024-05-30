<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Country;
use App\Models\State;
use Illuminate\Support\Facades\Validator;
use DataTables;

class EmployeeCtrl extends Controller
{
    public function index(){

        $countries = Country::all();
        
        return view('employee',['countries'=>$countries]);
    }

    public function employeeList(Request $request){
       // die();
        if($request->ajax()){

            $data = Employee::with(['country','state'])->latest()->get();

            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('country_name', function($data) {
                return $data->country->name;
            })
            ->addColumn('state_name', function($data) {
                return $data->state->name;
            })
            ->addColumn('action',function($row){
                $actionBtn = '<a href="javascript:void(0);" class="edit btn btn-success btn-sm" id="editEmpBtn" onclick="editEmployee('.$row->id.');"> Edit </a> <a href="javascript:void(0);" class="delete btn btn-danger btn-sm" onclick="deleteEmployee('.$row->id.');">Delete</a>';
                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
    }
    
    public function addEmployee(Request $request){
        //dd($request->email);
        $validator= Validator::make($request->all(),[
            'name'=>'required|min:5|max:15',
            "email"=> "required|email|unique:employees,email",
            'country'=>'required',
            'state'=>'required',
        ]);
        if($validator->passes()){
            $employee= new Employee();
            $employee->name=$request->name;
            $employee->email=$request->email;
            $employee->country_id=$request->country;
            $employee->state_id=$request->state;

            $employee->save();
            session()->flash('success','Employee added susccesfully');
            return response()->json(
                [
                    'status'=>true,
                    'errors'=>[]
                ]

            );

        }else{
            return response()->json([
              'status'=>false, 
              'errors'=>$validator->errors()
          ]);
        }
    
    }
    public function editEmployee($empId){
        $employee = Employee::find($empId);
        return response()->json($employee);
    }
    public function updateEmployee(Request $request){
        //dd($request->email);
        $validator= Validator::make($request->all(),[
            'name'=>'required|min:5|max:15',
            'email'=>'required|email|unique:employees,email,'.$request->emp_id.',id',
            'country'=>'required',
            'state'=>'required',
        ]);
        if($validator->passes()){
            $employee= Employee::find($request->emp_id);
            $employee->name=$request->name;
            $employee->email=$request->email;
            $employee->country_id=$request->country;
            $employee->state_id=$request->state;

            $employee->save();
            session()->flash('success','Employee updated susccesfully');
            return response()->json(
                [
                    'status'=>true,
                    'errors'=>[]
                ]

            );

        }else{
            return response()->json([
              'status'=>false, 
              'errors'=>$validator->errors()
          ]);
        }

        
    }
    public function getStates($country_id)
    {
        $states = State::where('country_id', $country_id)->pluck('name', 'id');
        return response()->json($states);
    }
    public function deleteEmployee($emp_id)
    { 
        //dd($emp_id);
        $employee = Employee::findOrFail($emp_id);
        $employee->delete();
        session()->flash('success','Employee deleted successfully');
        return response()->json(
            [
                'status'=>true,
                'errors'=>[]
            ]

        );
    }
}
