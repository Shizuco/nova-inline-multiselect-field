<template>
  <div :class="`nova-inline-multiselect-field-index text-${field.textAlign}${editing ? ' -editing' : ''} w-full`"
    @click.stop="e => !e.target.classList.contains('inline-icon')" @dblclick.stop.capture="startEditing">
    <template v-if="!editing">
      <EditIcon @click.stop.capture="startEditing" />

      <div :style="contentStyle" v-if="!hasValue">
        <p>&mdash;</p>
      </div>
      <div :style="contentStyle" v-else-if="field.asHtml" v-html="value"></div>
      <span :style="contentStyle" v-else class="whitespace-no-wrap">{{ value }}</span>
    </template>

    <template v-else>
      <multiselect :options="fieldOptions" :selected="fieldValue" :value="fieldValue" :preselected="fieldValue"
        v-model="fieldValue" label="label" track-by="label" ref="select" :disabled="loading">
      </multiselect>

      <ConfirmIcon @click.stop.capture="!loading ? updateFieldValue() : void 0" />
      <CancelIcon @click.stop.capture="cancelEditing" />
    </template>
  </div>
</template>

<script>
import EditIcon from '../icons/EditIcon';
import CancelIcon from '../icons/CancelIcon';
import ConfirmIcon from '../icons/ConfirmIcon';
import InteractsWithResourceInformation from '../../../../../vendor/laravel/nova/resources/js/mixins/InteractsWithResourceInformation';
import Multiselect from 'vue-multiselect';

export default {
  props: ['resourceName', 'field'],
  mixins: [InteractsWithResourceInformation],
  components: { EditIcon, CancelIcon, ConfirmIcon, Multiselect },

  data() {
    return {
      editing: false,
      loading: false,
      fieldValue: '',
      fieldOptions: [],
      selected: '',
      typed: ""
    };
  },

  mounted() {
    (Nova.request().get('/nova-api/comparisons/1?relationshipType=')).then((response) => {
      let arr = JSON.parse(JSON.stringify(this.field.options))
      var result = arr.filter(item => item.value == response.data.resource.fields[3]['belongsToId'])[0]
      this.field.value = (result === undefined) ? result : result.label
      this.selected = result
      this.fieldOptions = JSON.parse(JSON.stringify(this.field.options))
    })

  },

  methods: {

    onInputKeyPress(e) {
      if (e.which === 13) this.updateFieldValue();
    },

    startEditing() {
      if (this.editing) return;
      this.editing = true;
      this.$nextTick(() => this.$refs.input && this.$refs.input.focus());
    },

    cancelEditing() {
      if (this.loading) return;
      this.editing = false;
    },

    async updateFieldValue() {
      this.loading = true;
      let value = (this.fieldValue === null) ? null : this.fieldValue.value
      try {
        await Nova.request().post(`/nova-vendor/nova-inline-multiselect-field/update/${this.resourceName}`, {
          _inlineResourceId: this.field.resourceId,
          _inlineAttribute: this.field.attribute,
          [this.field.attribute]: value,
        });
        this.editing = false;
        this.field.value = (this.fieldValue === null) ? this.fieldValue : this.fieldValue.label

        Nova.success(
          this.__('The :resource was updated!', {
            resource: this.resourceInformation.singularLabel.toLowerCase(),
          })
        );
      } catch (e) {
        console.error(e);
        Nova.error(this.__('There was a problem submitting the form.'));
      }
      this.loading = false;
    },
  },

  computed: {
    hasValue() {
      return this.value !== null;
    },

    value() {
      return this.field.value || this.field.displayedAs;
    },

    contentStyle() {
      return {
        maxWidth: this.field.maxWidth ? `${this.field.maxWidth}px` : void 0,
      };
    },
  },
};
</script>

<style src="vue-multiselect/dist/vue-multiselect.css">

</style>

<style lang="scss">
.nova-inline-multiselect-field-index {
  position: relative;
  display: flex;
  align-items: center;

  >.edit-icon {
    height: 24px;
    width: 24px;
    margin-right: 2px;
    margin-bottom: 1px;
    flex-shrink: 0;
    min-width: 24px;
    cursor: pointer;
    padding: 4px;

    &:hover {
      fill: rgba(var(--colors-primary-500));
    }
  }

  >.cancel-icon,
  >.confirm-icon {
    height: 24px;
    width: 24px;
    cursor: pointer;
    margin-left: 6px;
    opacity: 0.6;

    &:hover {
      opacity: 1;
    }
  }

  >.cancel-icon {
    fill: #f87171;
  }

  >.confirm-icon {
    fill: #4ade80;
  }

  >.form-input {
    margin-right: 8px;
    max-width: 50vw;

    height: 1.75rem;
    padding-left: 0.5rem;
    padding-right: 0.5rem;
    font-size: 14px;
    max-height: calc(100% - 2px);
  }

}

.multiselect__content-wrapper {
  position: relative;
}
</style>
