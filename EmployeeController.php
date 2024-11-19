<?php

namespace App\Http\Controllers;

use App\Models\employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PHPExcel_IOFactory;

class EmployeeController extends Controller
{

    
    function login(){
        if(Auth::check()){
            return redirect(route('employees'));

        }
        return view('login'); 
    }


    
    function loginpost(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        
    $credentials = $request->only(['email', 'password']);

    if(Auth::guard('employee')->attempt($credentials)){
        $request->session()->put('employee_id',Auth::guard('employee')->id());
        return redirect()->route('employee.index');
    } else {
        return redirect()->route('employee.login')->withInput($request->except('password'))
            ->with("error", "Invalid email or password");
    }

       // $credentials = $request->only(keys:'email', 'password');
        // $credentials = $request->only(['email', 'password']);

        // $employee = employee::where('email', $credentials['email'])->first();
        //dd($employee);  
        // if (!$employee || !$employee->email_verified_at) {
        //     return redirect()->route('employee.login')->withInput($request->except('password'))
        //         ->with("error", "Email not verified or does not exist");
        // }
        // dd($credentials);


        // if(Auth::guard('employee')->attempt($credentials)){
        // return redirect()
        // ->route('employee.index');
        // }
        //    return redirect()->route('employee.login')->withInput($request->except('password'))
        //     ->with("error", "Login details are not valid");
       
            

        
    //     if(Auth::attempt($credentials)){
    //         return redirect()->intended(route('employee.index'));
    //     }
    //      return redirect(route('employee.login'))->with("error","Login details are not valid");

    //   if(Auth::attempt($credentials)){
    //     $request->session()->regenerate();
    //     return redirect()->intended('employee.index');
    //  }
    //  return back()->withErrors([
    //     'email' => 'The given items are wrong',
    //  ]);

    // if(Auth::guard('employee')->attempt($credentials)){
    //     $request->session()->put('employee_id',Auth::guard('employee')->id());
    //     return redirect()->route('employee.index');
    // }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = employee::orderBY('id')->paginate(5);
         return view('index',compact('employees')); 
        
   

    //   $employee = auth()->user();
    //     if ($employee) {
    //       return view('employee.index', ['user' => $employees]);
    //     } else {
    //       return redirect()->route('employee.login');
    //     }\

    // $employeeId = session('employee_id');
    // $employees = employee::find($employeeId);

    // if($employees){
    //     return view('index',compact('employee'));
    // }else{
    //     return redirect()->route('employee.login');
    // }
   

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Validator::extend('re_password', function ($attribute, $value, $parameters, $validator) {
            return $value === $validator->getData()['password'];
        });
        Validator::extend('is_active_checked', function ($attribute, $value, $parameters, $validator) {
            return $value == 1;
        });

        $request->validate([
            'name'=>'required',
            'email'=>'required|unique:employee,email|email',
            'joining_date' => 'required|date_format:Y-m-d',
            'salary'=>'required',
            'phone' => 'required|numeric|digits:10|unique:employee,phone',
            'address' => 'required',
            'password' => 'required',
            're_password' => 'required|re_password',
            'is_active' => 'required|is_active_checked',
        ],[
            'phone.unique' => 'Given Phone Number is already exits',
            'email.unique' => 'Given E-mail ID is already exits',
            're_password' => 'password and re-password must be same',
            'is_active.is_active_checked' => 'The Active field must be checked',
        ]);


       $data = $request->except('_token');

      //mass assignment

       //employee::create($data);
    //    $data['password'] = Hash::make($request->password);
    //    $data['re_password'] = Hash::make($request->re_password); 

        // insert a single row

      $employee= new employee;
       $employee->name=$data['name'];
       $employee->email=$data['email'];
       $employee->phone=$data['phone'];
       $employee->joining_date=$data['joining_date'];
       $employee->salary=$data['salary'];
       $employee->is_active=$data['is_active'];
       $employee->address=$data['address'];
       $employee->password = Hash::make($request->password);
       $employee->re_password = Hash::make($request->re_password);
       $employee->save();


    //    dd('successfully created');

       return redirect()
       ->route('employee.login')
       ->withSuccess('Employee has been created successfully');
     
    }

    /**
     * Display the specified resource.
     */
    public function show(employee $employee)
    {
       return view('show',compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(employee $employee)
    {
        // $employee=employee::find($id);
        return view('edit' ,compact('employee'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, employee $employee)
    {

        $request->validate([
            'name'=>'required',
            'email'=>'required|unique:employee,email,'.$employee->id.'|email',
            'joining_date'=>'required',
            'salary'=>'required',
            'phone' => 'required|numeric|digits:10|unique:employee,phone,'.$employee->id,
            'address' => 'required',
        ],[
            'phone.unique' => 'Given Phone Number is already exits',
            'email.unique' => 'Given E-mail ID is already exits'
        ]);

        $data=$request->all();
        // $employee=employee::find($id);
        $employee->update($data);
        return redirect()->route('employee.edit',$employee->id)
        ->withSuccess('Employee details updated');
       
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(employee $employee)
    {
       $employee->delete();
       return redirect()->route('employee.index')
       ->withSuccess('Employee deleted successfully');
    }

    public function logout(){
        session()->forget('employee_id');
        Auth::guard('employee')->logout();
        return redirect()->route('employee.login');
    }

    public function uploadForm()
{
    return view('upload');
}

public function upload(Request $request)
{
    $request->validate([
        'csv_file' => 'required|file|mimes:csv,txt',
    ]);
    
    $file = $request->file('csv_file');
    $csvData = array_map('str_getcsv', file($file));

    foreach ($csvData as $row) {
        $existingEmployee = employee::where('email', $row[1])->first();

        if (!$existingEmployee) {
            $employee = new employee;
            $employee->name = $row[0];
            $employee->email = $row[1];
            $employee->phone = $row[2];
            $employee->joining_date = $row[3];
            $employee->salary = $row[4];
            $employee->is_active = $row[5];
            $employee->address = $row[6];
            $employee->password = Hash::make($row[7]);
            $employee->re_password = Hash::make($row[8]);
            $employee->save();
        }
    }

    return redirect()->route('employee.login')->withSuccess('Employees registered successfully');
}

}
