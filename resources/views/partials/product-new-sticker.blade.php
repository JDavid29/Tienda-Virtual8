@php
    // Configurable recency window in days
    $recentDays = 14;
    $created = optional($p->created_at);
    $updated = optional($p->updated_at);

    $flag = isset($p->is_new) ? (bool) $p->is_new : null;
    $isRecent = ($flag === true)
        || ($updated && $updated->gt(\Illuminate\Support\Carbon::now()->subDays($recentDays)))
        || ($created && $created->gt(\Illuminate\Support\Carbon::now()->subDays($recentDays)));
@endphp

@if($isRecent)
    <span class="sticker">Nuevo</span>
@endif
