@extends('index')

@section('container')
    <style>
        .table td {
            white-space: normal;
            word-wrap: break-word;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 150px;
            /* Sesuaikan dengan kebutuhan */
        }

        .table td:hover {
            overflow: visible;
            /* Tampilkan semua teks saat di-hover */
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

                    <a href="/exam/report/{{ $standard->id }}/excel" id="exportToExcelLink">
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
                                <span
                                    class="badge {{ $student['status'] === 'Not Competent' ? 'bg-danger' : 'bg-success' }}">
                                    {{ $student['status'] }}
                                </span>
                            </td>
                            <td>
                                <!-- Change this to trigger modal -->
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#detailsModal-{{ $index }}">
                                    View Details
                                </button>
                            </td>
                        </tr>
                        {{-- <p>{{ $index }}</p> --}}
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal structure -->
    <div id="modal-data">
        @foreach ($students as $index => $student)
            <div class="modal fade" id="detailsModal-{{ $index }}" tabindex="-1"
                aria-labelledby="detailsModalLabel-{{ $index }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="detailsModalLabel-{{ $index }}">Student:
                                {{ $student['student_name'] }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Criteria</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($student['elements'] as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $elements[$index]->criteria }}</td>
                                            <td><span
                                                    class="badge {{ $item['status'] === 'Competent' ? 'bg-success' : 'bg-danger' }}">{{ $item['status'] }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>



    <script>
        function fetchExamResultReport(standardId) {
            fetch(`/exam-results/report/${standardId}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (!data || !data.students) {
                        console.error('Invalid or empty data');
                        return;
                    }

                    // Update unit details
                    document.getElementById("unit_code").innerText = data.standard.unit_code;
                    document.getElementById("unit_title").innerText = data.standard.unit_title;
                    document.getElementById("unit_description").innerText = data.standard.unit_description;

                    // Check if DataTable is initialized
                    if ($.fn.dataTable.isDataTable('#manage-table')) {
                        $('#manage-table').DataTable().clear().destroy();
                    }

                    const tableBody = document.querySelector('#manage-table tbody');
                    tableBody.innerHTML = ''; // Clear previous content

                    // Clear modal data
                    const modaldata = document.getElementById("modal-data");
                    modaldata.innerHTML = ''; // Clear existing modals

                    // Prepare new rows with dynamic data
                    const studentArray = Object.values(data.students);
                    studentArray.forEach((student, index) => {
                        // Insert a row for each student
                        const mainRow = `
            <tr>
                <td>${index + 1}</td>
                <td>${student.student_name}</td>
                <td>${student.student_nisn}</td>
                <td>${student.final_score.toFixed(2)}%</td>
                <td>
                    <span class="badge ${student.status === 'Not Competent' ? 'bg-danger' :  'bg-success'}">
                        ${student.status}
                    </span>
                </td>
                <td>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#detailsModal-${index}">
                        View Details
                    </button>
                </td>
            </tr>
        `;
                        tableBody.innerHTML += mainRow;

                        // Generate modal for each student
                        const modalHtml = `
            <div class="modal fade" id="detailsModal-${index}" tabindex="-1" aria-labelledby="detailsModalLabel-${index}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="detailsModalLabel-${index}">Student: ${student.student_name}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Criteria</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${student.elements.map((item, elementIndex) => {
                                        return `
                                                                                <tr>
                                                                                    <td>${elementIndex + 1}</td>
                                                                                    <td>${data.elements[elementIndex].criteria}</td>
                                                                                    <td>
                                                                                        <span class="badge ${item.status === 'Not Competent' ? 'bg-danger' : 'bg-success'}">
                                                                                            ${item.status}
                                                                                        </span>
                                                                                    </td>
                                                                                </tr>
                                                                            `;
                                    }).join('')}
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
                        modaldata.innerHTML += modalHtml; // Append modal to the modal-data div
                    });

                    // Re-initialize the DataTable with the new data
                    $('#manage-table').DataTable();

                    // Update export link
                    document.getElementById("exportToExcelLink").href = `/exam/report/${data.standard.id}/excel`;
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
@endsection
