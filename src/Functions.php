<?php

/**
 * ok 20180220
 * sitewide variables
 *
 * all logmsg strings are based on `md5 -s <funcName>`
 *
 **/
//$test_appname = 'MFS Inventory';
//$test_url_prefix = 'localhost:8888';
//$test_web_dir = '/Users/bhawes/Desktop/Framing/Website/';
//$test_site_dir = $test_web_dir . 'MFSInventory/';
$test_log_dir = '/var/opt/MFSInventory/';
$test_session_dir = $test_log_dir . 'SessionLogs/';
$test_config_dir = $test_log_dir . 'Config/ctlConfiguration.php';
date_default_timezone_set('America/Chicago');


/**
 * ok 20180220
 * date/time
 * nick code
 *
 * returns yearmonthdayhourminutesecond
 *
 **/
function getdatetime() {
    date_default_timezone_set('US/Central');
    $currentdate = date('Ymd');
    //nickcode $currenttime = date("F j, Y, g:i:s a T");
    $currenttime = date('His');
    
    return $currentdate . $currenttime;
}

/**
 * ok 20180220
 * logging for site
 * nick code
 *
 * md5 -s <funcName>
 *
 **/
function mfslog($realm, $action, $parameters) {
    global $test_log_dir;
    
    $log_prefix = 'mfslog';
    $log_date = getdatetime();
    if (isset($test_log_dir) &&
        isset($log_prefix)) {
        file_put_contents($test_log_dir . "/" . $log_prefix . ".log",
            "<<<<<< $log_date @@@ [" . $realm . "](" . $action . "):\n" . $parameters . "\n>>>>>>\n\n",
	    FILE_APPEND | LOCK_EX);
    }
}


/**
 * ok 20180220
 * get user role
 *
 * parameters:
 *    uname: name entered by user
 *    ucode: passcode entered by user
 *
 * returns:
 *    did login fail?
 *      yes: userpermission = none, log reason
 *      no: userpermission = query->userpermission for uname, log success
 *
 * logs:
 *    767a03418f27197f7cf2720e27778710
 *    missing info: missing uname or upswd
 *    user not found: query count < 1
 *    user found multiple: query count >1
 *    found: user $uname logged in at timestamp
 *
 **/
function validateUser($uname, $upswd) {
    $validateDBO = $quser = $userpermission = $hupswd = $logmsg = '';
    $resultquser = $row = array();
    $resultcount = 0;
    
     //include($dbconfigdir . "ctlConfiguration.php");
    $mysql_configs = array(
        'dbhost' => 'localhost',
        'dbname' => 'marsh181_frameshop',
        'dbuser' => array(
            'one' => 'TODOset_name',
        ),
        'dbpswd' => array(
            'one' => 'TODOset_pswd',
        )
    );

    if (isset($upswd) &&
        (isset($uname) &&
        preg_match("#^([a-zA-Z]{2,15})$#",$uname))) {
        try {
        //only need select access to check user
            $condsn = 'mysql:dbname=' . $mysql_configs['dbname'];
            $conuser = $mysql_configs['dbuser']['one'];
            $conpswd = $mysql_configs['dbpswd']['one'];
            $validateDBO = new PDO($condsn,$conuser,$conpswd);
            $validateDBO->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            $quser = $validateDBO->prepare("SELECT `fk_muser_rolecode_muserrole`,`userpassword` FROM m_user WHERE `k_username` = :username");
            $quser->execute(array(':username' => $uname));
            $resultquser = $quser->fetchAll();
            foreach ($resultquser as $row) {
                $resultcount++;
                $hupswd = $row['userpassword'];
                if (password_verify($upswd,$hupswd)) {
                    $userpermission = $row['fk_muser_rolecode_muserrole'];
                }
            }

            if (isset($userpermission)) {
                switch ($resultcount) {
                case(0):
                    $userpermission = 'none';
                    $logmsg = $uname . "not found";
                    break;
                case(1):
                    $logmsg = $uname . " returned with permission " . $userpermission . " at " . getdatetime();
                    break;
                default:
                    $userpermission = 'none';
                    $logmsg = $uname . "found multiple";
                    break;
                }
            } else {
                $logmsg = $uname . " missing permission ";
            }
        } catch (PDOException $e) {
            $logmsg = $e->getMessage();
            mfslog("ctlFunctions-validateUser", "767a03418f27197f7cf2720e27778710", $logmsg);
            print "Error (146) return to previous window";
            die();
        }
    } else {
        //fail with missing info
        $userpermission = 'missinginfo';
        $logmsg = 'missing info';
    }

    $quser = null;
    $validateDBO = null;
    mfslog("ctlFunctions-validateUser", "767a03418f27197f7cf2720e27778710done", $logmsg);

    return ($userpermission);
}

/**
 * ok 20180220
 * get user actions
 *
 * parameters:
 *    uperm: from validateUser
 *    
 *
 * returns:
 *    user buttons for form
 * 
 *
 * logs:
 *    b40d8ffe845fe4a62add188f4c2e2741
 *    done, values to be returned
 *
 **/
function getActionlist($uname, $uperm) {
    $actionchoices = '';

    if (isset($uname) &&
        is_numeric($uperm)) {

        switch ($uperm) {
        case 1:
            $actionchoices = <<<_END
<input type='hidden' name='user' value='$uname'>
<input type='hidden' name='permission' value='$uperm'>
<button class='large' type='submit' name='GAselected' value='lookupitem'>Lookup Item</button>
<button class='large' type='submit' name='GAselected' value='assignitemjob'>Assign Item to Job</button>
_END;
            break;
         case 2:
            $actionchoices = <<<_END
<input type='hidden' name='user' value='$uname'>
<input type='hidden' name='permission' value='$uperm'>
<button class='large' type='submit' name='GAselected' value='lookupitem'>Lookup Item</button>
<button class='large' type='submit' name='GAselected' value='assignitemjob'>Assign Item to Job</button>
<button class='large' type='submit' name='GAselected' value='restockitem'>Restock Item</button>
_END;
            break;
         case 3:
            $actionchoices = <<<_END
<input type='hidden' name='user' value='$uname'>
<input type='hidden' name='permission' value='$uperm'>
<button class='large' type='submit' name='GAselected' value='lookupitem'>Lookup Item</button>
<button class='large' type='submit' name='GAselected' value='assignitemjob'>Assign Item to Job</button>
<button class='large' type='submit' name='GAselected' value='restockitem'>Restock Item</button>
<button class='large' type='submit' name='GAselected' value='additem'>Add New Item</button>
<button class='large' type='submit' name='GAselected' value='clearjobs'>Clear Working</button>
<button class='large' type='submit' name='GAselected' value='addinvoice'>Add Invoice</button>
_END;
            break;
         case 4:
            $actionchoices = <<<_END
<input type='hidden' name='user' value='$uname'>
<input type='hidden' name='permission' value='$uperm'>
<button class='large' type='submit' name='GAselected' value='lookupitem'>Lookup Item</button>
<button class='large' type='submit' name='GAselected' value='assignitemjob'>Assign Item to Job</button>
<button class='large' type='submit' name='GAselected' value='restockitem'>Restock Item</button>
<button class='large' type='submit' name='GAselected' value='additem'>Add New Item</button>
<button class='large' type='submit' name='GAselected' value='clearjobs'>Clear Working</button>
<button class='large' type='submit' name='GAselected' value='addinvoice'>Add Invoice</button>
<button class='large' type='submit' name='GAselected' value='logproblem'>Log a Problem</button>
_END;
            break;
         default:
            $actionchoices = <<<_END
<input type='hidden' name='user' value='$uname'>
<input type='hidden' name='permission' value='$uperm'>
<button class='large' type='submit' name='GAselected' value='lookupitem'>Lookup Item</button>
_END;
            break;
        }
    }

    //$logmsg = "User permission: " . $uperm " with " . $actionchoices;
    //mfslog("ctlFunctions-getActions", "b40d8ffe845fe4a62add188f4c2e2741", $logmsg);
    return ($actionchoices);
}

/**
 * ok 20180220
 * lookup item
 *
 * parameters:
 *    
 *    
 *
 * returns:
 *   resultqitem array of items with field order:
 *   id_instanceitem_physical
 *   location
 *   dimensionlength
 *   dimensionwidth
 *   dateentered
 * 
 *
 * logs:
 *    27cf43e948b25803b38d4559003e2c6c
 *
 **/
function lookupItem($inumber) {
    $findItemDBO = $qitem = $logmsg = '';
    $resultqitem = $row = array();
    $resultcount = 0;

     //include($dbconfigdir . "ctlConfiguration.php");
    $mysql_configs = array(
        'dbhost' => 'localhost',
        'dbname' => 'marsh181_frameshop',
        'dbuser' => array(
            'one' => 'TODOset_name',
        ),
        'dbpswd' => array(
            'one' => 'TODOset_pswd',
        )
    );

    if (isset($inumber)) {
        try {
        //only need select access to lookup item
            $condsn = 'mysql:dbname=' . $mysql_configs['dbname'];
            $conuser = $mysql_configs['dbuser']['one'];
            $conpswd = $mysql_configs['dbpswd']['one'];

            $findItemDBO = new PDO($condsn,$conuser,$conpswd);
            $findItemDBO->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            $qitem = $findItemDBO->prepare("SELECT `id_instanceitem_physical`, `location`,
                `dimensionlength`, `dimensionwidth`, `dateentered`
                FROM `ii_physical` 
                WHERE `fk_iiphys_itemnumber_mitem` = :itemnumber");
            $qitem->execute(array(':itemnumber' => $inumber));
            $resultqitem = $qitem->fetchAll();

        } catch (PDOException $e) {
            $logmsg = $e->getMessage();
            mfslog("ctlFunctions-lookupItem", "27cf43e948b25803b38d4559003e2c6c", $logmsg);
            print "Error (308) return to previous window";
            die();
        }

    } else {
        //fail with missing info
        $logmsg = 'missing info';
    }
    $qitem = null;
    $findItemDBO = null;
    //mfslog("ctlFunctions-lookupItem", "27cf43e948b25803b38d4559003e2c6cdone", $logmsg);
    //echo $inumber;
   // print_r($resultqitem);
    return ($resultqitem);
}

/**
 * ok 20180220
 * populate LookupItem results table with array of dataset
 *
 * parameters:
 *    resultset: array of rows to display
 *    
 *
 * returns:
 *   resulttable: string with results in results table format
 * 
 *
 * logs:
 *    52e20c57c35a392e1e131013b9254de3
 *
 * pass flag for option to select row
 **/
function populateLookupItemResulttable($resultset) {
    $resulttable = $itemcount = $item = $row = '';
    $loopitemID = $looplocation = $looplength = $loopwidth = $loopentered = '';

    if (isset($resultset) &&
        is_array($resultset)) {

        $resulttable = <<<_END
<table class = "results">
  <tr class = "results">
    <th class = "results">Location</th>
    <th class = "results">Length</th>
    <th class = "results">Width</th>
    <th class = "results">DateEntered</th>
  </tr>
_END;

        $itemcount = 0;
        foreach ($resultset as $item) {
            $loopitemID = $item['id_instanceitem_physical'];
            $looplocation = $item['location'];
            $looplength = $item['dimensionlength'];
            $loopwidth = $item['dimensionwidth'];
            $loopentered = $item['dateentered'];
            $row = <<<_END
  <tr class = "results">
    <td class = "results">$looplocation</td>
    <td class = "results">$looplength</td>
    <td class = "results">$loopwidth</td>
    <td class = "results">$loopentered</td>
    <td class = "results"><input type="radio" value="$loopitemID" name="physicalitemid"></td>
  </tr>
_END;

        $resulttable .= $row;
        $itemcount++;
        } //end foreach resultset

        $resulttable .= "</table><br>";

    } else {

        $resulttable = <<<_END
<table class = "results">
  <tr class = "results">
    <th class = "results">Location</th>
    <th class = "results">Length</th>
    <th class = "results">Width</th>
    <th class = "results">DateEntered</th>
  </tr>
</table><br>
_END;

    } //end if resultsetisset

    //mfslog("ctlFunctions-populateLookupItemResulttable", "52e20c57c35a392e1e131013b9254de3", $logmsg);

    return($resulttable);

} //end populate table


/**
 * ok  20180316
 * assign physical item to workorder
 *
 * parameters:
 *    itemphysicalid: id for item instance in ii_physical, from lookupItem()
 *    itemnumber: masteritem number, from lookupItem()
 * 
 * variables entered by user:
 *    workordernumber
 * 
 * code generated
 *    username
 *    date time date("Y-m-d h:i:sa")
 *
 * returns:
 *  success if query succeeds
 *  fail if not
 *
 * logs:
 *    a3aa768b40482d33a8d5e3e9f624153f
 * 
 **/
function assignItemToJob($itemphysicalid, $itemnumber, $workorder, $username) {

    $assignItemDBO = $qitem = $logmsg = '';
    //$resultqitem = $row = array();
    //$resultcount = 0;

    //include($dbconfigdir . "ctlConfiguration.php");


     //include($dbconfigdir . "ctlConfiguration.php");
    $mysql_configs = array(
        'dbhost' => 'localhost',
        'dbname' => 'marsh181_frameshop',
        'dbuser' => array(
            'three' => 'TODOset_name',
        ),
        'dbpswd' => array(
            'three' => 'TODOset_pswd',
        )
    );

    if (isset($itemphysicalid) &&
        isset($itemnumber) &&
        isset($workorder) &&
        isset($username)) {
        try {
            $condsn = 'mysql:dbname=' . $mysql_configs['dbname'];
            $conuser = $mysql_configs['dbuser']['three'];
            $conpswd = $mysql_configs['dbpswd']['three'];
            
            $assignItemDBO = new PDO($condsn,$conuser,$conpswd);
            $assignItemDBO->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            $qitem = $assignItemDBO->prepare("INSERT INTO ii_working 
                (`fk_iiwork_itemnumber_mitem`,`job_number`,`fk_iiwork_enteredby_muser`,`fk_iiwork_id_iiphys`,`dateentered`)
                VALUES (:itemnumber,:jobnumber,:enteredby,:idphyscial,NOW())");
            $qitem->execute(array(':itemnumber' => $itemnumber,
                                  ':jobnumber' => $workorder,
                                  ':enteredby' => $username,
                                  ':idphyscial' => $itemphysicalid));

            $returnvalue = 'success';
        } catch (PDOException $e) {
            $logmsg = $e->getMessage();
            mfslog("ctlFunctions-assignItemToJob", "a3aa768b40482d33a8d5e3e9f624153f", $logmsg);
            print "Error (479) return to previous window";
            die();
        } // end try sql

    } else {
        //fail with missing info
        $logmsg = 'missing assign workorder info';
        $returnvalue = 'fail';
    } // end if variable set

    $qitem = null;
    $assignItemDBO = null;
    //mfslog("ctlFunctions-assignItemToJob", "a3aa768b40482d33a8d5e3e9f624153fdone", $logmsg);
    //echo $inumber;
    //print_r($resultqitem);
    return($returnvalue);

} //end assign item

/**  
 * ok  20180316
 * move physical item location
 *
 * parameters:
 *    itemphysicalid: id for item instance in ii_physical, from lookupItem()
 *    location: physical location
 * 
 * variables entered by user:
 *    
 * 
 * code generated variables
 *    username
 *    date time date("Y-m-d h:i:sa")
 *
 * returns:
 *  returnvalue = success if sql worked
 *  returnvalue = fail if missing info
 * 
 *
 * logs:
 *    c14e15326f3745d0a1616a137ad12e94
 * 
 **/
function moveItem($itemphysicalid,$moveto) {

    $moveItemDBO = $qitem = $logmsg = '';
    //$resultqitem = $row = array();
    //$resultcount = 0;
    $returnvalue = '';

     //include($dbconfigdir . "ctlConfiguration.php");
    $mysql_configs = array(
        'dbhost' => 'localhost',
        'dbname' => 'marsh181_frameshop',
        'dbuser' => array(
            'two' => 'TODOset_name',
        ),
        'dbpswd' => array(
            'two' => 'TODset_pswd',
        )
    );

    if (isset($itemphysicalid) &&
        isset($moveto)) {
        try {
            $condsn = 'mysql:dbname=' . $mysql_configs['dbname'];
            $conuser = $mysql_configs['dbuser']['two'];
            $conpswd = $mysql_configs['dbpswd']['two'];
            
            $moveItemDBO = new PDO($condsn,$conuser,$conpswd);
            $moveItemDBO->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            $qitem = $moveItemDBO->prepare("UPDATE ii_physical 
                SET `location`=:newlocation
                WHERE `id_instanceitem_physical`=:phyid");
            $qitem->execute(array(':newlocation' => $moveto,':phyid' => $itemphysicalid));
            $returnvalue = 'success';
        } catch (PDOException $e) {
            $logmsg = $e->getMessage();
            mfslog("ctlFunctions-moveItem", "c14e15326f3745d0a1616a137ad12e94", $logmsg);
            print "Error (567) return to previous window";
            die();
        }

    } else {
        //fail with missing info
        $logmsg = 'missing move to info';
        $returnvalue = 'fail';
    } //end if variables set
    $qitem = null;
    $moveItemDBO = null;
    //mfslog("ctlFunctions-moveItem", "c14e15326f3745d0a1616a137ad12e94done", $logmsg);
    //echo $inumber;
   // print_r($resultqitem);

    return($returnvalue);

} //end move item

/* ok 20180316
 * check master item
 *
 * parameters:
 *    inumber item to find in m_item
 *    
 *
 * returns:
 *   itemdescription string if found
 *   null if not
 * 
 *
 * logs:
 *    3e4e57e17d5f52fa3484de02d1db4bb9
 *
 **/
function checkMasterItem($inumber) {
    $findItemDBO = $qitem = $logmsg = $itedes = '';
    $resultqitem = $row = array();
    $resultcount = 0;

     //include($dbconfigdir . "ctlConfiguration.php");
     //include($dbconfigdir . "ctlConfiguration.php");
    $mysql_configs = array(
        'dbhost' => 'localhost',
        'dbname' => 'marsh181_frameshop',
        'dbuser' => array(
            'one' => 'TODOset_name',
        ),
        'dbpswd' => array(
            'one' => 'TODOset_pswd',
        )
    );

    if (isset($inumber)) {
        try {
        //only need select access to lookup item
            //$dsn = 'mysql:dbname=' . $mysql_configs['dbhost'];
            //$dsn = 'mysql:dbname=marsh181_frameshop';
            $condsn = 'mysql:dbname=' . $mysql_configs['dbname'];
            $conuser = $mysql_configs['dbuser']['one'];
            $conpswd = $mysql_configs['dbpswd']['one'];
            
            $findItemDBO = new PDO($condsn,$conuser,$conpswd);
            $findItemDBO->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            $qitem = $findItemDBO->prepare("SELECT `itemdescription`
                FROM `m_item` 
                WHERE `k_itemnumber` = :itemnumber");
            $qitem->execute(array(':itemnumber' => $inumber));
            $resultqitem = $qitem->fetch();

            $itedes = $resultqitem["itemdescription"];

        } catch (PDOException $e) {
            $logmsg = $e->getMessage();
            mfslog("ctlFunctions-checkMasterItem", "3e4e57e17d5f52fa3484de02d1db4bb9", $logmsg);
            print "Error (648) return to previous window";
            die();
        }


    } else {
        //fail with missing info
        $logmsg = 'missing info';
    }
    $qitem = null;
    $findItemDBO = null;
    //mfslog("ctlFunctions-checkMasterItem", "3e4e57e17d5f52fa3484de02d1db4bb9", $logmsg);
    //echo $inumber;
   // print_r($resultqitem);
    return ($itedes);
} // end check master item

/* ok 20180316
 * check master invoice
 *
 * parameters:
 *    invoicenumber to find in m_invoice
 *    
 *
 * returns:
 *   idinv if record found
 *   null if not
 * 
 *
 * logs:
 *    e8b1c9c5b8554d7adc3abc561bfa5d92
 *
 **/
function checkMasterInvoice($invnumber) {
    $findInvoiceDBO = $qinvoice = $logmsg = '';
    $resultqinvoice = $row = array();
    $resultcount = 0;
    $idinv= '';

     //include($dbconfigdir . "ctlConfiguration.php");
     //include($dbconfigdir . "ctlConfiguration.php");
    $mysql_configs = array(
        'dbhost' => 'localhost',
        'dbname' => 'marsh181_frameshop',
        'dbuser' => array(
            'one' => 'TODOset_name',
        ),
        'dbpswd' => array(
            'one' => 'TODOset_pswd',
        )
    );

    if (isset($invnumber)) {
        try {
            $condsn = 'mysql:dbname=' . $mysql_configs['dbname'];
            $conuser = $mysql_configs['dbuser']['one'];
            $conpswd = $mysql_configs['dbpswd']['one'];
            
            $findInvoiceDBO = new PDO($condsn,$conuser,$conpswd);
            $findInvoiceDBO->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            $qinvoice = $findInvoiceDBO->prepare("SELECT `id_master_invoice`
                FROM `m_invoice` 
                WHERE `k_invoicenumber` = :invoicenumber");
            $qinvoice->execute(array(':invoicenumber' => $invnumber));
            $resultqinvoice = $qinvoice->fetch();

            $idinv = $resultqinvoice["id_master_invoice"];

        } catch (PDOException $e) {
            $logmsg = $e->getMessage();
            mfslog("ctlFunctions-checkMasterInvoice", "e8b1c9c5b8554d7adc3abc561bfa5d92", $logmsg);
            print "Error (728) return to previous window";
            die();
        }


    } else {
        //fail with missing info
        $logmsg = 'missing info';
    }
    $qinvoice = null;
    $findInvoiceDBO = null;
    //mfslog("ctlFunctions-checkMasterInvoice", "e8b1c9c5b8554d7adc3abc561bfa5d92", $logmsg);
    //echo $inumber;
    //print_r($idinv);
    return ($idinv);
} // end check master invoice

/** ok 20180316
 * 
 * stock physical item location
 *
 * parameters:
 *    $itemnumber master item number
 *    $itemunit master item unit
 *    $itemlength item length, always longest
 *    $itemwidth item width, maybe 0
 *    $itemlocation item location
 * 
 * variables entered by user:
 *    
 * 
 * code generated variables
 *    username
 *    date time date("Y-m-d h:i:sa")
 *
 * returns:
 *  returnvalue = success if sql worked
 *  returnvalue = fail if missing info
 * 
 *
 * logs:
 *    42735962c6fa2edb66b433e5687bc28a
 * 
 **/
function stockItem($itemnumber,$itemunit,$itemlength,$itemwidth,$itemlocation) {

    $stockItemDBO = $qitem = $logmsg = '';
    //$resultqitem = $row = array();
    //$resultcount = 0;
    $returnvalue = '';

     //include($dbconfigdir . "ctlConfiguration.php");
    $mysql_configs = array(
        'dbhost' => 'localhost',
        'dbname' => 'marsh181_frameshop',
        'dbuser' => array(
            'three' => 'TODOset_name',
        ),
        'dbpswd' => array(
            'three' => 'TODOset_pswd',
        )
    );

    if (isset($itemnumber) &&
        isset($itemunit) &&
        isset($itemlength) &&
        isset($itemwidth) &&
        isset($itemlocation)) {
        try {
        //need select,update,insert access to stock item
            $condsn = 'mysql:dbname=' . $mysql_configs['dbname'];
            $conuser = $mysql_configs['dbuser']['three'];
            $conpswd = $mysql_configs['dbpswd']['three'];
            
            $stockItemDBO = new PDO($condsn,$conuser,$conpswd);
            $stockItemDBO->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            $qitem = $stockItemDBO->prepare("INSERT INTO `ii_physical` 
                (`fk_iiphys_itemnumber_mitem`, `fk_iiphys_unit_mitemunit`, 
                 `dimensionlength`, `dimensionwidth`, `location`, 
                 `fk_iiphys_enteredby_muser`, `dateentered`) 
                VALUES 
                (:inumber,:iunit,:ilength,:iwidth,:ilocation,:enteredby,NOW())");
            $qitem->execute(array(':inumber' => $itemnumber,
                            ':iunit' => $itemunit,
                            ':ilength' => $itemlength,
                            ':iwidth' => $itemwidth,
                            ':ilocation' => $itemlocation,
                            ':enteredby' => 'CoreyB'));
            $returnvalue = 'success';
        } catch (PDOException $e) {
            $logmsg = $e->getMessage();
            mfslog("ctlFunctions-stockItem", "42735962c6fa2edb66b433e5687bc28a", $logmsg);
            print "Error (828) return to previous window";
            die();
        }

    } else {
        //fail with missing info
        $logmsg = 'missing stock info';
        $returnvalue = 'fail';
    } //end if variables set
    $qitem = null;
    $stockItemDBO = null;
    //mfslog("ctlFunctions-stockItem", "42735962c6fa2edb66b433e5687bc28adone", $logmsg);
    //echo $inumber;
   // print_r($resultqitem);

    return($returnvalue);

} //end stock item

/** ok 20180316
 * 
 * add cog item
 *
 * parameters:
 *    $itemnumber master item number
 *    $itemunit master item unit
 *    $invoicenumber master invoice number 
 *    $priceperunit unit price on invoice
 *    $purchasequantity quantity purchased
 * 
 * variables entered by user:
 *    
 * 
 * code generated variables
 *    username
 *    date time date("Y-m-d h:i:sa")
 *
 * returns:
 *  returnvalue = success if sql worked
 *  returnvalue = fail if missing info
 * 
 *
 * logs:
 *    ffd2e51edad2edf4236a1ce9c652f230
 * 
 **/
function addCogItem($itemnumber,$itemunit,$invoicenumber,$priceperunit,$purchasequantity) {

    $addCogItemDBO = $qitem = $logmsg = '';
    //$resultqitem = $row = array();
    //$resultcount = 0;
    $returnvalue = '';

     //include($dbconfigdir . "ctlConfiguration.php");
    $mysql_configs = array(
        'dbhost' => 'localhost',
        'dbname' => 'marsh181_frameshop',
        'dbuser' => array(
            'three' => 'TODOset_name',
        ),
        'dbpswd' => array(
            'three' => 'TODOset_pswd',
        )
    );

    if (isset($itemnumber) &&
        isset($itemunit) &&
        isset($invoicenumber) &&
        isset($priceperunit) &&
        isset($purchasequantity)) {
        try {
            $condsn = 'mysql:dbname=' . $mysql_configs['dbname'];
            $conuser = $mysql_configs['dbuser']['three'];
            $conpswd = $mysql_configs['dbpswd']['three'];
            
            $addCogItemDBO = new PDO($condsn,$conuser,$conpswd);
            $addCogItemDBO->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            $qitem = $addCogItemDBO->prepare("INSERT INTO `ii_costofgood` 
                (`fk_iicost_itemnumber_mitem`, `fk_iicost_unit_mitemunit`, 
                 `fk_iicost_invoicenumber_minv`, `priceperunit`, `purchasequantity`) 
                VALUES 
                (:inumber,:iunit,:iinvoice,:iprice,:iquantity)");
            $qitem->execute(array(':inumber' => $itemnumber,
                            ':iunit' => $itemunit,
                            ':iinvoice' => $invoicenumber,
                            ':iprice' => $priceperunit,
                            ':iquantity' => $purchasequantity));
            $returnvalue = 'success';
        } catch (PDOException $e) {
            $logmsg = $e->getMessage();
            mfslog("ctlFunctions-addCogItem", "ffd2e51edad2edf4236a1ce9c652f230", $logmsg);
            print "Error (928) return to previous window";
            die();
        }

    } else {
        //fail with missing info
        $logmsg = 'missing cog info';
        $returnvalue = 'fail';
    } //end if variables set
    $qitem = null;
    $addCogItemDBO = null;
    //mfslog("ctlFunctions-addCogItem", "ffd2e51edad2edf4236a1ce9c652f230done", $logmsg);
    //echo $inumber;
   // print_r($resultqitem);

    return($returnvalue);

} //end add cog item

/**
 * ok  20180316
 * add invoice
 *
 * parameters:
 *    invoicenumber: unique invoice id
 *    invoicedate: date of invoice
 *    companycode: must be in m_companycode
 *    enterby: must be in m_user
 * 
 * variables entered by user:
 *    
 * 
 * code generated variables
 *
 * returns:
 *  returnvalue = success if sql worked
 *  returnvalue = fail if missing info
 * 
 *
 * logs:
 *    580bff2b467c030eef8570fa62cd408f
 * 
 **/
function addInvoice($invoicenumber,$invoicedate,$companycode,$enteredby) {
    $addInvoiceDBO = $qinvoice = $logmsg = '';
    //$resultqitem = $row = array();
    //$resultcount = 0;
    $returnvalue = '';

     //include($dbconfigdir . "ctlConfiguration.php");
    $mysql_configs = array(
        'dbhost' => 'localhost',
        'dbname' => 'marsh181_frameshop',
        'dbuser' => array(
            'three' => 'TODOset_name',
        ),
        'dbpswd' => array(
            'three' => 'TODOset_pswd',
        )
    );

    if (isset($invoicenumber) &&
        isset($invoicedate) &&
        isset($companycode) &&
        isset($enteredby)) {

        try {
        //need insert,update,select access to lookup item
            $condsn = 'mysql:dbname=' . $mysql_configs['dbname'];
            $conuser = $mysql_configs['dbuser']['three'];
            $conpswd = $mysql_configs['dbpswd']['three'];
            
            $addInvoiceDBO = new PDO($condsn,$conuser,$conpswd);
            $addInvoiceDBO->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            $qinvoice = $addInvoiceDBO->prepare("INSERT INTO `m_invoice`
                    (`k_invoicenumber`,`invoicedate`,`fk_minvo_companycode_mcomp`,`fk_minvo_enteredby_muser`)
                    VALUES 
                    (:invnum,:invdat,:comcod,:entby)");

            $qinvoice->execute(array(':invnum' => $invoicenumber,
                            ':invdat' => $invoicedate,
                            ':comcod' => $companycode,
                            ':entby' => $enteredby));
           //$qinvoice->execute(array($new_invnum,$new_invdat,$new_comcod,$new_entby));
        
           $returnvalue = "success";

           } catch (PDOException $e) {
                $logmsg = $e->getMessage();
                mfslog("Invoices->addInvoice", "580bff2b467c030eef8570fa62cd408f", $logmsg);
                print "Error (1024) return to previous window";
                die();
            } //end try



    } else {
        //fail with missing info
        $logmsg = 'missing invoice info';
        $returnvalue = 'fail';
    } //end if variables set
    $qinvoice = null;
    $addInvoiceDBO = null;
    //mfslog("ctlFunctions-addCogItem", "ffd2e51edad2edf4236a1ce9c652f230done", $logmsg);
    //echo $inumber;
   // print_r($resultqitem);

    return($returnvalue);

} //end add invoice

/**
 * ok  20180316
 * look up jobs
 *
 * parameters:
 *    jobnumber: in job_number ii_working OR "all"
 *    
 *
 * returns:
 *   resultqjobs array of items by jobs with field order:
 *   job_number
 *   dateentered
 *   fk_iiwork_itemnumber_mitem
 *   fk_iiwork_id_iiphys
 * 
 *
 * logs:
 *    64f79b3b3262c83d57763d867156fdd4
 *
 **/
function lookupJob($jobnumber) {
    $findJobDBO = $qjob = $logmsg = '';
    $resultqjob = $row = array();
    $resultcount = 0;

     //include($dbconfigdir . "ctlConfiguration.php");
    $mysql_configs = array(
        'dbhost' => 'localhost',
        'dbname' => 'marsh181_frameshop',
        'dbuser' => array(
            'one' => 'TODOset_name',
        ),
        'dbpswd' => array(
            'one' => 'TODOset_pswd',
        )
    );

    if (isset($jobnumber)) {
       
        if ($jobnumber == "all") {
            try {
                //only need select access to lookup job
                //$dsn = 'mysql:dbname=' . $mysql_configs['dbhost'];
                //$dsn = 'mysql:dbname=marsh181_frameshop';
                $condsn = 'mysql:dbname=' . $mysql_configs['dbname'];
                $conuser = $mysql_configs['dbuser']['one'];
                $conpswd = $mysql_configs['dbpswd']['one'];
            
                $findJobDBO = new PDO($condsn,$conuser,$conpswd);
                $findJobDBO->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                $qjob = $findJobDBO->prepare("SELECT `job_number`, `dateentered`,
                    `fk_iiwork_itemnumber_mitem`, `fk_iiwork_id_iiphys`
                    FROM `ii_working` ORDER BY `job_number`;");
                $qjob->execute();
                $resultqjob = $qjob->fetchAll();

            } catch (PDOException $e) {
                $logmsg = $e->getMessage();
                mfslog("ctlFunctions-lookupJob", "64f79b3b3262c83d57763d867156fdd4", $logmsg);
                print "Error (1110) return to previous window";
                die();
            } //end try all
        } else {
            try {
                //only need select access to lookup job
                //$dsn = 'mysql:dbname=' . $mysql_configs['dbhost'];
                //$dsn = 'mysql:dbname=marsh181_frameshop';
                $condsn = 'mysql:dbname=' . $mysql_configs['dbname'];
                $conuser = $mysql_configs['dbuser']['one'];
                $conpswd = $mysql_configs['dbpswd']['one'];
            
                $findJobDBO = new PDO($condsn,$conuser,$conpswd);
                $findJobDBO->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                $qjob = $findJobDBO->prepare("SELECT `job_number`, `dateentered`,
                    `fk_iiwork_itemnumber_mitem`, `fk_iiwork_id_iiphys`
                    FROM `ii_working` 
                    WHERE `job_number` = :jobnumber;");
                $qjob->execute(array(':jobnumber' => $jobnumber));
                $resultqjob = $qjob->fetchAll();

            } catch (PDOException $e) {
                $logmsg = $e->getMessage();
                mfslog("ctlFunctions-lookupJob", "64f79b3b3262c83d57763d867156fdd4", $logmsg);
                print "Error (1134) return to previous window";
                die();
            }
        } //end if all jobs

    } else {
        //fail with missing info
        $logmsg = 'missing info';
    } // end if parameters set
    $qjob = null;
    $findJobDBO = null;
    //mfslog("ctlFunctions-lookupJob", "64f79b3b3262c83d57763d867156fdd4done", $logmsg);
    //echo $jobnumber;
    //var_dump($resultqjob);
    return ($resultqjob);
}

/**
 * ok 20180406
 * populate lookup job results table with array of dataset
 *
 * parameters:
 *    resultset: array of rows to display
 *    
 *
 * returns:
 *   resulttable: string with results in results table format
 * 
 *
 * logs:
 *    e3c3c8f5fb172676a651e0a17800d55b
 *
 * pass flag for option to select row
 **/
function populateLookupJobResulttable($resultset) {
    $resulttable = $jobcount = $itemcount = $job = $row = '';
    $loopjobnumber = $loopitemnumber = $loopitemID = $loopentered = '';

    if (isset($resultset) &&
        is_array($resultset)) {

        $resulttable = <<<_END
<table class = "results">
  <tr class = "results">
    <th class = "results">Mark Finished</th>
    <th class = "results">Job Number</th>
    <th class = "results">Date Entered</th>
    <th class = "results">Materials Used</th>
  </tr>
_END;

        $jobcount = 0;
        $itemcount = 0;
        $loopjobnumber = '0';
        $row = '';
        foreach ($resultset as $job) {

            if ($itemcount > 0) {
//1+ been through once
                if ($loopjobnumber == $job['job_number']) {

                    $loopjobnumber = $job['job_number'];
                    $loopitemnumber = $job['fk_iiwork_itemnumber_mitem'];
                    $loopitemID = $job['fk_iiwork_id_iiphys'];
                    $loopentered = $job['dateentered'];

                    $row .= <<<_END
, $loopitemnumber
_END;
                    $itemcount++;
                } else {
                    $loopjobnumber = $job['job_number'];
                    $loopitemnumber = $job['fk_iiwork_itemnumber_mitem'];
                    $loopitemID = $job['fk_iiwork_id_iiphys'];
                    $loopentered = $job['dateentered'];
                    $row = <<<_END
</td></tr>
<tr class = "results">
  <td class = "results"><input type="checkbox" value="$loopjobnumber" name="tofinishjoblist[]"></td>
  <td class = "results">$loopjobnumber</td>
  <td class = "results">$loopentered</td>
  <td class = "results" style="font-size: small;">$loopitemnumber
_END;
                } // end if same job
            } else {
                $loopjobnumber = $job['job_number'];
                $loopitemnumber = $job['fk_iiwork_itemnumber_mitem'];
                $loopitemID = $job['fk_iiwork_id_iiphys'];
                $loopentered = $job['dateentered'];
                $row = <<<_END
<tr class = "results">
  <td class = "results"><input type="checkbox" value="$loopjobnumber" name="tofinishjoblist[]"></td>
  <td class = "results">$loopjobnumber</td>
  <td class = "results">$loopentered</td>
  <td class = "results" style="font-size: small;">$loopitemnumber
_END;
                $itemcount = 1;
            } //end if itemcount

            $resulttable .= $row;
            $row = '';

        } //end foreach resultset

        $resulttable .= "</td></tr></table><br>";

    } else {

        $resulttable = <<<_END
<table class = "results">
  <tr class = "results">
    <th class = "results">Mark Finished</th>
    <th class = "results">Job Number</th>
    <th class = "results">DateEntered</th>
    <th class = "results">Materials Used</th>
  </tr>
</table><br>
_END;

    } //end if resultsetisset

    //mfslog("ctlFunctions-populateLookupJobResulttable", "e3c3c8f5fb172676a651e0a17800d55b", $logmsg);

    return($resulttable);

} //end populate table

/**
 * ok 20180406
 * get itemids for a job
 *
 * parameters:
 *    jobnumber: in job_number ii_working
 *    
 *
 * returns:
 *   resultqjobs array of itemids (fk_iiwork_id_iiphys)
 * 
 *
 * logs:
 *    79e5f187d9ca2ebeec6ae9289ea5a19e
 *
 **/
function getitemidsforjob($finishjobnumber) {
     //include($dbconfigdir . "ctlConfiguration.php");
    $mysql_configs = array(
        'dbhost' => 'localhost',
        'dbname' => 'marsh181_frameshop',
        'dbuser' => array(
            'one' => 'TODOset_name',
        ),
        'dbpswd' => array(
            'one' => 'TODOset_pswd',
        )
    );

    if (isset($finishjobnumber)) {
         try {
            $condsn = 'mysql:dbname=' . $mysql_configs['dbname'];
            $conuser = $mysql_configs['dbuser']['one'];
            $conpswd = $mysql_configs['dbpswd']['one'];
            
            $findJobDBO = new PDO($condsn,$conuser,$conpswd);
            $findJobDBO->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            $qjob = $findJobDBO->prepare("SELECT `fk_iiwork_itemnumber_mitem`, `fk_iiwork_id_iiphys`
                    FROM `ii_working` 
                    WHERE `job_number` = :jobnumber");
            $qjob->execute(array(':jobnumber' => $finishjobnumber));
            $resultqjob = $qjob->fetchAll();

        } catch (PDOException $e) {
            $logmsg = $e->getMessage();
            mfslog("ctlFunctions-getitemidsforjob", "79e5f187d9ca2ebeec6ae9289ea5a19e", $logmsg);
            print "Error (1313) return to previous window";
            die();
        } //end try

    } else {
        //fail with missing info
        $logmsg = 'missing info';
    } // end if parameters set
    $qjob = null;
    $findJobDBO = null;
    //mfslog("ctlFunctions-getitemidsforjob", "79e5f187d9ca2ebeec6ae9289ea5a19edone", $logmsg);
    //var_dump($resultqjob);
    return ($resultqjob);
}

/**
 * ok 20180407
 * delete an item for jobnumber
 *
 * parameters:
 *    jobnumber: job_number ii_working
 *    item number: fk_iiwork_id_iiphys ii_working
 * 
 * variables entered by user:
 * 
 * code generated
 *
 * returns:
 *  success if query succeeds
 *  fail if not
 *
 * logs:
 *    c6dc181e665297e9592c806ee1c30e01
 * 
 **/
function deleteJobItem($jobnumber, $itemid) {

    $deleteJobItemDBO = $qjobitem = $logmsg = '';
    //$resultqjob = $row = array();
    //$resultcount = 0;

    //include($dbconfigdir . "ctlConfiguration.php");
    $mysql_configs = array(
        'dbhost' => 'localhost',
        'dbname' => 'marsh181_frameshop',
        'dbuser' => array(
            'four' => 'TODOset_name'
        ),
        'dbpswd' => array(
            'four' => 'TODOset_pswd'
        )
    );


    if (isset($jobnumber) &&
        isset($itemid)) {
        try {
        //need select,update,insert access to lookup item
            //$dsn = 'mysql:dbname=' . $mysql_configs['dbhost'];
            //$dsn = 'mysql:dbname=marsh181_frameshop';
            $condsn = 'mysql:host=localhost; dbname=' . $mysql_configs['dbname'];
            $conuser = $mysql_configs['dbuser']['four'];
            $conpswd = $mysql_configs['dbpswd']['four'];
            
            $deleteJobItemDBO = new PDO($condsn,$conuser,$conpswd);
            $deleteJobItemDBO->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);


            $qjobitem = $deleteJobItemDBO->prepare("DELETE FROM `ii_working` 
                WHERE `fk_iiwork_id_iiphys` = :itemnumber AND `job_number` = :jobnumber;");

            $qjobitem->execute(array(':itemnumber' => $itemid,
                                     ':jobnumber' => $jobnumber));

            $returnvalue = 'success';
        } catch (PDOException $e) {
            $logmsg = $e->getMessage();
            mfslog("ctlFunctions-deleteJobItem", "c6dc181e665297e9592c806ee1c30e01", $logmsg);
            print "Error (1397) return to previous window";
            die();
        } // end try sql

    } else {
        //fail with missing info
        $logmsg = 'missing delete job item info';
        $returnvalue = 'fail';
    } // end if variable set

    $qjobitem = null;
    $deleteJobItemDBO = null;
    //mfslog("ctlFunctions-deleteJobItem", "c6dc181e665297e9592c806ee1c30e01", $logmsg);

    return($returnvalue);

} //end delete job items

/**
 * ok 20180407
 * delete a physical item from ii_physical
 *
 * parameters:
 *    itemid: item id [id_instanceitem_physical]
 * 
 * variables entered by user:
 * 
 * code generated
 *
 * returns:
 *  success if query succeeds
 *  fail if not
 *
 * logs:
 *    d2e939e7e74fe42e53b82216b7c4f2e9
 * 
 **/
function deletePhysicalItem($itemid) {

    $deletePhysicalItemDBO = $qphysitem = $logmsg = '';
    //$resultqjob = $row = array();
    //$resultcount = 0;

    //include($dbconfigdir . "ctlConfiguration.php");
    $mysql_configs = array(
        'dbhost' => 'localhost',
        'dbname' => 'marsh181_frameshop',
        'dbuser' => array(
            'four' => 'TODOset_name'
        ),
        'dbpswd' => array(
            'four' => 'TODOset_pswd'
        )
    );


    if (isset($itemid)) {
        try {
            $condsn = 'mysql:host=localhost; dbname=' . $mysql_configs['dbname'];
            $conuser = $mysql_configs['dbuser']['four'];
            $conpswd = $mysql_configs['dbpswd']['four'];
            
            $deletePhysicalItemDBO = new PDO($condsn,$conuser,$conpswd);
            $deletePhysicalItemDBO->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);


            $qphysitem = $deletePhysicalItemDBO->prepare("DELETE FROM `ii_physical` 
                WHERE `id_instanceitem_physical` = (:itemid);");

            $qphysitem->execute(array(':itemid' => $itemid));

            $returnvalue = 'success';
        } catch (PDOException $e) {
            $logmsg = $e->getMessage();
            mfslog("ctlFunctions-deletePhysicalItem", "d2e939e7e74fe42e53b82216b7c4f2e9", $logmsg);
            print "Error (1481) return to previous window";
            die();
        } // end try sql

    } else {
        //fail with missing info
        $logmsg = 'missing delete physical item info';
        $returnvalue = 'fail';
    } // end if variable set

    $qphysitem = null;
    $deletePhysicalItemDBO = null;
    //mfslog("ctlFunctions-deletePhysicalItem", "d2e939e7e74fe42e53b82216b7c4f2e9", $logmsg);
    return($returnvalue);

} //end physical item job
