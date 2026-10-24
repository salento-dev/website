<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\AnalyticsEvent;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAnalyticsEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string', Rule::in(array_keys(AnalyticsEvent::LABELS))],
            'url' => ['required', 'string', 'max:2048'],
            'path' => ['required', 'string', 'starts_with:/', 'max:255'],
            'label' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.in' => 'Tipo di evento non valido.',
            'url.required' => 'La destinazione del click è obbligatoria.',
            'path.required' => 'La pagina di origine è obbligatoria.',
            'path.starts_with' => 'La pagina di origine deve essere un percorso relativo.',
        ];
    }
}
