@extends('index')
@section('container')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Forms/</span> Competency Standard</h4>

    <!-- Basic Layout -->
    <div class="row">

        <div class="col-xl">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Please fill the blank field</h5>
                    <small class="text-muted float-end">Merged input group</small>
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger m-2">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="card-body">
                    <form action="/admin/competency-standard/create" method="post">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-default-company">Unit Code</label>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-company2" class="input-group-text"><i
                                        class="bx bx-buildings"></i></span>
                                <input type="text" id="basic-icon-default-company" class="form-control"
                                    placeholder="CB00*, Etc" aria-label="CB00*, Etc" name="unit_code"
                                    aria-describedby="basic-icon-default-company2" value="{{ old('unit_code') }}" />
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-default-message">Unit Title</label>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-message2" class="input-group-text"><i
                                        class="bx bx-comment"></i></span>
                                <input id="basic-icon-default-message" class="form-control" placeholder="*Skill pasport"
                                    name="unit_title" type="text" aria-label="*Skill pasport"
                                    aria-describedby="basic-icon-default-message2" value="{{ old('unit_title') }}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-default-message">Unit Description</label>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-message2" class="input-group-text"><i
                                        class="bx bx-comment"></i></span>
                                <textarea id="basic-icon-default-message" class="form-control" placeholder="Bla, bla, bla" name="unit_description"
                                    aria-label="*Skill pasport" aria-describedby="basic-icon-default-message2">{{ old('unit_description') }}</textarea>
                            </div>
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
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-default-company">Assessor</label>
                            <select id="defaultSelect" class="form-select" name="assessor_id">
                                <option>Default select</option>
                                @foreach ($assessors as $item)
                                    <option value="{{ $item->id }}">{{ $item->user->full_name }}</option>
                                @endforeach
                            </select>
                            {{-- <label class="form-label" for="assessor">Assessor</label>
                            @foreach ($assessors as $item)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="assessor[]" id="assessor{{ $item->id }}" value="{{ $item->id }}">
                                    <label class="form-check-label" for="assessor{{ $item->id }}">
                                        {{ $item->user->full_name }}
                                    </label>
                                </div>
                            @endforeach --}}
                        </div>
                        <button type="submit" class="btn btn-primary">Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- / Content -->
@endsection
