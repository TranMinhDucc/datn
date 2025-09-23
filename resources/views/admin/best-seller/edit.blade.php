@extends('layouts.admin')
@section('content')
<h1>Edit Best Seller Section</h1>
<form action="{{ route('admin.best-seller.update', $item) }}" method="post" enctype="multipart/form-data">
  @method('PUT')
  @include('admin.best-seller._form')
</form>
@endsection
