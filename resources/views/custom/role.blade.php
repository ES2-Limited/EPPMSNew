<div class="col-md-4">
    <div class="mb-3">
        <label class="form-label">Personnel Role</label>
        @php
            $roleId = old('role_id', isset($selectedRole) ? $selectedRole : '');
        @endphp
        <select class="form-select" id="role" name="role_id" required>
            <option value="" {{ $roleId ? '' : 'selected' }}>Choose Role</option>
            @foreach ($roles as $eachRole)
                <option value="{{ $eachRole->id }}"
                    {{ (int)$roleId === $eachRole->id ? 'selected' : '' }}>
                    {{ ucfirst(str_replace(['_', '-'], ' ', $eachRole->name)) }}
                </option>
            @endforeach
        </select>
    </div>
</div>
