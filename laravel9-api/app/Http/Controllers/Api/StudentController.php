<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    // untuk mendapatkan semua data dari tabel students
    public function index()
    {
        // ambil semua data
        $students = Student::all();
        
        // jika jumlah data nya lebih dari 0, maka berikan response keberhasilan, jika tidak maka berikan response kesalahan
        return ($students->count() > 0) ? 
               response()->json([ 'status' => 200, 'students' => $students ], 200) : 
               response()->json([ 'status' => 404, 'status_message' => 'No Record Found' ], 404) ;    
    }

    // untuk menambahkan data ke tabel students
    public function store(Request $request)
    {
        // melakukan validasi
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:191'],
            'course' => ['required', 'string', 'max:191'],
            'email' => ['required', 'email', 'max:191'],
            'phone' => ['required', 'digits:10'],
        ]);

        // jika validasi gagal, berikan response kesalahan
        if($validator->fails()) 
            return response()->json([ 'status' => 422, 'errors' => $validator->messages() ], 422);

        // masukan ke dalam database
        $students = Student::create([
            'name' => $request->name,
            'course' => $request->course,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        // jika berhasil dimasukan ke database, maka berikan response keberhasilan, jika tidak maka berikan response kesalahan
        return $students ? 
               response()->json([ 'status' => 200, 'message' => 'Student Added Created Successfully' ], 200) : 
               response()->json([ 'status' => 500, 'message' => 'Something Went Wrong' ], 500) ;
    }

    // untuk mendapatkan data dari tabel students bedasarkan id
    public function show($id)
    {
        // cari student bedasarkan id nya
        $student = Student::find($id);

        // jika ada, maka berikan response keberhasilan, jika tidak maka berikan response kesalahan
        return $student ? 
               response()->json([ 'status' => 200, 'student' => $student ], 200) : 
               response()->json([ 'status' => 404, 'message' => 'No Such Student Found!' ], 404) ;
    }

    // untuk mendapatkan data dari tabel students bedasarkan id, lalu digunakan untuk penampilan data saat edit
    public function edit($id)
    {
        $student = Student::find($id);

        return $student ? 
               response()->json([ 'status' => 200, 'student' => $student ], 200) : 
               response()->json([ 'status' => 404, 'message' => 'No Such Student Found!' ], 404) ;
    } 
    
    // untuk melakukan update data ke tabel students bedasarkan id
    public function update(Request $request, int $id)
    {   
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:191'],
            'course' => ['required', 'string', 'max:191'],
            'email' => ['required', 'email', 'max:191'],
            'phone' => ['required', 'digits:10'],
        ]);

        if($validator->fails()) 
            return response()->json([ 'status' => 422, 'errors' => $validator->messages() ], 422);

        $student = Student::find($id);

        if(!$student)
            return response()->json([ 'status' => 404, 'message' => 'No Such Student Found' ], 404);
        
        $student->update([
            'name' => $request->name,
            'course' => $request->course,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return response()->json([ 'status' => 200, 'message' => 'Student Update Successfully' ], 200);
    }

    // untuk melakukan delete data di tabel students bedasarkan id
    public function destroy($id)
    {
        $student = Student::find($id);

        if(!$student)
            return response()->json([ 'status' => 404, 'message' => 'No Such Student Found!' ], 404);
        
        $student->delete();

        return response()->json([ 'status' => 200, 'message' => 'Student Deleted Successfully' ], 200);
    }
}
