<template>
  <PanelItem :field="field">
    <template #value>
      <InlineMultiSelectEditableValue :field="field" :resourceName="resourceName" />
    </template>
  </PanelItem>
</template>

<script>
import InlineMultiSelectEditableValue from './InlineMultiSelectEditableValue';

export default {
  components: { InlineMultiSelectEditableValue },
  props: ['resource', 'resourceName', 'resourceId', 'field'],
};
</script>
