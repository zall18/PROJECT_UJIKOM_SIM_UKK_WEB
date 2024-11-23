    @extends('index')
    @section('container')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"><a href="/assesment">Assesment</a> / {{ $standard->unit_title }} / <a href="/assesment/competency-standard/{{ $standard->id }}">Student</a> /</span> {{ $student->user->full_name }}</h4>
    <div class="card">
        <h5 class="card-header">Assessment</h5>
        <div class="row m-2">
            <div class="col">
                <select id="roleSelect" class="form-select" name="role" onchange="fetchExamResultReport(this.value)">
                    @foreach ($students as $item)
                        <option value="{{ $item->id }}" {{ $student->id == $item->id ? 'selected' : '' }}>
                            {{ $item->user->full_name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row m-2">
            <div class="col">
                <button class="btn btn-primary w-100" id="setCompetent">Set all Competent</button>
            </div>
            <div class="col">
                <button class="btn btn-danger w-100" id="setNotCompetent">Set all Not Competent</button>
            </div>
        </div>
        <form action="/assessment/grading/{{ $student->id }}" method="post" id="gradingForm">
        @csrf
        <input type="hidden" name="standard_id" value="{{ $standard->id }}">
        <div class="table-responsive text-nowrap">
            <table class="table" id="manage-table">
                <thead>
                    <tr>
                        <th>Id. </th>
                        <th>Criteria</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                        @foreach ($elements as $index => $item)
                        {{-- @dd($index) --}}
                        @php
                            // Cari elemen ujian berdasarkan element_id
                            $examination = $examinations->firstWhere('element_id', $item->id);
                        @endphp
                        <input type="hidden" name="elements[]" value="{{ $item->id }}">
                        <tr>
                            <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong>{{ $index + 1 }}</strong>
                            </td>
                            <td>{{ $item->criteria }}</td>
                            <td>
                                <input type="radio" name="status{{ $index }}" value="1" id="status" {{ $examination && $examination->status == 1 ? 'checked' : '' }}> Competent
                                <input type="radio" name="status{{ $index }}" value="0" id="status" {{ $examination && $examination->status == 0 ? 'checked' : '' }}> Not Competent
                            </td>

                        </tr>

                        @endforeach

                        <tr>
                            <td colspan="3">
                            <button type="submit" class="btn btn-primary w-100" >Save changes</button>

                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </form>
    </div>

    <script>
        // Event listener untuk tombol "Set all Competent"
        document.getElementById('setCompetent').addEventListener('click', function() {
            const radioButtons = document.querySelectorAll('input[type="radio"][value="1"]');
            radioButtons.forEach(radio => radio.checked = true);
        });

        // Event listener untuk tombol "Set all Not Competent"
        document.getElementById('setNotCompetent').addEventListener('click', function() {
            const radioButtons = document.querySelectorAll('input[type="radio"][value="0"]');
            radioButtons.forEach(radio => radio.checked = true);
        });

        document.getElementById('gradingForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Mencegah reload halaman

            const form = event.target;
            const formData = new FormData(form); // Ambil semua data dari form

            // Kirim data menggunakan fetch
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest', // Tandai permintaan sebagai AJAX
                },
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json(); // Ubah respons ke JSON jika server mengirimkan JSON
            })
            .then(data => {
                // Tampilkan pesan sukses atau lakukan sesuatu dengan respons
                console.log('Form submitted successfully:', data);
                alert('Changes saved successfully!');
            })
            .catch(error => {
                console.error('Error submitting form:', error);
                alert('Failed to save changes. Please try again.');
            });
        });


        function fetchExamResultReport(studentId) {
            const standardId = {{ $standard->id }}; // Ambil ID standard yang sedang aktif

            // Kirim permintaan AJAX
            fetch(`/assesment/fetch/competency-standard/${standardId}/student/${studentId}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Data fetched:', data);

                    // Update nama student di header
                    document.querySelector('h4 span.text-muted').innerHTML = `
                        <a href="/assesment">Assesment</a> /
                        ${data.standard.unit_title} /
                        <a href="/assesment/competency-standard/${data.standard.id}">Student</a> /
                        ${data.student.user.full_name}
                    `;

                    // Update tabel elemen dan radio button
                    const tableBody = document.querySelector('#manage-table tbody');
                    tableBody.innerHTML = '';

                    data.elements.forEach((element, index) => {
                        const examination = data.examinations.find(exam => exam.element_id === element.id);
                        const competentChecked = examination && examination.status === 1 ? 'checked' : '';
                        const notCompetentChecked = examination && examination.status === 0 ? 'checked' : '';

                        const row = `
                            <tr>
                                <input type="hidden" name="elements[]" value="${element.id}">
                                <td><strong>${index + 1}</strong></td>
                                <td>${element.criteria}</td>
                                <td>
                                    <input type="radio" name="status${index}" value="1" ${competentChecked}> Competent
                                    <input type="radio" name="status${index}" value="0" ${notCompetentChecked}> Not Competent
                                </td>
                            </tr>
                        `;
                        tableBody.innerHTML += row;
                    });

                    // Tambahkan tombol save di akhir
                    const saveButtonRow = `
                        <tr>
                            <td colspan="3">
                                <button type="submit" class="btn btn-primary w-100">Save changes</button>
                            </td>
                        </tr>
                    `;
                    tableBody.innerHTML += saveButtonRow;

                    // Update action pada form
                    const form = document.querySelector('form');
                    form.action = `/assessment/grading/${data.student.id}`;
                })
                .catch(error => console.error('Error fetching data:', error));
        }

    </script>
    @endsection
