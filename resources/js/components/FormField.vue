<template>
    <default-field :field="field">
        <template slot="field">
            <div class="flex flex-wrap items-stretch w-full relative">
              <div class="flex -mr-px">
                <span class="flex items-center leading-normal rounded rounded-r-none border border-r-0 border-60 px-3 whitespace-no-wrap bg-50 text-80 text-sm">
                  {{ field.currency }}
                </span>
              </div>  
              <input :id="field.name" type="text"
                  class="flex-shrink flex-grow flex-auto leading-normal w-px flex-1 rounded-l-none form-control form-input form-input-bordered"
                  :class="errorClasses"
                  :placeholder="field.name"
                  v-model="value"
              />
            </div>

            <p v-if="hasError" class="my-2 text-danger">
                {{ firstError }}
            </p>
        </template>
    </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'

export default {
    mixins: [FormField, HandlesValidationErrors],

    props: ['resourceName', 'resourceId', 'field'],

    computed: {
        /**
         * Check to see if we are using minor units.
         */
        usingMinorUnits() {
          if (this.field.minor_units) {
            return true;
          }

          return false;
        },
    },

    methods: {
        /*
         * Set the initial, internal value for the field.
         */
        setInitialValue() {
          this.value = this.formatValue(this.field.value) || ''
        },

        /**
         * Format the given value.
         */
        formatValue(value)
        {
          if (this.usingMinorUnits) {
            return value / 100;
          } else {
            return value;
          }
        },

        /**
         * Fill the given FormData object with the field's internal value.
         */
        fill(formData) {
          formData.append(this.field.attribute, this.value || '')
        },

        /**
         * Update the field's internal value.
         */
        handleChange(value) {
          this.value = value
        }
    }
}
</script>
