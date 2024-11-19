@extends('layout.app')

@section('content')
<form action="{{ route('employee.upload') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <label for="csv_file">Upload File:</label>
    <input type="file" name="csv_file" id="csv_file" required>
    <button type="submit">Upload</button>
</form>
@endsection
