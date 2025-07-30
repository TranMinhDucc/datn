@php
    $stt = 1;
    $categoryMap = $categories->pluck('name', 'id')->toArray();
    renderRows($categories, null, 0, $stt, $categoryMap);
@endphp