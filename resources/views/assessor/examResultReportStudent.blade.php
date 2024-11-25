@extends('index')

@section('container')
    <div class="card h-auto">
        <div class="d-flex align-items-end row">
            <div class="col-sm-12">
                <div class="card-body">
                    <h5 class="card-title text-primary">Exam Result <span id="unit_title">{{ $standard->unit_title }}</span>
                    </h5>
                    <p class="mb-1" id="unit_code">
                        {{ $standard->unit_code }}
                    </p>
                    <p class="mb-4" id="unit_description">
                        {{ $standard->unit_description }}
                    </p>
                    {{-- <a href="/competency-standard/delete/{{ $competency->id }}" class="">
                        <button type="submit" class="btn btn-danger w-100 mb-2">Delete Competecy Standard</button>
                    </a>
                    <a href="/competency-standard/update/{{ $competency->id }}">
                        <button type="submit" class="btn btn-primary w-100">Update Competecy Standard</button>
                    </a> --}}
                    <select id="roleSelect" class="form-select" name="role" onchange="fetchExamResultReport(this.value)">
                        @foreach ($standards as $item)
                            <option value="{{ $item->id }}" {{ $standard->id == $item->id ? 'selected' : '' }}>
                                {{ $item->unit_title }}
                            </option>
                        @endforeach
                    </select>

                    <a href="/exam/report/{{ $standard->id }}/excel">
                        <button class="btn btn-success w-100 mt-2">Export to Excel</button>
                    </a>

                </div>
            </div>
        </div>

    </div>
    <div class="card mt-3">
        <h5 class="card-header">Table Exam Result</h5>
        <div class="table-responsive text-nowrap">
            <table class="table" id="report-table">
                <thead>
                    <tr>
                        <th>Id. </th>
                        <th>Student Full Name</th>
                        <th>Student NISN</th>
                        @foreach ($elements as $item)
                            <th>{{ $item->criteria }}</th>
                        @endforeach
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
                            @foreach ($student['elements'] as $item)
                                <td>{{ $item['status'] }}</td>
                            @endforeach
                            <td>{{ number_format($student['final_score'], 2) }}%</td>
                            <td>{{ $student['status'] }}</td>

                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>

    <script>
        function fetchExamResultReport(standardId) {
            // Kirim request menggunakan Fetch API untuk mengganti data tanpa refresh halaman
            fetch(`/exam-results/report/${standardId}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Fetched data:', data);


                    const unit_code = document.getElementById("unit_code");
                    const unit_title = document.getElementById("unit_title");
                    const unit_description = document.getElementById("unit_description");

                    unit_code.innerHTML = '';
                    unit_title.innerHTML = '';
                    unit_description.innerHTML = '';

                    unit_code.innerHTML = data.standard.unit_code;
                    unit_title.innerHTML = data.standard.unit_title;
                    unit_description.innerHTML = data.standard.unit_description;


                    const tableHeader = document.querySelector('#report-table thead');
                    tableHeader.innerHTML = ''; // Bersihkan isi thead terlebih dahulu
                    let elementArray = Object.values(data.elements)
                    // Buat baris header awal
                    let headerRow = `
                    <tr>
                        <th>#</th>
                        <th>Student Full Name</th>
                        <th>Student NISN</th>
                `;

                    // Iterasi array `element` untuk menambahkan kolom kriteria
                    elementArray.forEach(element => {
                        headerRow += `<th>${element.criteria}</th>`;
                    });

                    // Tambahkan kolom untuk skor akhir dan status kompetensi
                    headerRow += `
                        <th>Final Score (%)</th>
                        <th>Competency Status</th>
                    </tr>
                `;

                    // Set isi <thead> dengan baris header yang telah dibuat
                    tableHeader.innerHTML = headerRow;
                    // Replace table content dynamically
                    const studentsArray = Object.values(data.students);
                    console.log(studentsArray);

                    const tableBody = document.querySelector('#report-table tbody');
                    tableBody.innerHTML = '';

                    studentsArray.forEach((student, index) => {
                        let row = `<tr>
                        <td>${index + 1}</td>
                        <td>${student.student_name}</td>
                        <td>${student.student_nisn}</td>
                        `;

                        student.elements.forEach(element => {
                            row += `<td>${element.status}</td>`;
                        });

                        row += `
                        <td>${student.final_score.toFixed(2)}%</td>
                        <td>${student.status}</td>
                    </tr>`;
                        tableBody.innerHTML += row;
                    });
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
@endsection
