@extends('index')
@section('container')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Forms/</span> User Form</h4>

    <!-- Basic Layout -->
    <div class="row">

        <div class="col-xl">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Please fill the blank field</h5>
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
                    <form action="/user/create" method="post">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-default-company">Full Name</label>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-company2" class="input-group-text"><i
                                        class='bx bx-user'></i></span>
                                <input type="text" id="basic-icon-default-company" class="form-control"
                                    placeholder="rizal, bla, bla" aria-label="rizal, bla, bla." name="full_name"
                                    aria-describedby="basic-icon-default-company2" />
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-default-company">Username</label>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-company2" class="input-group-text"><i
                                        class='bx bx-user'></i></span>
                                <input type="text" id="basic-icon-default-company" class="form-control"
                                    placeholder="rizal, bla, bla" aria-label="rizal, bla, bla." name="username"
                                    aria-describedby="basic-icon-default-company2" />
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-default-company">Email</label>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-company2" class="input-group-text"><i
                                        class='bx bx-envelope'></i></span>
                                <input type="text" id="basic-icon-default-company" class="form-control"
                                    placeholder="user@example.com" aria-label="user@example.com" name="email"
                                    aria-describedby="basic-icon-default-company2" />
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
                                    placeholder="087*****" aria-label="087*****" name="phone"
                                    aria-describedby="basic-icon-default-company2" />
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-default-company">Role</label>
                            <select id="roleSelect" class="form-select" name="role" onchange="toggleRoleInputs()">
                                <option>Default select</option>
                                <option value="student">Student</option>
                                <option value="admin">Admin</option>
                                <option value="assessor">Assessor</option>
                            </select>
                        </div>

                        <div id="student-input" style="display: none;">
                            <div class="mb-3">
                                <label class="form-label" for="basic-icon-default-company">Nisn</label>
                                <div class="input-group input-group-merge">
                                    <span id="basic-icon-default-company2" class="input-group-text"><i
                                            class='bx bx-phone'></i></span>
                                    <input type="text" id="basic-icon-default-company" class="form-control"
                                        placeholder="0070****" aria-label="0070****" name="nisn"
                                        aria-describedby="basic-icon-default-company2" />
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="basic-icon-default-company">Grade Level</label>
                                <select id="defaultSelect" class="form-select" name="grade_level">
                                    <option>Default select</option>
                                    <option value="10">X</option>
                                    <option value="11">XI</option>
                                    <option value="12">XII</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="basic-icon-default-company">Major</label>
                                <select id="defaultSelect" class="form-select" name="major_id">
                                    <option>Default select</option>
                                    @foreach ($majors as $item)
                                        <option value="{{ $item->id }}">{{ $item->major_name }}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                        <div id="assessor-input" style="display: none;">
                            <div class="mb-3">
                                <label class="form-label" for="basic-icon-default-company">Assessor Type</label>
                                <select id="defaultSelect" class="form-select" name="assessor_type">
                                    <option>Default select</option>
                                    <option value="internal">Internal</option>
                                    <option value="external">External</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="basic-icon-default-company">Assessor Description</label>
                                <div class="input-group input-group-merge">
                                    <span id="basic-icon-default-company2" class="input-group-text"><i
                                            class='bx bx-phone'></i></span>
                                    <input type="text" id="basic-icon-default-company" class="form-control"
                                        placeholder="SMK YPC TASIK, PT. bla, Etc" aria-label="SMK YPC TASIK, PT. bla, Etc"
                                        name="description" aria-describedby="basic-icon-default-company2" />
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- / Content -->

    <script>
        function toggleRoleInputs() {
            var role = document.getElementById('roleSelect').value;
            var studentInput = document.getElementById('student-input');
            var assessorInput = document.getElementById('assessor-input');

            if (role === 'student') {
                studentInput.style.display = 'block';
                assessorInput.style.display = 'none';
            } else if (role === 'assessor') {
                studentInput.style.display = 'none';
                assessorInput.style.display = 'block';
            } else {
                studentInput.style.display = 'none';
                assessorInput.style.display = 'none';
            }
        }
    </script>
@endsection
