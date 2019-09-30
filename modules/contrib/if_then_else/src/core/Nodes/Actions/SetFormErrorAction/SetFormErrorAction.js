// New add action for setting default values of fields
var VueSetFormErrorActionControl = {
  components: {
    // Component included for Multiselect.
    Multiselect: window.VueMultiselect.default
  },
  props: ['getData', 'putData', 'emitter'],
  provide() {
    return {
      emitter: this.emitter
    }
  },
  data(){
    return{
      type: drupalSettings.if_then_else.nodes.set_form_error_action.type,
      class: drupalSettings.if_then_else.nodes.set_form_error_action.class,
      name: drupalSettings.if_then_else.nodes.set_form_error_action.name,
      classArg: drupalSettings.if_then_else.nodes.set_form_error_action.classArg,
      options: [],
      form_fields: [],
      value: [],      
    }
  },
  template: `<div class="fields-container">
    <div class="entity-select">
      <label class="typo__label">Field</label>
      <multiselect v-model="value" :options="options" @input="fieldValueChanged" label="name" track-by="code" 
      :searchable="false" :close-on-select="true" :show-labels="false" placeholder="Select a field">
      </multiselect>      
    </div>
  </div>`,

  methods: {
    fieldValueChanged(value){
      //Triggered when selecting an field.
      var selectedOptions = [];
      selectedOptions.push({name: value.name, code: value.code});
      
      this.putData('form_fields',selectedOptions);
      editor.trigger('process');
    },    
  },

  mounted(){
    // initialize variable for data
    this.putData('type',drupalSettings.if_then_else.nodes.set_form_error_action.type);
    this.putData('class',drupalSettings.if_then_else.nodes.set_form_error_action.class);
    this.putData('name', drupalSettings.if_then_else.nodes.set_form_error_action.name);
    this.putData('classArg', drupalSettings.if_then_else.nodes.set_form_error_action.classArg);
    
    //setting values of selected fields when rule edit page loads.
    var get_form_fields = this.getData('form_fields');
    if(typeof get_form_fields != 'undefined'){
      this.value = this.getData('form_fields');
    }else{
      this.putData('form_fields',[]);
    }
  },
  created() {
    if(drupalSettings.if_then_else.nodes.set_form_error_action.form_fields){
      //setting list of all fields for a form when rule edit page loads.
      this.options = drupalSettings.if_then_else.nodes.set_form_error_action.form_fields;
    }
  }
};

class SetFormErrorActionControl extends Rete.Control {
  constructor(emitter, key, readonly) {
    super(key);
    this.component = VueSetFormErrorActionControl;
    this.props = { emitter, ikey: key, readonly };
  }
}
