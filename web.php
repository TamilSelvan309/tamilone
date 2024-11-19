<?php

use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::get('/', [EmployeeController::class, 'login'])-> name('employee.login');
//Route::get('/login', [EmployeeController::class,'login'])->name('employee.login');
Route::post('/employees/post', [EmployeeController::class, 'loginpost'])-> name('employee.loginpost');


Route::get('/employee/index', [EmployeeController::class, 'index'])->name('employee.index')->middleware('auth');

Route::get('/employees/create',[EmployeeController::class,'create'])-> name('employee.create');
Route::get('/employees/index',[EmployeeController::class,'index'])-> name('employee.index');
Route::post('/employees/store', [EmployeeController::class,'store'])-> name('employee.store');
Route::get('/employee/{employee}',[EmployeeController::class,'show'])-> name('employee.show');
Route::get('/employee/{employee}/edit',[EmployeeController::class,'edit'])-> name('employee.edit');
Route::put('/employees/{employee}',[EmployeeController::class,'update'])->name('employee.update');
Route::delete('/employees/{employee}',[EmployeeController::class,'destroy'])->name('employee.destroy');
Route::get('/employees/upload', [EmployeeController::class, 'uploadForm'])->name('employee.uploadForm');
Route::post('/employees/upload', [EmployeeController::class, 'upload'])->name('employee.upload');