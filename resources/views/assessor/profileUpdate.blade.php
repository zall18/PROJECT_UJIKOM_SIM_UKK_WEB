@extends('index')
@section('container')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Forms/</span> User Profile</h4>
    {{-- @dd($user->student) --}}
    <!-- Basic Layout -->
    <div class="row">

        <div class="col-xl">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">User Profile</h5>
                    <small class="text-muted float-end">Merged input group</small>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="/assessor/me" method="post">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-default-company">Full Name</label>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-company2" class="input-group-text"><i
                                        class='bx bx-user'></i></span>
                                <input type="text" id="basic-icon-default-company" class="form-control"
                                    placeholder="rizal, bla, bla" aria-label="rizal, bla, bla." name="full_name"
                                    value="{{ $user->full_name }}" aria-describedby="basic-icon-default-company2"
                                    disabled />
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-default-company">Username</label>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-company2" class="input-group-text"><i
                                        class='bx bx-user'></i></span>
                                <input type="text" id="basic-icon-default-company" class="form-control"
                                    placeholder="rizal, bla, bla" aria-label="rizal, bla, bla." name="username"
                                    value="{{ $user->username }}" aria-describedby="basic-icon-default-company2" />
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-default-company">Email</label>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-company2" class="input-group-text"><i
                                        class='bx bx-envelope'></i></span>
                                <input type="text" id="basic-icon-default-company" class="form-control"
                                    placeholder="user@example.com" aria-label="user@example.com" name="email"
                                    value="{{ $user->email }}" aria-describedby="basic-icon-default-company2" />
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-default-company">Password</label>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-company2" class="input-group-text"><i
                                        class='bx bx-low-vision'></i></span>
                                <input type="password" id="basic-icon-default-company" class="form-control"
                                    placeholder="****" aria-label="****" name="password"
                                    aria-describedby="basic-icon-default-company2" />
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-default-company">Phone</label>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-company2" class="input-group-text"><i
                                        class='bx bx-phone'></i></span>
                                <input type="text" id="basic-icon-default-company" class="form-control"
                                    placeholder="087*****" aria-label="087*****" name="phone" value="{{ $user->phone }}"
                                    aria-describedby="basic-icon-default-company2" />
                            </div>
                        </div>
                        <input type="hidden" name="role" value="{{ $user->role }}">
                        <div id="assessor-input">
                            <div class="mb-3">
                                <label class="form-label" for="basic-icon-default-company">Assessor Type</label>
                                <select id="defaultSelect" class="form-select" name="assessor_type" disabled>
                                    <option>Default select</option>
                                    <option value="internal"
                                        {{ $user->assessor->assessor_type == 'internal' ? 'selected' : '' }}>Internal
                                    </option>
                                    <option value="external"
                                        {{ $user->assessor->assessor_type == 'external' ? 'selected' : '' }}>External
                                    </option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="basic-icon-default-company">Assessor Description</label>
                                <div class="input-group input-group-merge">
                                    <span id="basic-icon-default-company2" class="input-group-text"><i
                                            class='bx bx-phone'></i></span>
                                    <input type="text" id="basic-icon-default-company" class="form-control"
                                        placeholder="SMK YPC TASIK, PT. bla, Etc" aria-label="SMK YPC TASIK, PT. bla, Etc"
                                        value="{{ $user->assessor->description }}" name="description"
                                        aria-describedby="basic-icon-default-company2" disabled />
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Update Your Profile</button>

                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- / Content -->

    <script>
        // function toggleRoleInputs() {
        //     var role = document.getElementById('roleSelect').value;
        //     var studentInput = document.getElementById('student-input');
        //     var assessorInput = document.getElementById('assessor-input');

        //     if (role === 'student') {
        //         studentInput.style.display = 'block';
        //         assessorInput.style.display = 'none';
        //     } else if (role === 'assessor') {
        //         studentInput.style.display = 'none';
        //         assessorInput.style.display = 'block';
        //     } else {
        //         studentInput.style.display = 'none';
        //         assessorInput.style.display = 'none';
        //     }
        // }
    </script>
@endsection
