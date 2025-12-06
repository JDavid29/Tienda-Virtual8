@php
    // Inputs:
    // - $image: the stored cover image value (string|null)
    // - $alt: alt text for the image
    // - $default: path to default image (optional)
    // - $attributesHtml: raw HTML attributes to add to the <img> (optional)
    $img = $image ?? null;
    $default = $default ?? 'images/default.png';
    if ($img) {
        // Absolute URL
        if (\Illuminate\Support\Str::startsWith($img, ['http://','https://'])) {
            $imgUrl = $img;
        } else {
            $candidate = ltrim($img, '/');
            // 1) If file exists in public (public/<candidate>) use asset(<candidate>)
            if (file_exists(public_path($candidate))) {
                $imgUrl = asset($candidate);
            }
            // 2) If file exists in storage/app/public/<candidate> use asset('storage/<candidate>')
            elseif (file_exists(storage_path('app/public/' . $candidate))) {
                $imgUrl = asset('storage/' . $candidate);
            }
            // 3) If it looks like a relative path starting with known folders, try asset(<candidate>)
            elseif (\Illuminate\Support\Str::startsWith($candidate, ['images/', 'img/', 'storage/', 'uploads/', 'productos/'])) {
                $imgUrl = asset($candidate);
            }
            // 4) Fallback to storage path
            else {
                $imgUrl = asset('storage/' . $candidate);
            }
        }
    } else {
        $imgUrl = asset($default);
    }
@endphp
<img src="{{ $imgUrl }}" alt="{{ $alt ?? '' }}" {!! $attributesHtml ?? '' !!}>
