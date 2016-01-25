<script type="text/html" id="tabs-controls">
    <div class="tabControls" data-bind="with: tabs">
        <!-- ko  foreach: tabsList -->
            <span data-bind="text: title"></span>
        <!-- /ko -->
    </div>
</script>
<script type="text/html" id="tabs-frames">
        
        <!-- ko  foreach: tabs.tabsList -->
        <div class="frameDisplay" data-bind="visible:visible">
            <iframe data-bind="location: $data, iframeTitle: $data.title()">

            </iframe>
        </div>
        <!-- /ko -->
</script>