@extends('index')
@section('container')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Forms/</span> Major Form</h4>

    <!-- Basic Layout -->
    <div class="row">

        <div class="col-xl">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Update the field you want</h5>
                    <small class="text-muted float-end">Merged input group</small>
                </div>
                <div class="card-body">
                    <form action="/major/update" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{ $major->id }}">
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-default-company">Major Name</label>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-company2" class="input-group-text"><i
                                        class="bx bx-buildings"></i></span>
                                <input type="text" id="basic-icon-default-company" class="form-control"
                                    placeholder="RPL, TKJ, Etc" aria-label="ACME Inc." name="major_name"
                                    aria-describedby="basic-icon-default-company2" value="{{ $major->major_name }}" />
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-default-message">Description</label>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-message2" class="input-group-text"><i
                                        class="bx bx-comment"></i></span>
                                <textarea id="basic-icon-default-message" class="form-control" placeholder="Description about major" name="description"
                                    aria-label="Description about major" aria-describedby="basic-icon-default-message2">{{ $major->description }}</textarea>
                            </div>
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
