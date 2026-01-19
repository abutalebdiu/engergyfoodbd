<?php

namespace App\Http\Requests\Property;

use App\Rules\FileTypeValidate;
use Illuminate\Foundation\Http\FormRequest;

class PropertyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "title"                    => "required|string|max:255",
            "title_ar"                 => "required|string|max:255",
            "slug"                     => "required|string|max:255",
            "property_type_id"         => "required|exists:property_types,id",
            "construction_type"        => "required|string|max:255",
            "purpose"                  => "required|string|max:255",
            "electricity"              => "required|string|max:255",
            "water"                    => "required|string|max:255",
            "length"                   => "nullable|string|max:191",
            "width"                    => "nullable|string|max:191",
            "age"                      => "nullable|string|max:191",
            "bed_rooms"                => "nullable|string|max:191",
            "bath_rooms"               => "nullable|string|max:191",
            "living_room"              => "nullable|string|max:191",
            "guest_room"               => "nullable|string|max:191",
            "land_area"                => "nullable|string|max:191",
            "description"              => "required|string",
            "description_ar"           => "required|string",
            "price"                    => "numeric",
            "sqr_price"                => "numeric",
            "reference_no"             => "nullable|string|max:191",
            "country_id"               => "required|exists:countries,id",
            "state_id"                 => "nullable|exists:states,id",
            "city_id"                  => "required|exists:cities,id",
            "district_id"                 => "nullable|exists:districts,id",
            "features"                 => "nullable|string",
            "features_ar"              => "nullable|string",
            "fixtures"                 => "nullable|string",
            "fixtures_ar"              => "nullable|string",
            "street"                   => "nullable|string",
            "street_width"             => "nullable|string",
            "facing"                   => "nullable|string",
          
        ];
    }
}
