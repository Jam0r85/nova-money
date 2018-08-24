<?php

namespace Jam0r85\NovaMoney;

use Brick\Money\Money as MoneyMaker;
use Laravel\Cashier\Cashier;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
use Symfony\Component\Intl\Intl;

class Money extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-money';

    /**
     * The value is stored in minor units.
     *
     * @var boolean
     */
    public $minorUnits = true;

    /**
     * The locale the field will be displayed in.
     *
     * @var string
     */
    public $locale;

    /**
     * The currency to be used when displaying the value.
     *
     * @var string
     */
    public $currency;

    /**
     * Create a new field.
     *
     * @param  string  $name
     * @param  string|null  $attribute
     * @param  mixed|null  $resolveCallback
     * @return void
     */
    public function __construct($name, $attribute = null, $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->loadDefaults();

        $this->displayUsing(function ($value) {
            return $this->getDisplay($value);
        });
    }

    /**
     * Load the default values for this package.
     *
     * @return void
     */
    protected function loadDefaults()
    {
        // $this->currency = 'GBP';
        $this->currency = strtoupper(Cashier::usesCurrency());

        $this->withMeta([
            'minor_units' => $this->minorUnits,
            'currency' => Intl::getCurrencyBundle()->getCurrencySymbol($this->currency)
        ]);
    }

    /**
     * Grab the display for the field.
     *
     * @param  string  $value
     * @return mixed
     */
    protected function getDisplay($value)
    {
        $locale = $this->locale ?? config('app.locale');

        if ($this->minorUnits) {
            $money = MoneyMaker::ofMinor($value, $this->currency);
        } else {
            $money = MoneyMaker::of($value, $this->currency);
        }

        return $money->formatTo($locale);
    }

    /**
     * Hydrate the given attribute on the model based on the incoming request.
     *
     * @param  \Laravel\Nova\Http\NovaRequest  $request
     * @param  string  $requestAttribute
     * @param  object  $model
     * @param  string  $attribute
     * @return void
     */
    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        if ($request->exists($requestAttribute) && $this->minorUnits) {
            $model->{$attribute} = $request[$requestAttribute] * 100;
        }
    }

    /**
     * We are not storing values as minor units.
     *
     * @return $this
     */
    public function notMinorUnits()
    {
        $this->minorUnits = false;

        return $this->withMeta([
            'minor_units' => false
        ]);
    }
    

    /**
     * The currency value that represents the values.
     *
     * @param  string  $currency
     * @return $this
     */
    public function currency($currency)
    {
        $this->currency = $currency;

        return $this->withMeta([
            'currency' => Intl::getCurrencyBundle()->getCurrencySymbol($currency)
        ]);
    }

    /**
     * The monetary locale the field will be displayed in.
     *
     * @param  string  $locale
     * @return $this
     */
    public function locale($locale)
    {
        $this->locale = $locale;

        return $this;
    }
}
