@extends('index')

@section('container')
    <div class="row">

        <div class="col-lg-12 mb-2 order-0">
            <div class="row">
                @foreach ($majors as $item)
                <div class="col-lg-6 col-md-12 col-6 mb-4 text-center">
                    <a href="/major/student/{{ $item->id }}" class="text-dark">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="card-title d-flex align-items-center justify-content-center">
                                    <div class="avatar flex-shrink-0">
                                        <i class="fi fi-ss-user-graduate fs-3 text-primary"></i>
                                    </div>
                                </div>
                                <span>{{ $item->major_name }}</span>

                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>



    </div>
@endsection
