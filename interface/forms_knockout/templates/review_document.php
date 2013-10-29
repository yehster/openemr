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
    <span data-bind="text:name,visible: showLabel"></span>
    <span data-bind="visible:showLabel" >:</span>
    <span data-bind="text:value"></span>
    <span data-bind="text:units"></span>
</script>

<script type="text/html" id="date">
  <span data-bind="text:value"></span>
</script>

<script type="text/html" id="select">
  <span data-bind="text:name,visible: showLabel"></span>    
  <span data-bind="text:value"></span>
</script>