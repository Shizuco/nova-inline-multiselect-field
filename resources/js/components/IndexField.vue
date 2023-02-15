<template>
  <InlineMultiSelectEditableValue :field="field" :resourceName="resourceName" />
</template>

<script>
import InlineMultiSelectEditableValue from './InlineMultiSelectEditableValue';

export default {
  components: { InlineMultiSelectEditableValue },
  props: ['resourceName', 'field'],
};
</script>
