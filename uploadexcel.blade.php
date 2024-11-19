<!-- @extends('layout.app')

@section('content')
<form action="{{ route('employee.uploadexcel') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <label for="excel_file">Upload File:</label>
    <input type="file" name="excel_file" id="excel_file" required>
    <button type="submit">Upload</button>
</form>
@endsection -->
