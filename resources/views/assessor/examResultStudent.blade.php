@extends('index')

@section('container')
    <div class="card h-auto">
        <div class="d-flex align-items-end row">
            <div class="col-sm-12">
                <div class="card-body">
                    <h5 class="card-title text-primary">Exam Result {{ $standard->unit_title }}</h5>
                    <p class="mb-1">
                        {{ $standard->unit_code }}
                    </p>
                    <p class="mb-4">
                        {{ $standard->unit_description }}
                    </p>
                    {{-- <form action="/select/exam/result/student" method="GET" id="form-standar">
                        <select name="standar_id" id="standar-select" class="form-select w-100" onchange="submitForm()">
                            @foreach ($standars as $item)
                                <option value="{{ $item->id }}" {{ request('standar_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->unit_code }}, {{ $item->unit_title }}
                                </option>
                            @endforeach
                        </select>
                    </form> --}}
                    {{-- @dd($standard->id) --}}
                    <select id="roleSelect" class="form-select" name="role" onchange="fetchExamResult(this.value)">
                        @foreach ($standars as $item)
                            <option value="{{ $item->id }}" {{ $standard->id == $item->id ? 'selected' : '' }}>{{ $item->unit_title }}</option>
                        @endforeach
                    </select>


                    {{-- <a href="/competency-standard/delete/{{ $competency->id }}" class="">
                        <button type="submit" class="btn btn-danger w-100 mb-2">Delete Competecy Standard</button>
                    </a>
                    <a href="/competency-standard/update/{{ $competency->id }}">
                        <button type="submit" class="btn btn-primary w-100">Update Competecy Standard</button>
                    </a> --}}
                </div>
            </div>
        </div>

    </div>
    <div class="card mt-3">
        <h5 class="card-header">Table Exam Result</h5>
        <div class="table-responsive text-nowrap">
            <table class="table" id="manage-table">
                <thead>
                    <tr>
                        <th>Id. </th>
                        <th>Student Full Name</th>
                        <th>Student NISN</th>
                        <th>Final Score (%)</th>
                        <th>Competency Status</th>

                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($students as $index => $student)
                        <tr>
                            <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong>{{ $index }}</strong>
                            </td>
                            <td>{{ $student['student_name'] }}</td>
                            <td>{{ $student['student_nisn'] }}</td>
                            <!-- Ganti dengan nama murid jika ada relasi -->
                            <td>{{ number_format($student['final_score'], 2) }}%</td>
                            <td>{{ $student['status'] }}</td>

                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>

    {{-- <script>
        function submitForm() {
        const form = document.getElementById('form-standar');
        form.submit();
        }
    </script> --}}
    <script>
        function fetchExamResult(standardId) {
            fetch(`/exam-result/${standardId}`)
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.querySelector('#manage-table tbody');
                    tableBody.innerHTML = ''; // Hapus isi tabel sebelumnya

                    // Perbarui isi tabel dengan data yang diterima
                    data.students.forEach((student, index) => {
                        tableBody.innerHTML += `
                            <tr>
                                <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong>${index + 1}</strong></td>
                                <td>${student.student_name}</td>
                                <td>${student.student_nisn}</td>
                                <td>${parseFloat(student.final_score).toFixed(2)}%</td>
                                <td>${student.status}</td>
                            </tr>
                        `;
                    });
                })
                .catch(error => {
                    console.error('Error fetching exam results:', error);
                });
        }
    </script>

@endsection
