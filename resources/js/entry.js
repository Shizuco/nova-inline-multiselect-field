import IndexField from './components/IndexField';
import DetailField from './components/DetailField';

Nova.booting((Vue, router) => {
  Vue.component('index-inline-multiselect-field', IndexField);
  Vue.component('detail-inline-multiselect-field', DetailField);
});
