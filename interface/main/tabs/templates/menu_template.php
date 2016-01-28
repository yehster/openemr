<script type="text/html" id="menu-action">
    <span data-bind="text:label,click: menuActionClick"></span>
</script>
<script type="text/html" id="menu-header">
    <span>
        <b><span data-bind="text:label"></span></b>
        <span data-bind="foreach: children">
            <span data-bind="template: {name:header ? 'menu-header' : 'menu-action', data: $data }"></span>
        </span>
    </span>
</script>
<script type="text/html" id="menu-template">
    <div data-bind="foreach: menu">
            <span data-bind="template: {name:header ? 'menu-header' : 'menu-action', data: $data }"></span>
    </div>
</script>

