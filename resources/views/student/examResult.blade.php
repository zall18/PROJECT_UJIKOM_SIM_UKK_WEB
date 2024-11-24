@extends('index')

@section('container')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Tables /</span> Exam Result</h4>

    <!-- Basic Bootstrap Table -->
    <div class="card">
        <h5 class="card-header">Your Exam Result</h5>
        <div class="table-responsive text-nowrap">
            <table class="table" id="manage-table">
                <thead>
                    <tr>
                        <th>Id. </th>
                        <th>Competency Standard</th>
                        <th>Final Score</th>
                        <th>Status</th>
                        <th>Certicate</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($statusSummary as $index => $item)
                        <tr>
                            <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong>{{ $index + 1 }}</strong>
                            </td>
                            <td>{{ $item['unit_title'] }}</td>
                            <td>{{ $item['final_score'] }}</td>
                            <td>{{ $item['status'] }}</td>
                            <td>
                                <form action="/exam-result/certificate" method="post"
                                    @if ($item['final_score'] > 60) target="_blank" @endif>
                                    @csrf
                                    <input type="hidden" name="name" value="{{ Auth::user()->full_name }}">
                                    <input type="hidden" name="program" value="{{ $item['unit_title'] }}">
                                    <input type="hidden" name="final_score" value="{{ $item['final_score'] }}">
                                    <button class="btn btn-primary" type="submit">Generate</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
    <!--/ Basic Bootstrap Table -->

    <div class="buy-now">
        <a href="/major/create" class="btn btn-danger btn-buy-now">Create Major Data</a>
    </div>
@endsection
