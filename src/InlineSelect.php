<?php

namespace Shizuco\NovaInlineSelectField;

use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class InlineSelect extends Select
{
    public $component = 'inline-multiselect-field';

    protected function resolveAttribute($resource, $attribute)
    {
        $this->withMeta(['resourceId' => $resource->getKey()]);
        return parent::resolveAttribute($resource, $attribute);
    }

    public function resolve($resource, $attribute = null)
    {
        parent::resolve($resource, $attribute);

        /** @var NovaRequest */
        $novaRequest = app()->make(NovaRequest::class);
        if ($novaRequest->isFormRequest()) {
            $this->component = 'multiselect-field';
        }

    }

    public function maxWidth(int | null $maxWidthPx = null)
    {
        return $this->withMeta(['maxWidth' => $maxWidthPx]);
    }

}
