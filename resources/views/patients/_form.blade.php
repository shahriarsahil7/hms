<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label small fw-semibold">Full name</label>
        <input type="text" name="name" value="{{ old('name', $patient->name ?? '') }}" class="form-control" required>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label small fw-semibold">Date of birth</label>
        <input type="date" name="date_of_birth" value="{{ old('date_of_birth', optional($patient->date_of_birth ?? null)->format('Y-m-d')) }}" class="form-control">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label small fw-semibold">Gender</label>
        <select name="gender" class="form-select">
            <option value="">—</option>
            @foreach(['male','female','other'] as $g)
                <option value="{{ $g }}" @selected(old('gender', $patient->gender ?? '')===$g)>{{ ucfirst($g) }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label small fw-semibold">Blood group</label>
        <input type="text" name="blood_group" value="{{ old('blood_group', $patient->blood_group ?? '') }}" class="form-control" placeholder="e.g. O+">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label small fw-semibold">Phone</label>
        <input type="text" name="phone" value="{{ old('phone', $patient->phone ?? '') }}" class="form-control">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label small fw-semibold">Email</label>
        <input type="email" name="email" value="{{ old('email', $patient->email ?? '') }}" class="form-control">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label small fw-semibold">Address</label>
        <input type="text" name="address" value="{{ old('address', $patient->address ?? '') }}" class="form-control">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label small fw-semibold">Emergency contact name</label>
        <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name', $patient->emergency_contact_name ?? '') }}" class="form-control">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label small fw-semibold">Emergency contact phone</label>
        <input type="text" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $patient->emergency_contact_phone ?? '') }}" class="form-control">
    </div>
    <div class="col-12 mb-3">
        <label class="form-label small fw-semibold">Known allergies</label>
        <textarea name="allergies" class="form-control" rows="2">{{ old('allergies', $patient->allergies ?? '') }}</textarea>
    </div>
</div>
