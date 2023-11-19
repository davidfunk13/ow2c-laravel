<?php

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AdditionalHeroValidationRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, $value, Closure $fail): void
    {
        // Check if 'additional_hero_played_2' is not empty and 'additional_hero_played_1' is not empty
        $additionalHero1 = request('additional_hero_played_1');
        if (!empty($value) && !empty($additionalHero1)) {
            return;
        }

        $fail("The additional hero is only accepted if something is in the first additional hero field.");
    }
}
