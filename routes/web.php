<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeCtrl;

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

Route::get('/',[EmployeeCtrl::class,'index'])->name('home');
Route::get('employee/list',[EmployeeCtrl::class,'employeeList'])->name('employeeList');
Route::get('emplyee/create', [EmployeeCtrl::class,'createEmployee'])->name('createEmployee');
Route::get('employee/edit/{id}', [EmployeeCtrl::class,'editEmployee'])->name('editEmployee');
Route::post('employee/add', [EmployeeCtrl::class,'addEmployee'])->name('addEmployee');
Route::put('employee/update', [EmployeeCtrl::class,'updateEmployee'])->name('updateEmployee');
Route::get('get-states/{cid}', [EmployeeCtrl::class,'getStates'])->name('getStates');
Route::delete('employee/delete/{id}', [EmployeeCtrl::class,'deleteEmployee'])->name('deleteEmployee');
