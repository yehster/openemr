<script type="text/html" id="tabs-controls">
    <div class="tabControls" data-bind="with: tabs">
        <!-- ko  foreach: tabsList -->
            <span class="tabSpan">
                <span  data-bind="text: title, click: tabClicked"></span>
                <span class="typcn typcn-refresh" style="font-size: 1.5em;" data-bind="click: tabRefresh"></span>
                <!--ko if:!locked() -->
                    <span class="typcn typcn-lock-open" style="font-size: 1.5em;" data-bind="click: tabLockToggle"></span>
                <!-- /ko -->
                <!--ko if:locked() -->
                    <span class="typcn typcn-lock-closed" style="font-size: 1.5em;" data-bind="click: tabLockToggle"></span>
                <!-- /ko -->

                <!-- ko if:closable-->
                    <span class="typcn typcn-delete" style="font-size: 1.5em;" data-bind="click: tabClose"></span>
                <!-- /ko -->    
            </span>
        <!-- /ko -->
    </div>
</script>
<script type="text/html" id="tabs-frames">
        
        <!-- ko  foreach: tabs.tabsList -->
        <div class="frameDisplay" data-bind="visible:visible">
            <iframe data-bind="location: $data, iframeName: $data.name, ">

            </iframe>
        </div>
        <!-- /ko -->
</script>