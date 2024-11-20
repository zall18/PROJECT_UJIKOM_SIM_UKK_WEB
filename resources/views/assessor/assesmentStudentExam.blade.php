@extends('index')
@section('container')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"><a href="/assesment">Assesment</a> / {{ $standard->unit_title }} / <a href="/assesment/competency-standard/{{ $standard->id }}">Student</a> /</span> {{ $student->user->full_name }}</h4>
<div class="card">
    <h5 class="card-header">Assessment</h5>
    <div class="row m-2">
        <div class="col">
            <button class="btn btn-primary w-100" id="setCompetent">Set all Competent</button>
        </div>
        <div class="col">
            <button class="btn btn-danger w-100" id="setNotCompetent">Set all Not Competent</button>
        </div>
    </div>
    <form action="/assessment/grading/{{ $student->id }}" method="post">
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
</script>
@endsection
