@extends('index')
@section('container')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Forms/</span> Competency Standard/ Competency Element</h4>

    <!-- Basic Layout -->
    <div class="row">
        <div class="col-xl">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Please fill the blank field</h5>
                    <small class="text-muted float-end">Merged input group</small>
                </div>
                <div class="card-body">
                    <form action="/competency-standard/competency-elements" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{ $id }}">
                        <div id="criteria-container">
                            <div class="criteria-item mb-3">
                                <label class="form-label" for="basic-icon-default-company">Criteria</label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="bx bx-buildings"></i></span>
                                    <input type="text" class="form-control"
                                        placeholder="CB00*, Etc" name="criterias[]" />
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-secondary" onclick="addCriteria()">Tambah</button>
                        <button type="submit" class="btn btn-primary">Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    function addCriteria() {
        // Clone the first criteria item and reset the input value
        let criteriaContainer = document.getElementById('criteria-container');
        let newCriteria = criteriaContainer.firstElementChild.cloneNode(true);
        newCriteria.querySelector('input').value = ''; // clear the input field
        criteriaContainer.appendChild(newCriteria); // add the new input to the container
    }
</script>
