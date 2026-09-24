<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DiagnosisController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\DoctorScheduleController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Guest routes (Authentication module)
|--------------------------------------------------------------------------
*/
/*Route::get('/run-seed-x7k9', function () { if (\App\Models\Patient::count() > 0) { return 'Already seeded — remove this route now.'; } \Illuminate\Support\Facades\Artisan::call('db:seed'); return 'Seeded successfully! Remove this route from routes/web.php now and redeploy.'; }); */
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /*
    |----------------------------------------------------------------
    | User management (Admin only)
    |----------------------------------------------------------------
    */
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
        Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

        Route::resource('departments', DepartmentController::class);
    });

    /*
    |----------------------------------------------------------------
    | Doctor directory (everyone logged in can browse)
    |----------------------------------------------------------------
    */
    Route::get('doctors', [DoctorController::class, 'index'])->name('doctors.index');
    Route::get('doctors/{doctor}', [DoctorController::class, 'show'])->name('doctors.show');

    /*
    |----------------------------------------------------------------
    | Patient management (Admin & Receptionist)
    |----------------------------------------------------------------
    */
    Route::middleware('role:admin,receptionist')->group(function () {
        Route::resource('patients', PatientController::class)->except(['show']);
    });
    Route::get('patients/{patient}', [PatientController::class, 'show'])
        ->middleware('role:admin,receptionist,doctor')
        ->name('patients.show');

    /*
    |----------------------------------------------------------------
    | Doctor schedules (Admin manages all; a doctor manages their own)
    |----------------------------------------------------------------
    */
    Route::middleware('role:admin,doctor')->group(function () {
        Route::get('schedules', [DoctorScheduleController::class, 'index'])->name('schedules.index');
        Route::get('schedules/create', [DoctorScheduleController::class, 'create'])->name('schedules.create');
        Route::post('schedules', [DoctorScheduleController::class, 'store'])->name('schedules.store');
        Route::get('schedules/{schedule}/edit', [DoctorScheduleController::class, 'edit'])->name('schedules.edit');
        Route::put('schedules/{schedule}', [DoctorScheduleController::class, 'update'])->name('schedules.update');
        Route::delete('schedules/{schedule}', [DoctorScheduleController::class, 'destroy'])->name('schedules.destroy');
    });

    /*
    |----------------------------------------------------------------
    | Appointments (all roles, scoped inside the controller)
    |----------------------------------------------------------------
    */
    Route::get('appointments/available-slots', [AppointmentController::class, 'availableSlots'])->name('appointments.available-slots');
    Route::resource('appointments', AppointmentController::class)->except(['destroy']);
    Route::delete('appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');

    /*
    |----------------------------------------------------------------
    | Medical records, diagnoses & prescriptions (Doctors write; others read)
    |----------------------------------------------------------------
    */
    Route::resource('medical-records', MedicalRecordController::class)->except(['destroy']);
    Route::middleware('role:doctor')->group(function () {
        Route::post('medical-records/{medicalRecord}/diagnoses', [DiagnosisController::class, 'store'])->name('diagnoses.store');
        Route::delete('diagnoses/{diagnosis}', [DiagnosisController::class, 'destroy'])->name('diagnoses.destroy');

        Route::post('medical-records/{medicalRecord}/prescriptions', [PrescriptionController::class, 'store'])->name('prescriptions.store');
        Route::delete('prescriptions/{prescription}', [PrescriptionController::class, 'destroy'])->name('prescriptions.destroy');
    });
    Route::get('prescriptions/{prescription}', [PrescriptionController::class, 'show'])->name('prescriptions.show');

    /*
    |----------------------------------------------------------------
    | Billing & Payments (Admin & Receptionist manage; Patient views own)
    |----------------------------------------------------------------
    */
    Route::get('invoices', [InvoiceController::class, 'index'])->name('invoices.index');

// NOTE: invoices/create must be registered before invoices/{invoice} below,
// otherwise Laravel matches the wildcard route first and 404s trying to
// find an invoice named "create".
Route::middleware('role:admin,receptionist')->group(function () {
    Route::get('invoices/create', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('invoices', [InvoiceController::class, 'store'])->name('invoices.store');
});

Route::get('invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');

Route::middleware('role:admin,receptionist')->group(function () {
    Route::delete('invoices/{invoice}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');
    Route::patch('invoices/{invoice}/cancel', [InvoiceController::class, 'cancel'])->name('invoices.cancel');

    Route::post('invoices/{invoice}/payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::delete('invoices/{invoice}/payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');
});
});
