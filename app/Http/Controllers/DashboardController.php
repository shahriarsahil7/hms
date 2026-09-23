<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        return match ($user->role) {
            User::ROLE_ADMIN => $this->adminDashboard(),
            User::ROLE_DOCTOR => $this->doctorDashboard($user),
            User::ROLE_RECEPTIONIST => $this->receptionistDashboard(),
            User::ROLE_PATIENT => $this->patientDashboard($user),
            default => view('dashboard.generic'),
        };
    }

    private function adminDashboard()
    {
        $stats = [
            'total_patients' => Patient::count(),
            'total_doctors' => Doctor::count(),
            'today_appointments' => Appointment::whereDate('appointment_date', Carbon::today())->count(),
            'pending_appointments' => Appointment::where('status', 'pending')->count(),
            'unpaid_invoices' => Invoice::whereIn('status', ['unpaid', 'partially_paid'])->count(),
            'revenue_this_month' => Invoice::whereMonth('issue_date', Carbon::now()->month)
                ->whereYear('issue_date', Carbon::now()->year)
                ->sum('amount_paid'),
        ];

        $recentAppointments = Appointment::with(['patient', 'doctor.user', 'department'])
            ->latest('appointment_date')
            ->latest('appointment_time')
            ->limit(8)
            ->get();

        return view('dashboard.admin', compact('stats', 'recentAppointments'));
    }

    private function doctorDashboard(User $user)
    {
        $doctor = $user->doctor;

        $todayAppointments = collect();
        $upcomingAppointments = collect();

        if ($doctor) {
            $todayAppointments = Appointment::with('patient')
                ->where('doctor_id', $doctor->id)
                ->whereDate('appointment_date', Carbon::today())
                ->orderBy('appointment_time')
                ->get();

            $upcomingAppointments = Appointment::with('patient')
                ->where('doctor_id', $doctor->id)
                ->whereDate('appointment_date', '>', Carbon::today())
                ->orderBy('appointment_date')
                ->orderBy('appointment_time')
                ->limit(10)
                ->get();
        }

        return view('dashboard.doctor', compact('doctor', 'todayAppointments', 'upcomingAppointments'));
    }

    private function receptionistDashboard()
    {
        $todayAppointments = Appointment::with(['patient', 'doctor.user'])
            ->whereDate('appointment_date', Carbon::today())
            ->orderBy('appointment_time')
            ->get();

        $stats = [
            'total_patients' => Patient::count(),
            'today_appointments' => $todayAppointments->count(),
            'unpaid_invoices' => Invoice::whereIn('status', ['unpaid', 'partially_paid'])->count(),
        ];

        return view('dashboard.receptionist', compact('todayAppointments', 'stats'));
    }

    private function patientDashboard(User $user)
    {
        $patient = $user->patient;

        $upcomingAppointments = collect();
        $invoices = collect();

        if ($patient) {
            $upcomingAppointments = Appointment::with('doctor.user', 'department')
                ->where('patient_id', $patient->id)
                ->whereDate('appointment_date', '>=', Carbon::today())
                ->orderBy('appointment_date')
                ->orderBy('appointment_time')
                ->get();

            $invoices = Invoice::where('patient_id', $patient->id)
                ->latest('issue_date')
                ->limit(5)
                ->get();
        }

        return view('dashboard.patient', compact('patient', 'upcomingAppointments', 'invoices'));
    }
}
