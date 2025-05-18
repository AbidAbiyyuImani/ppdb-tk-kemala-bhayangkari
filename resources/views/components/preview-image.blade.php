@props([
  'id'    => null,
  'src'   => null,
])

@php
  use Illuminate\Support\Facades\Storage;
  use Illuminate\Support\Str;

  $exists = $src && Storage::disk('public')->exists($src);

  if ($exists) {
      $mime = Storage::disk('public')->mimeType($src);
      $isImage = Str::startsWith($mime, 'image/');
  } else {
      $isImage = false;
  }

  $imageUrl = $isImage 
    ? asset('storage/' . $src) 
    : '';
@endphp

<img
  id="{{ $id }}"
  src="{{ $imageUrl }}"
  {{ $attributes->merge(['class' => 'w-36 mb-2']) }}
  style="{{ $isImage ? '' : 'display: none;' }}"
/>

@once
  <script>
    function previewImage(inputId, previewId) {
      const input   = document.getElementById(inputId);
      const preview = document.getElementById(previewId);

      if (!input.files || !input.files[0]) {
        preview.style.display = 'none';
        return;
      }

      const file = input.files[0];
      const allowedTypes = [
        'image/jpg','image/jpeg','image/png','image/bmp',
        'image/gif','image/svg+xml','image/webp'
      ];

      if (!allowedTypes.includes(file.type)) {
        preview.style.display = 'none';
        preview.src = '';
        return;
      }

      const reader = new FileReader();
      reader.onload = e => {
        preview.src = e.target.result;
        preview.style.display = 'block';
      };
      reader.readAsDataURL(file);
    }
  </script>
@endonce