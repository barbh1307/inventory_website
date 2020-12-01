<?php

/**
 * ok 20180220
 *
 * lookup item test page, no login
 *
 **/


/**
 * formaction: 
 * formmessage: instructions for form
 * formcontent: 
 *
 **/
$currentworkingdirectory = getcwd();
$pagetitle = 'Lookup Item';
$founditems = array();
$numofite = $itenum = $doit = '';
$mitemnumber = $instanceid = $outcome = $worord = '';

require_once('Functions.php');

/**
 * Lookup Item
 * http://localhost:8888/MFSInventory/LookupItem.php
 */
if (isset($_POST['LIselected'])) {
    $doit = $_POST['LIselected'];
} else {
    $doit = "lookupitem";
}
switch ($doit) {

    case "lookupitem":
        if (isset($_POST['itemnumber'])) {
        
            $itenum = $_POST['itemnumber'];

            $founditems = lookupItem($itenum);

            if (count($founditems) > 0) {
                $numofite = count($founditems);
                $formcontent = populateLookupItemResulttable($founditems);

                $formaction = <<<_END
action='LookupItem.php' method='post'
_END;
                $formmessage = <<<_END
<h2>Found $numofite instances of item $itenum</h2>
_END;
                $formcontent .= <<<_END
<input hidden name='previousitemnumber' value=$itenum>
<button class='large' type='submit' name='LIselected' value='assignitem'>Assign Item</button>
<br><label for='itemnumber'>Next Item Number</label>
<input type='text' id='itemnumber' name='itemnumber'>
<br><button class='large' type='submit' name='LIselected' value='lookupitem'>Lookup Item</button>
<button class='large' type='submit' name='LIselected' value='donelookupitem'>Done</button>
_END;
            } else {
                $formcontent = "item not found";
                $formaction = <<<_END
action='LookupItem.php' method='post'
_END;
                $formmessage = <<<_END
<h2>Found 0 instances of item $itenum</h2>
_END;
                $formcontent .= <<<_END
<input hidden name='previousitemnumber' value=$itenum>
<br><label for='itemnumber'>Next Item Number</label>
<input type='text' id='itemnumber' name='itemnumber'>
<br><button class='large' type='submit' name='LIselected' value='lookupitem'>Lookup Item</button>
<button class='large' type='submit' name='LIselected' value='donelookupitem'>Done</button>
_END;
            } //end if count items found

        } else {
        $formaction = <<<_END
action='LookupItem.php' method='post'
_END;
        $formmessage = <<<_END
<h2>Lookup Item</h2>
_END;
        $formcontent = <<<_END
<label for='itemnumber'>Item Number</label>
<input type='text' id='itemnumber' name='itemnumber'>
<button class='large' type='submit' name='LIselected' value='lookupitem'>Lookup Item</button>
<button class='large' type='submit' name='LIselected' value='donelookupitem'>Done</button>
_END;
        } //end if variables set
        break;

    case "assignitem":
        /*
         * $_POST['previousitemnumber'] masteritem number
         * $_POST['physicalitemid'] physical id for instance of item
         *  
         *
         */

        if (isset($_POST['previousitemnumber']) && 
            is_numeric($_POST['physicalitemid'])) {
            $mitemnumber = $_POST['previousitemnumber'];
            $instanceid = $_POST['physicalitemid'];
        $formaction = <<<_END
action='LookupItem.php' method='post'
_END;
        $formmessage = <<<_END
<h2>Enter work order for instance $instanceid of item $mitemnumber:</h2>
_END;
        $formcontent = <<<_END
<input hidden name='assigneditem' value=$mitemnumber>
<input hidden name='assignedinstid' value=$instanceid>
<label for='workorder'>Work Order</label>
<input type='text' id='workorder' name='workorder'>
<button class='large' type='submit' name='LIselected' value='runassignqueries'>Submit Assignment</button>
<button class='large' type='submit' name='LIselected' value='lookupitem'>Cancel</button>
_END;
        } else {
        $formaction = <<<_END
action='LookupItem.php' method='post'
_END;
        $formmessage = <<<_END
Missing info to assign job.
_END;
        $formcontent = <<<_END
<label for='itemnumber'>Item Number</label>
<input type='text' id='itemnumber' name='itemnumber'>
<button class='large' type='submit' name='LIselected' value='lookupitem'>Lookup Item</button>
<button class='large' type='submit' name='LIselected' value='donelookupitem'>Done</button>
_END;

        }

        break;
    case "runassignqueries":
        /*
         * $_POST['assigneditem'] masteritem number
         * $_POST['assignedinstid'] physical id for instance of item
         * $_POST['workorder'] work order for instance of item 
         *
         */

        if (isset($_POST['assigneditem'])) {
            $mitemnumber = $_POST['assigneditem'];
        } else {
            $logmsg = "missing assigneditmenumber"; 
        }
        if (isset($_POST['assignedinstid'])) {
            $instanceid = $_POST['assignedinstid'];
        } else {
            $logmsg = "missing instanceid"; 
        }
        if (isset($_POST['workorder'])) {
            $worord = $_POST['workorder'];
        } else {
            $logmsg = "missing instanceid"; 
        }
        $outcome = assignItemToJob($instanceid, $mitemnumber, $worord, 'CoreyB');
        if ($outcome == 'success') {
            $outcome = '';
            $outcome = moveItem($instanceid, 'work');
            if ($outcome == 'success') {
            $formmessage = <<<_END
instance $instanceid of item $mitemnumber successfully assigned to $worord <br>
_END;
            } //end if outcome moveitem
        } else {
            $formmessage = <<<_END
ERROR assigning instance $instanceid of item $mitemnumber to $worord <br>
_END;
        } //end if outcome assignitem

        $formaction = <<<_END
action='LookupItem.php' method='post'
_END;
        $formmessage .= <<<_END
Lookup another item?
_END;
        $formcontent = <<<_END
<label for='itemnumber'>Next Item Number</label>
<input type='text' id='itemnumber' name='itemnumber'>
<button class='large' type='submit' name='LIselected' value='lookupitem'>Lookup Item</button>
<button class='large' type='submit' name='LIselected' value='donelookupitem'>Done</button>
_END;

        break;

    case "donelookupitem":
        unset($itenum);
        unset($founditems);
        unset($mitemnumber);
        unset($instanceid);
        unset($doit);
        unset($formaction);
        unset($formmessage); 
        unset($formcontent);

        $formaction = <<<_END
action='ChangePage.php' method='post'
_END;

        $formmessage = <<<_END
<h2>Done stocking items.</h2>
_END;

        $formcontent = <<<_END
<input hidden id='gotopage' name='gotopage' value='changepage'>
<button class='large' type='submit' name='CPselected'>Choose New Action</button>
_END;
        break;

    default:
        /**
         * 
         * print default lookup item form
         * $formaction, $formmessage, $formcontent must have values
         *
         *ok
         */
        unset($itenum);
        unset($founditems);
        unset($doit);

        if (!isset($formaction)) {
$formaction = <<<_END
action='LookupItem.php' method='post'
_END;
}
        if (!isset($formmessage)) {
$formmessage = <<<_END
<h2>Lookup Another Item Fall Thru</h2>
_END;
}
        if (!isset($formcontent)) {
$formcontent = <<<_END
<label for='itemnumber'>Item Number</label>
<input type='text' id='itemnumber' name='itemnumber'>
<button class='large' type='submit' name='LIselected' value='lookupitem'>Lookup Item</button>
<button class='large' type='submit' name='LIselected' value='donelookupitem'>Done</button>
_END;
}
        break;
} //end switch on LIselected



require_once('Header.php');

echo <<<_END
<div class="content">

  <div class="row">
    <div class="column side" style="background-color:#fff;"></div>
    <div class="column middle" style="background-color:#fff;">
      <p class='instructions'>
      $formmessage
      </p>
    </div> <!-- end column middle -->
    <div class="column side" style="background-color:#fff;"></div>
  </div> <!-- end row to hold columns -->

  <div class="row">
    <div class="column side" style="background-color:#fff;"></div>

    <div class="column middle" style="background-color:#fff;">
       <form $formaction>
       $formcontent
       </form> <!-- end welcome form -->
     </div> <!-- end column middle -->

    <div class="column side" style="background-color:#fff;"></div>
  </div> <!-- end row to hold columns -->

</div> <!-- end content -->
_END;

include_once('Footer.php');
