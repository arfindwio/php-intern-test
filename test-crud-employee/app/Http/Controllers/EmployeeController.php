<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redis;

class EmployeeController extends Controller
{
    public function getEmployees()
    {
        $employees = Employee::all();
        return response()->json($employees);
    }

    public function getEmployeeById($id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json(['message' => 'Employee not found']);
        }

        Redis::set("emp_{$employee->nomor}", $employee->toJson());
        
        return response()->json($employee);
    }

    public function createEmployee(Request $request)
    {
        $data = [
            'nomor' => $request->nomor,
            'nama' => $request->nama,
            'jabatan' => $request->jabatan,
            'talahir' => $request->talahir,
            'created_by' => $request->created_by,
        ];

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('employees', 's3');
            $data['photo_upload_path'] = Storage::disk('s3')->url($path);
        }

        
        $employee = Employee::create($data);
        
        Redis::set("emp_{$employee->nomor}", $employee->toJson());

        return response()->json($employee);
    }

    public function updateEmployee(Request $request, $id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json(['message' => 'Employee not found']);
        }

        $employee->nama = $request->nama;
        $employee->jabatan = $request->jabatan;
        $employee->talahir = $request->talahir;
        $employee->updated_by = $request->updated_by;

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('employees', 's3');
            $employee->photo_upload_path = Storage::disk('s3')->url($path);
        }

        Redis::set("emp_{$employee->nomor}", $employee->toJson());
        
        $employee->save();

        return response()->json($employee);
    }

    public function deleteEmployee($id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json(['message' => 'Employee not found']);
        }

        Redis::del("emp_{$employee->nomor}");

        $employee->delete();

        return response()->json(['message' => 'Employee deleted']);
    }
}
