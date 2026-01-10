@extends('layouts.app')

@section('content')
<div class="container">
   <h3>كشف حساب العميل: {{ $customer->name }}</h3>

<table class="table table-bordered">
<tr>
    <th>التاريخ</th>
    <th>البيان</th>
    <th>مدين</th>
    <th>دائن</th>
    <th>الرصيد</th>
</tr>

@foreach($accounts as $row)
<tr>
    <td>{{ $row->date }}</td>
    <td>{{ $row->description }}</td>
    <td>{{ $row->debit }}</td>
    <td>{{ $row->credit }}</td>
    <td>{{ $row->balance }}</td>
</tr>
@endforeach
</table>
</div>
@endsection