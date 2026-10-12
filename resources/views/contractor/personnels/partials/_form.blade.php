<form action="{{ $formAction }}" method="POST" enctype="multipart/form-data"
    onsubmit="this.querySelector('#submit-button').disabled = true; this.querySelector('#submit-button').innerText = 'Saving...';">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif
    <div>
        <div class="card-body">
            <h4 class="card-title">Personnel Credentials</h4>

            <div class="row pt-3">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">First Name</label>
                        <input type="text" id="firstName" class="form-control" placeholder="First Name"
                            name="first_name"
                            value="{{ old('first_name', $contractor_personnel->user->first_name ?? '') }}" required />
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Last Name</label>
                        <input type="text" id="lastName" class="form-control" placeholder="Last Name"
                            name="last_name"
                            value="{{ old('last_name', $contractor_personnel->user->last_name ?? '') }}" required />
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Other</label>
                        <input type="text" id="other" class="form-control" placeholder="Other" name="other_name"
                            value="{{ old('other_name', $contractor_personnel->user->other_name ?? '') }}" />
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" id="email" class="form-control" placeholder="Johndoe@gmail.com"
                            name="email" value="{{ old('email', $contractor_personnel->user->email ?? '') }}"
                            {{ isset($contractor_personnel) ? 'readonly' : '' }} required />
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="text" id="phoneNumber" class="form-control" placeholder="Phone Number"
                            name="phone" value="{{ old('phone', $contractor_personnel->user->phone ?? '') }}" />
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Designation</label>
                        <input type="text" id="designation" class="form-control" placeholder="Designation"
                            name="designation"
                            value="{{ old('designation', $contractor_personnel->designation ?? '') }}" />
                    </div>
                </div>
            </div>

            <div class="row pt-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Project Contractor</label>
                        <select class="form-select" id="contractor" name="contractor_id">
                            <option value="">Choose Contractor</option>
                            @foreach ($contractors as $contractor)
                                <option value="{{ $contractor->id }}"
                                    {{ old('contractor_id', $contractor_personnel->contractor_id ?? '') == $contractor->id ? 'selected' : '' }}>
                                    {{ $contractor->user?->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                @if (!isset($contractor_personnel))
                    <div class="col-md-6">
                        <label for="password" class="form-label">{{ __('Password') }}</label>
                        <div class="input-group mb-3">
                            <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" name="password" required
                                autocomplete="new-password">
                            <span class="input-group-text" id="togglePassword">
                                <i id="togglePasswordIcon" class="fas fa-eye"></i>
                            </span>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="form-actions">
            <div class="card-body border-top">
                <button type="submit" class="btn btn-primary" id="submit-button">
                    {{ $isEdit ? 'Update' : 'Create' }}
                </button>
                <button type="reset" class="btn bg-danger-subtle text-danger ms-6">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</form>
