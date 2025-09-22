@extends('layouts.admin')
@section('content')
<h1>Create Best Seller Section</h1>
<form action="{{ route('admin.best-seller.store') }}" method="post" enctype="multipart/form-data">
  @include('admin.best-seller._form')
</form>
@endsection
