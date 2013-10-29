<script type="text/html" id="base-document">
    <div data-bind="foreach: $data">
        <div data-bind="attr: {class:type},template: {name: type, data:$data}"></div>
    </div>
</script>

<script type="text/html" id="section">
    <div class='section'>
        <span class="label" data-bind="text:name"></span>
        <input type="checkbox" data-bind="checked:expanded"></input>
    </div>
    <span data-bind="visible: expanded">
        <!-- ko foreach: children -->
            <div class="child" data-bind="template: {name: type, data: $data}"></div>
        <!-- /ko -->
    </span>
</script>

<script type="text/html" id="freetext">
    <div>
        <textarea data-bind="value:value,valueUpdate: 'keyup'"></textarea>
    </div>
</script>

<script type="text/html" id="option_set">
    <div>
    
        <input type="checkbox" data-bind="checked: value"/>
        <span data-bind="text:name"></span>
        <!-- ko if: sub_components -->
            ...
        <!-- /ko -->
        <!-- ko foreach: children -->
                <span data-bind="visible:$parent.value ,template: {name:type, data:$data}"></span>
        <!-- /ko -->
    </div>
</script>

<script type="text/html" id="option_select">
    <div>
    
        <input type="checkbox" data-bind="checked: value"/>
        <select data-bind="value: selection,options: choices"></select>    
        <!-- ko if: sub_components -->
            ...
        <!-- /ko -->
        <!-- ko foreach: children -->
                <span data-bind="visible:$parent.value ,template: {name:type, data:$data}"></span>
        <!-- /ko -->
    </div>
</script>

<script type="text/html" id="side">
    <select title="location" data-bind="value: value,options: locations"></select>    
</script>

<script type="text/html" id="duration">
    <input class='duration' data-bind="value: value,valueUpdate: 'keyup'" type='text' title='duration'/>
    <select data-bind="value: units,options: unit_choices"></select>    
</script>

<script type="text/html" id="quantity">
    <span data-bind="text:name,visible: showLabel"></span>
    <span data-bind="visible: showLabel">:</span>
    <input class= 'quantity' data-bind="value: value,valueUpdate: 'keyup'" type='text' />
    <select data-bind="value: units,options: unit_choices"></select>
</script>

<script type="text/html" id="choice">
    <input type="checkbox" data-bind="checked: value"/>
    <span data-bind="text: name"></span>
</script>



<script type="text/html" id="grouping">
        <!-- ko foreach: children -->
                <span data-bind="template: {name:type, data:$data}"></span>
        <!-- /ko -->
</script>

<script type="text/html" id="text_history">
    <div class='text_history'>
        <input type="text" data-bind="value: value, attr:{title: name},valueUpdate: 'keyup'"/>
    </div>    
</script>

<script type="text/html" id="text_finding">
    <div class='text_finding'>
        <input type="text" data-bind="value: value,  attr:{title: name},valueUpdate: 'keyup'"/>
    </div>
</script>

<script type="text/html" id="separator">
    <br>
</script>

<script type="text/html" id="select">
    <span data-bind="text:name,visible: showLabel"></span>
    <select data-bind="value: value,options: options"></select>
    <!-- ko foreach: children -->
        <span class="details" data-bind="template: {name: type, data: $data}"></span>
    <!-- /ko -->        
</script>

<script type="text/html" id="phrase">
    <span class="edit-container">
    <span data-bind="text:value, click: edit_phrase"></span>
    <textarea class="phrase-editor" data-bind="value:value, valueUpdate: 'keyup',visible: editing,event:{blur: edit_phrase}"></textarea>
    </span>
    <!-- ko foreach: children -->
        <span data-bind="template: {name: type, data: $data}"></span>
    <!-- /ko -->        
</script>

<script type="text/html" id="date">
    <input class='date' data-bind="value:value,valueUpdate: 'keyup'" type="text"/>
</script>