<script type="text/html" id="review-document">
    <div data-bind="foreach: $data">
        <div data-bind="attr: {class:type},template: {name: type, data:$data}"></div>
    </div>
</script>

<script type="text/html" id="phrase">
    <span data-bind="text:value"></span>
    <span data-bind="foreach: children">
        <span data-bind="attr: {class:type},template: {name: type, data:$data}"></span>
    </span>
    
</script>

<script type="text/html" id="quantity">
    <span data-bind="visible: value">
                
        <span data-bind="text:name,visible: showLabel"></span>
        <span data-bind="visible:showLabel" >:</span>
        <span data-bind="text:value"></span>
        <span data-bind="text:units"></span>
     </span>
</script>

<script type="text/html" id="date">
  <span data-bind="text:value"></span>
</script>

<script type="text/html" id="select">
  <span data-bind="text:name,visible: showLabel"></span>    
  <span data-bind="text:value"></span>
</script>

<script type="text/html" id="section">
  <div>
  <span data-bind="text:name"></span>    
    <span data-bind="foreach: children">
        <span data-bind="attr: {class:type},template: {name: type, data:$data}"></span>
    </span>
  </div>
</script>

<script type="text/html" id="freetext">
  <div data-bind="text:value"></span>    
</script>

<script type="text/html" id="option_set">
    <div data-bind="visible: value">
        <span data-bind="text:name"></span>
        <span data-bind="foreach: children">
            <span data-bind="attr: {class:type},template: {name: type, data:$data}"></span>
        </span>    
    </div>
</script>

<script type="text/html" id="grouping">
    <span data-bind="foreach: children">
        <span data-bind="attr: {class:type},template: {name: type, data:$data}"></span>
    </span>
</script>

<script type="text/html" id="choice">
    <span data-bind="visible:value, text:name"></span>
</script>

<script type="text/html" id="text_history">
  <span data-bind="text:value"></span>    
</script>

<script type="text/html" id="text_finding">
  <span data-bind="text:value"></span>    
</script>

<script type="text/html" id="duration">
  <span data-bind="text:value"></span>    
</script>

<script type="text/html" id="side">
  <span data-bind="text:value"></span>    
</script>

<script type="text/html" id="option_select">
  <span data-bind="visible:value">
</span>    
</script>

<script type="text/html" id="text_choice">
    <span data-bind="text:name"></span>
    <span >:</span>
    <span data-bind="text:value"></span>
</script>