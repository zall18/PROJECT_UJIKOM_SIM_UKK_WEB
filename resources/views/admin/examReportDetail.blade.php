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
            <table class="table">
                <thead>
                    <tr>
                        <th>Id. </th>
                        <th>Student Full Name</th>
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
@endsection
