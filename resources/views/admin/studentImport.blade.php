@extends('index')
@section('container')
<form action="/student/create/excel" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file" accept=".xlsx, .xls, .csv">
    <button type="submit">Import</button>
</form>
@endsection
