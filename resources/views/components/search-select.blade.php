@props([
    'datas' => [],
    'label' => 'Select',
    'name'  => 'country_id',
    'selected' => ''
])

<div class="form-group mb-3">
    <label for="form-label">{{ $label }}</label>
    <select class="form-control" name="{{ $name }}">
        <option value="" disabled>{{ $label }}</option>
        @foreach($datas as $data)
            <option value="{{ $data->id }}" {{ $data->name === $selected ? 'selected' : '' }}>{{ $data->name }}</option>
        @endforeach
    </select>
</div>
