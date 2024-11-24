@extends('index')
@section('container')
    <div class="card">
        <h5 class="card-header">Instruction for major id</h5>
        <div class="table-responsive text-nowrap">
            <table class="table" id="manage-table">
                <thead>
                    <tr>
                        <th>Id. </th>
                        <th>Major Name</th>
                        <th>Description</th>

                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($majors as $index => $item)
                        <tr>
                            <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong>{{ $item->id }}</strong>
                            </td>
                            <td>{{ $item->major_name }}</td>
                            <td>{{ $item->description }}</td>

                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>

    <div class="card mt-3">
        <h5 class="card-header">WARNING! The layout of excel must like this</h5>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Nisn</th>
                        <th>Grade_level</th>
                        <th>Major Id</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    {{-- @foreach ($majors as $index => $item) --}}
                    <tr>
                        {{-- <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong>{{ $index + 1 }}</strong>
                        </td> --}}
                        <td>Example</td>
                        <td>example@gmail.com</td>
                        <td>08#########</td>
                        <td>00#######</td>
                        <td>12</td>
                        <td>1</td>
                    </tr>
                    {{-- @endforeach --}}

                </tbody>
            </table>
        </div>
    </div>

    <form action="/student/create/excel" method="POST" enctype="multipart/form-data"
        class="form p-4 border rounded shadow-sm bg-light mt-2">
        @csrf
        <div class="mb-3">
            <label for="file" class="form-label fw-bold">Upload File</label>
            <input type="file" class="form-control" id="file" name="file" accept=".xlsx, .xls, .csv" required>
            <div class="form-text">Format yang didukung: .xlsx, .xls, .csv</div>
        </div>
        <button type="submit" class="btn btn-primary w-100">Import Data</button>
    </form>
@endsection
