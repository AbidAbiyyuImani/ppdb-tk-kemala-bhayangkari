@props([
    'action' => '',
])

<form method="GET" action="{{ $action }}" {{ $attributes->merge(['class' => 'mb-2 mx-auto sm:mx-0']) }}>
    <select name="filter" onchange="this.form.submit()"
        class="outline-none border border-gray-300 text-sm rounded px-2 py-1 focus:ring-blue-500 focus:border-blue-500">
        <option value="semua" {{ request('filter', 'aktif') == 'semua' ? 'selected' : '' }}>Semua</option>
        <option value="aktif" {{ request('filter', 'aktif') == 'aktif' ? 'selected' : '' }}>Hanya Aktif</option>
        <option value="terhapus" {{ request('filter', 'aktif') == 'terhapus' ? 'selected' : '' }}>Hanya Terhapus</option>
    </select>
</form>