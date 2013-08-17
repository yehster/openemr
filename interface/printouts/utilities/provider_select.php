<?php     $provider=isset($_REQUEST['provider']) ? $_REQUEST['provider'] : ""; ?>
<select name='provider'>
        <?php
            $sqlProviders="SELECT username,fname,lname from users where authorized=1 ORDER BY id ASC";
            $providers = sqlStatement($sqlProviders,array());
            $found=false;
            $first=true;
            while($row = sqlFetchArray($providers))
            {
                ?>
                <option value='<?php echo $row['username'];?>'
                <?php
                if($first)
                {
                    $first_provider_name=$row['fname']." ".$row['lname'];
                    $first=false;
                }
                if((($provider=="")  && $row['username']==$_SESSION['authUser'] ) || $provider==$row['username'])
                {
                    $found=true;
                    $provider_name=$row['fname']." ".$row['lname'];
                    echo " selected";
                }
                ?>
                ><?php echo $row['fname'].'&nbsp;'.$row['lname']?></option>
                <?php
            }
            if(!$found)
            {
                $provider_name=$first_provider_name;
            }
        ?>
</select>