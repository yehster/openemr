<script type="text/html" id="birth-weight-display">
    <div class='section-header-dynamic'>
        <table>
            <tbody>
                <tr>
                    <td>
                        <a class="css_button_small birth-button" data-bind="click:edit_birth_weight">
                            <span class='birth_button' data-bind='visible: !edit()'>Edit</span>
                            <span class='birth_button' data-bind='visible: edit()'>Hide</span>    
                        </a>
                    </td>
                    <td>
                        <b class='small'>Birth Weight</b>
                    </td>
                    <td>
                        <span class='small' data-bind="text:display"></span>
                    </td>
               </tr>
            </tbody>
        </table>
    </div>
    <div data-bind='visible: edit'>
        <div>
            <input class='weight_input' id='birth_weight_pounds' data-bind="value:pounds,hasFocus: edit_pounds,event:{keyup:handle_keyup}"/>lbs<input id='birth_weight_ounces' class='weight_input'  data-bind="value:ounces,event:{keyup:handle_keyup}"/>oz
        </div>
        <div>
            <input class='weight_input'  type='text' data-bind="value:weight,event:{keyup:handle_keyup}"/>kg
        </div>
    </div>
</script>