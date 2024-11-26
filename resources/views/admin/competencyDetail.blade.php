@extends('index')

@section('container')
    <div class="card h-auto">
        <div class="d-flex align-items-end row">
            <div class="col-sm-12">
                <div class="card-body">
                    <h5 class="card-title text-primary">Exam Result <span id="unit_title">{{ $competency->unit_title }}</span>
                    </h5>
                    <p class="mb-1" id="unit_code">
                        {{ $competency->unit_code }}
                    </p>
                    <p class="mb-4" id="unit_description">
                        {{ $competency->unit_description }}
                    </p>
                    <a href="/admin/competency-standard/delete/{{ $competency->id }}" class=""
                        data-confirm-delete="true">
                        <button type="submit" class="btn btn-danger w-100 mb-2">Delete Competecy Standard</button>
                    </a>
                    <a href="/admin/competency-standard/update/{{ $competency->id }}">
                        <button type="submit" class="btn btn-primary w-100">Update Competecy Standard</button>
                    </a>
                </div>
            </div>
        </div>

    </div>
    <div class="card mt-3">
        <h5 class="card-header">Table Competency Elements</h5>
        <div class="table-responsive text-nowrap">
            <table class="table" id="manage-table">
                <thead>
                    <tr>
                        <th>Id. </th>
                        <th>Criteria Competency</th>
                        <th>Actions</th>

                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($elements as $index => $item)
                        <tr>
                            <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong>{{ $index + 1 }}</strong>
                            </td>
                            <td>{{ $item->criteria }}</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item"
                                            href="/admin/competency-standard/competency-elements/update/{{ $competency->id }}/{{ $item->id }}"><i
                                                class="bx bx-edit-alt me-1"></i>
                                            Edit</a>
                                        <a class="dropdown-item"
                                            href="/admin/competency-standard/competency-elements/delete/{{ $competency->id }}/{{ $item->id }}"
                                            data-confirm-delete="true"><i class="bx bx-trash me-1"></i>
                                            Delete</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>


    <div class="buy-now">
        <a href="/admin/competency-standard/competency-elements/{{ $competency->id }}" class="btn btn-danger btn-buy-now">Create
            Another Competency Elements</a>
    </div>
@endsection
