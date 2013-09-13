<?php
// Copyright (C) 2009 Aron Racho <aron@mi-squared.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
include_once("../../globals.php");
include_once("$srcdir/api.inc");

require ("C_FormSnellen.class.php");

$c = new C_FormSnellen();
echo $c->default_action();


?>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery-2.0.3.min.js"></script>
<script type="text/javascript">
    $("#optometry_ref").click( function()
        {
            var notes=$("textarea[name='notes']");
            var phrase="Referred to optometry.";
            if(notes.val().indexOf(phrase)===-1)
                {
                    var text=notes.val()+phrase;
                    notes.val(text);    
                }
        });
</script>