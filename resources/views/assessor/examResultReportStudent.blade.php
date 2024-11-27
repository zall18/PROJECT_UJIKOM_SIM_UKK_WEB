@extends('index')

@section('container')

    <style>
        .table td {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 150px; /* Sesuaikan dengan kebutuhan */
        }

        .table td:hover {
            overflow: visible; /* Tampilkan semua teks saat di-hover */
        }

    </style>

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
                    <select id="roleSelect" class="form-select" name="role" onchange="fetchExamResultReport(this.value)">
                        @foreach ($standards as $item)
                            <option value="{{ $item->id }}" {{ $standard->id == $item->id ? 'selected' : '' }}>
                                {{ $item->unit_title }}
                            </option>
                        @endforeach
                    </select>

                    <a href="/admin/exam/report/{{ $standard->id }}/excel" id="exportToExcelLink">
                        <button class="btn btn-success w-100 mt-2">Export to Excel</button>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="card mt-3">
        <h5 class="card-header">Table Exam Result</h5>
        <div class="table-responsive" style="overflow-x: auto;">
            <table class="table table-striped" id="manage-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student Full Name</th>
                        <th>Student NISN</th>
                        <th>Final Score (%)</th>
                        <th>Competency Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($students as $index => $student)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $student['student_name'] }}</td>
                            <td>{{ $student['student_nisn'] }}</td>
                            <td>{{ number_format($student['final_score'], 2) }}%</td>
                            <td>
                                <span class="badge {{ $student['status'] === 'Not Competent' ? 'bg-danger' : 'bg-success' }}">
                                    {{ $student['status'] }}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#details-{{ $index }}">
                                    View Details
                                </button>
                            </td>
                        </tr>
                        <tr id="details-{{ $index }}" class="collapse">
                            <td colspan="6">
                                <div class="table-responsive" style="overflow-x: auto;">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                @foreach ($elements as $item)
                                                    <th>{{ $item->criteria }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                @foreach ($student['elements'] as $item)
                                                    <td>
                                                        <span class="badge {{ $item['status'] === 'Competent' ? 'bg-success' : 'bg-danger' }}">
                                                            {{ $item['status'] }}
                                                        </span>
                                                    </td>
                                                @endforeach
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

    <script>
        function fetchExamResultReport(standardId) {
            // Kirim request menggunakan Fetch API
            fetch(`/admin/exam-results/report/${standardId}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.json())
                .then(data => {
                    console.log('Fetched data:', data);

                    // Update unit details
                    document.getElementById("unit_code").innerText = data.standard.unit_code;
                    document.getElementById("unit_title").innerText = data.standard.unit_title;
                    document.getElementById("unit_description").innerText = data.standard.unit_description;

                    // Reinitialize DataTable
                    const table = $('#manage-table').DataTable();
                    table.destroy();

                    // Update table header
                    const tableHeader = document.querySelector('#manage-table thead');
                    tableHeader.innerHTML = `
                        <tr>
                            <th>#</th>
                            <th>Student Full Name</th>
                            <th>Student NISN</th>
                            <th>Final Score (%)</th>
                            <th>Competency Status</th>
                            <th>Actions</th>
                        </tr>
                    `;

                    // Update table body
                    const tableBody = document.querySelector('#manage-table tbody');
                    tableBody.innerHTML = ''; // Bersihkan konten sebelumnya

                    const studentsArray = Object.values(data.students);
                    studentsArray.forEach((student, index) => {
                        // Tambahkan baris utama
                        const mainRow = `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${student.student_name}</td>
                                <td>${student.student_nisn}</td>
                                <td>${student.final_score.toFixed(2)}%</td>
                                <td>
                                    <span class="badge ${student.status === 'Passed' ? 'bg-success' : 'bg-danger'}">
                                        ${student.status}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#details-${index}">
                                        View Details
                                    </button>
                                </td>
                            </tr>
                        `;

                        // Tambahkan baris detail
                        const detailRow = `
                            <tr id="details-${index}" class="collapse">
                                <td colspan="6">
                                    <div class="table-responsive" style="overflow-x: auto;">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    ${data.elements.map(el => `<th>${el.criteria}</th>`).join('')}
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    ${student.elements.map(el => `
                                                        <td>
                                                            <span class="badge ${el.status === 'Passed' ? 'bg-success' : 'bg-danger'}">
                                                                ${el.status}
                                                            </span>
                                                        </td>
                                                    `).join('')}
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        `;

                        // Tambahkan baris utama dan detail ke body tabel
                        tableBody.innerHTML += mainRow + detailRow;
                    });

                    // Reinitialize DataTable
                    $('#manage-table').DataTable();


                    // Update export link
                    const exportLink = document.getElementById("exportToExcelLink");
                    exportLink.href = `/admin/exam/report/${data.standard.id}/excel`;
                })
                .catch(error => console.error('Error:', error));
        }

    </script>
@endsection
