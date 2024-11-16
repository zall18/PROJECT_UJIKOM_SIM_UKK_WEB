@extends('index')

@section('container')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Tables /</span> Competency Standard Tables</h4>

    <!-- Basic Bootstrap Table -->
    <div class="card">
        <h5 class="card-header">Table Competency Standard</h5>
        <div class="table-responsive text-nowrap">
            <table class="table" id="manage-table">
                <thead>
                    <tr>
                        <th>Id. </th>
                        <th>Unit Code</th>
                        <th>Unit Title</th>
                        <th>Unit Description</th>
                        <th>Major</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($competencies as $index => $item)
                        <tr>
                            <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong>{{ $index + 1 }}</strong>
                            </td>
                            <td>{{ $item->unit_code }}</td>
                            <td>
                                {{ $item->unit_title }}
                            </td>
                            <td>{{ $item->unit_description }}</td>
                            <td>{{ $item->major->major_name }}</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="/competency-standard/detail/{{ $item->id }}"><i
                                                class='bx bx-search-alt-2'></i>
                                            View</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
    <!--/ Basic Bootstrap Table -->

    <div class="buy-now">
        <a href="/competency-standard/create" class="btn btn-danger btn-buy-now">Create Competenncy Standard</a>
    </div>
@endsection
