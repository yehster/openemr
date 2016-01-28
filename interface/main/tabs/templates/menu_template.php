<script type="text/html" id="menu-action">
    <span class='menuLabel' data-bind="text:label,click: menuActionClick"></span>
</script>
<script type="text/html" id="menu-header">
    
    <span class="menuSection">
        <b><span class='menuLabel' data-bind="text:label"></span></b>
        <ul class="menuEntries" data-bind="foreach: children">
           <li data-bind="template: {name:header ? 'menu-header' : 'menu-action', data: $data }"></li>
        <ul>
    </span>
</script>
<script type="text/html" id="menu-template">
    <div class='appMenu' data-bind="foreach: menu">
            <span data-bind="template: {name:header ? 'menu-header' : 'menu-action', data: $data }"></span>
    </div>
</script>

