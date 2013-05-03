<script type="text/html" id="menu-base">
        <ul id='main_menu' data-bind="foreach: menu">
            <li>
                <span data-bind="text:description, attr:{'class':$data.type}"></span>
                <ul data-bind="foreach: children">
                    <li>
                        <span  data-bind="text:description, attr:{'class':$data.type}"></span>
                    </li>
                    <ul data-bind="foreach: children">
                        <li>
                            <span  data-bind="text:description"></span>
                                <ul data-bind="foreach: children">
                                    <li>
                                        <span  data-bind="text:description"></span>
                                    </li>
                                </ul>
                        </li>
                    </ul>
                </ul>
            </li>
        </ul>
</script>