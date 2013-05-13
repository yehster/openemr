
<script type="text/html" id="menu-entry">
                <span data-bind="text:description, attr:{'class':$data.type},click: menu_click"></span>
                <!-- ko if:$data.children -->
                    <ul data-bind="foreach:$data.children">
                        <li data-bind="template:'menu-entry'"></li>
                    </ul>
                <!-- /ko -->
</script>

