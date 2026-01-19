@props([
    'type' => null,
    'image' => null,
    'imagePath' => null,
    'size' => null,
    'name' => 'image',
    'id' => 'image-upload-input1',
    'accept' => '.png, .jpg, .jpeg',
    'required' => false,
    'darkMode' => false,
    'showSizeFileType' => true,
])
@php
    $size = $size ?? getFileSize($type);
    $imagePath = $imagePath ?? getImage(getFilePath($type) . '/' . $image);
@endphp
<div {{ $attributes->merge(['class' => 'image--uploader']) }}>
    <div class="image-upload-wrapper">
        <div class="image-upload-preview {{ $darkMode ? 'bg--dark' : '' }}"
            style="background-image: url({{ $imagePath }})">
        </div>

        <input type="file" class="image-upload-input" name="{{ $name }}" id="{{ $id }}"
            accept="{{ $accept }}" @required($required)>
        <label for="{{ $id }}"></label>

    </div>
    @if ($size && $showSizeFileType)
        <div class="mt-2 image__support_file_size">
            <button data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ $accept }}">
                @lang('Supported Files')
            </button>
            <button data-bs-toggle="tooltip" data-bs-placement="top"
                data-bs-title="{{ $size }}  @lang('px')">
                @lang('Image Size')
            </button>

        </div>
    @endif
</div>
