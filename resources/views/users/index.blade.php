
<form method="POST" action="{{ route('users.store') }}">
@csrf

<input name="name" placeholder="الاسم">
<input name="email" placeholder="الإيميل">
<input name="password" type="password">

<select name="role">
    <option value="Admin">Admin</option>
    <option value="Collector">Collector</option>
    <option value="Supervisor">Supervisor</option>
</select>

<select name="collector_id">
    <option value="">-- اختر مندوب --</option>
    @foreach($collectors as $collector)
        <option value="{{ $collector->id }}">{{ $collector->name }}</option>
    @endforeach
</select>

<button>حفظ</button>
</form>
@if ($errors->any())
    <div>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<table>
    <thead>
        <tr>
            <th>الاسم</th>
            <th>الإيميل</th>
            <th>الدور</th>
            <th>المندوب</th>
            <th>الإجراءات</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->getRoleNames()->first() }}</td>
            <td>{{ $user->collector ? $user->collector->name : 'N/A' }}</td>
            <td>
                <form method="POST" action="{{ route('users.destroy', $user->id) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit">حذف</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
