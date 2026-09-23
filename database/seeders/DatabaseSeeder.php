<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Department;
use App\Models\Diagnosis;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\Invoice;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Prescription;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Demo data for the Hospital Management System.
 * Run with: php artisan db:seed
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ---------------------------------------------------------------
        // Departments
        // ---------------------------------------------------------------
        $departments = collect([
            ['name' => 'Cardiology', 'location' => 'Building A, 2nd Floor', 'description' => 'Heart and cardiovascular care.'],
            ['name' => 'Neurology', 'location' => 'Building A, 3rd Floor', 'description' => 'Brain and nervous system care.'],
            ['name' => 'Pediatrics', 'location' => 'Building B, 1st Floor', 'description' => 'Child healthcare.'],
            ['name' => 'Orthopedics', 'location' => 'Building B, 2nd Floor', 'description' => 'Bone and joint care.'],
            ['name' => 'General Medicine', 'location' => 'Building A, 1st Floor', 'description' => 'General checkups and referrals.'],
        ])->map(fn ($d) => Department::create($d));

        // ---------------------------------------------------------------
        // Admin account
        // ---------------------------------------------------------------
        User::create([
            'name' => 'System Administrator',
            'email' => 'admin@hms.test',
            'password' => Hash::make('password'),
            'role' => User::ROLE_ADMIN,
            'phone' => '01700000001',
        ]);

        // ---------------------------------------------------------------
        // Receptionist account
        // ---------------------------------------------------------------
        User::create([
            'name' => 'Front Desk',
            'email' => 'reception@hms.test',
            'password' => Hash::make('password'),
            'role' => User::ROLE_RECEPTIONIST,
            'phone' => '01700000002',
        ]);

        // ---------------------------------------------------------------
        // Doctors (a named demo doctor + a few extra doctors for variety)
        // ---------------------------------------------------------------
        $doctorSeedData = [
            ['name' => 'Dr. Sarah Islam', 'email' => 'doctor@hms.test', 'department' => 'Cardiology', 'specialization' => 'Cardiologist', 'qualification' => 'MBBS, MD (Cardiology)', 'experience' => 12, 'fee' => 50],
            ['name' => 'Dr. Farhan Ahmed', 'email' => 'farhan.ahmed@hms.test', 'department' => 'Neurology', 'specialization' => 'Neurologist', 'qualification' => 'MBBS, FCPS (Neurology)', 'experience' => 9, 'fee' => 60],
            ['name' => 'Dr. Nusrat Jahan', 'email' => 'nusrat.jahan@hms.test', 'department' => 'Pediatrics', 'specialization' => 'Pediatrician', 'qualification' => 'MBBS, DCH', 'experience' => 7, 'fee' => 40],
            ['name' => 'Dr. Kamal Hossain', 'email' => 'kamal.hossain@hms.test', 'department' => 'Orthopedics', 'specialization' => 'Orthopedic Surgeon', 'qualification' => 'MBBS, MS (Ortho)', 'experience' => 15, 'fee' => 55],
            ['name' => 'Dr. Ayesha Rahman', 'email' => 'ayesha.rahman@hms.test', 'department' => 'General Medicine', 'specialization' => 'General Physician', 'qualification' => 'MBBS', 'experience' => 5, 'fee' => 30],
        ];

        $doctors = collect();

        foreach ($doctorSeedData as $d) {
            $user = User::create([
                'name' => $d['name'],
                'email' => $d['email'],
                'password' => Hash::make('password'),
                'role' => User::ROLE_DOCTOR,
                'phone' => '017' . rand(10000000, 99999999),
            ]);

            $doctor = Doctor::create([
                'user_id' => $user->id,
                'department_id' => $departments->firstWhere('name', $d['department'])->id,
                'specialization' => $d['specialization'],
                'qualification' => $d['qualification'],
                'experience_years' => $d['experience'],
                'consultation_fee' => $d['fee'],
                'license_number' => 'LIC-' . strtoupper(Str::random(6)),
                'bio' => "{$d['specialization']} with {$d['experience']} years of experience.",
            ]);

            // Mon–Fri, 9am–1pm and 2pm–5pm
            foreach ([1, 2, 3, 4, 5] as $day) {
                DoctorSchedule::create([
                    'doctor_id' => $doctor->id,
                    'day_of_week' => $day,
                    'start_time' => '09:00',
                    'end_time' => '13:00',
                    'slot_duration_minutes' => 30,
                ]);
                DoctorSchedule::create([
                    'doctor_id' => $doctor->id,
                    'day_of_week' => $day,
                    'start_time' => '14:00',
                    'end_time' => '17:00',
                    'slot_duration_minutes' => 30,
                ]);
            }

            $doctors->push($doctor);
        }

        // ---------------------------------------------------------------
        // Patients (one with a login account, a few walk-ins without one)
        // ---------------------------------------------------------------
        $patientUser = User::create([
            'name' => 'Rahim Uddin',
            'email' => 'patient@hms.test',
            'password' => Hash::make('password'),
            'role' => User::ROLE_PATIENT,
            'phone' => '01700000099',
        ]);

        $mainPatient = Patient::create([
            'user_id' => $patientUser->id,
            'name' => 'Rahim Uddin',
            'date_of_birth' => '1990-05-14',
            'gender' => 'male',
            'blood_group' => 'B+',
            'phone' => '01700000099',
            'email' => 'patient@hms.test',
            'address' => 'House 12, Road 5, Dhaka',
            'emergency_contact_name' => 'Karim Uddin',
            'emergency_contact_phone' => '01700000098',
        ]);

        $walkInNames = [
            ['name' => 'Anika Chowdhury', 'gender' => 'female', 'blood' => 'A+'],
            ['name' => 'Tanvir Hasan', 'gender' => 'male', 'blood' => 'O+'],
            ['name' => 'Sumaiya Akter', 'gender' => 'female', 'blood' => 'AB+'],
            ['name' => 'Jahid Hasan', 'gender' => 'male', 'blood' => 'O-'],
        ];

        $walkIns = collect($walkInNames)->map(fn ($p) => Patient::create([
            'name' => $p['name'],
            'gender' => $p['gender'],
            'blood_group' => $p['blood'],
            'phone' => '018' . rand(10000000, 99999999),
            'date_of_birth' => now()->subYears(rand(18, 60))->format('Y-m-d'),
        ]));

        $allPatients = $walkIns->push($mainPatient);

        // ---------------------------------------------------------------
        // Appointments: a mix of past (completed) and upcoming (pending/confirmed)
        // ---------------------------------------------------------------
        $pastAppointments = collect();

        foreach ($allPatients as $i => $patient) {
            $doctor = $doctors[$i % $doctors->count()];

            // One completed appointment 3 days ago
            $pastAppointments->push(Appointment::create([
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'department_id' => $doctor->department_id,
                'appointment_date' => now()->subDays(3)->toDateString(),
                'appointment_time' => '10:00',
                'reason' => 'Routine checkup',
                'status' => 'completed',
                'created_by' => 1,
            ]));

            // One upcoming appointment
            Appointment::create([
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'department_id' => $doctor->department_id,
                'appointment_date' => now()->addDays(2 + $i)->toDateString(),
                'appointment_time' => '11:00',
                'reason' => 'Follow-up',
                'status' => $i % 2 === 0 ? 'confirmed' : 'pending',
                'created_by' => 1,
            ]);
        }

        // ---------------------------------------------------------------
        // Medical records, diagnoses & prescriptions for the completed visits
        // ---------------------------------------------------------------
        foreach ($pastAppointments as $appt) {
            $record = MedicalRecord::create([
                'patient_id' => $appt->patient_id,
                'doctor_id' => $appt->doctor_id,
                'appointment_id' => $appt->id,
                'visit_date' => $appt->appointment_date,
                'chief_complaint' => 'Mild fever and fatigue for 2 days.',
                'notes' => 'Patient advised rest and hydration. Follow up if symptoms persist.',
                'blood_pressure' => '120/80',
                'temperature_celsius' => 37.8,
                'pulse_bpm' => 82,
                'weight_kg' => 68.5,
                'height_cm' => 170,
            ]);

            Diagnosis::create([
                'medical_record_id' => $record->id,
                'diagnosis_name' => 'Viral Fever',
                'icd_code' => 'B34.9',
                'description' => 'Self-limiting viral infection.',
                'severity' => 'mild',
            ]);

            $prescription = Prescription::create([
                'medical_record_id' => $record->id,
                'doctor_id' => $appt->doctor_id,
                'patient_id' => $appt->patient_id,
                'issue_date' => $appt->appointment_date,
                'notes' => 'Take with food. Return if fever persists beyond 3 days.',
            ]);

            $prescription->items()->createMany([
                ['medicine_name' => 'Paracetamol 500mg', 'dosage' => '1 tablet', 'frequency' => '3x daily', 'duration' => '5 days', 'instructions' => 'After meals'],
                ['medicine_name' => 'Vitamin C 500mg', 'dosage' => '1 tablet', 'frequency' => '1x daily', 'duration' => '7 days', 'instructions' => 'Morning'],
            ]);

            // Invoice for this visit
            $invoice = Invoice::create([
                'invoice_number' => 'INV-' . now()->format('Ymd') . '-' . strtoupper(Str::random(5)),
                'patient_id' => $appt->patient_id,
                'appointment_id' => $appt->id,
                'issue_date' => $appt->appointment_date,
                'due_date' => now()->subDays(3)->addDays(7)->toDateString(),
                'created_by' => 1,
            ]);

            $invoice->items()->create([
                'description' => 'Consultation Fee',
                'quantity' => 1,
                'unit_price' => $appt->doctor->consultation_fee,
                'amount' => $appt->doctor->consultation_fee,
            ]);
            $invoice->items()->create([
                'description' => 'Medicine Dispensing',
                'quantity' => 1,
                'unit_price' => 8.50,
                'amount' => 8.50,
            ]);

            $invoice->refreshTotals();

            // Randomly mark some invoices as fully or partially paid
            if ($appt->id % 3 !== 0) {
                Payment::create([
                    'invoice_id' => $invoice->id,
                    'amount' => $invoice->total_amount,
                    'payment_method' => 'cash',
                    'payment_date' => $appt->appointment_date,
                    'received_by' => 2,
                ]);
                $invoice->refreshTotals();
            }
        }
    }
}
