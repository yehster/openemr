<?php
    function stamp_pdf($source,$target,$layout,$data)
    {
        $jar_file="/home/yehster/pdfclown.jar";
        $args = array($source,$layout,$target);
        $command="java -jar ".$jar_file." ".implode(" ",$args);
        foreach($data as $key=>$value)
        {
            $command.=" ".escapeshellarg($key."|".$value);
        }
        error_log($command);
        $result=exec($command);
        error_log($result);
    }
?>