<?php

/**ok 20180316
 *
 * cost of good item test page, no login
 *  adding items to ii_costofgood
 * TODO tie itemunit to dropdown list from database 
 **/


/**
 * formaction: 
 * formmessage: instructions for form
 * formcontent: 
 *
 **/
$currentworkingdirectory = getcwd();
$pagetitle = 'Add COG Item';
$doit = $logmsg = '';
$itemtocheck = $itemdescription = '';
$mitemnumber = '';
$iteuni = $itelen = $itewid = $iteloc = '';

require_once('Functions.php');

/**
 * COG Item
 * http://localhost:8888/MFSInventory/AddCogItem.php
 */
if (isset($_POST['ACselected'])) {
    $doit = $_POST['ACselected'];
} else {
    $doit = "addCOGitem";
}
switch ($doit) {

    case "addcogitem":
        if (isset($_POST['itemnumber'])) {

            $itemtocheck = $_POST['itemnumber'];
            $itemdescription = '';
            $itemdescription = checkMasterItem($itemtocheck);

            if (isset($itemdescription)) {
                
                $formaction = <<<_END
action='AddCogItem.php' method='post'
_END;
                $formmessage = <<<_END
<h2>AddCog item $itemtocheck $itemdescription</h2>
_END;
                $formcontent = <<<_END
<input hidden name='cogitemnumber' value=$itemtocheck>
<input hidden name='cogitemdescription' value=$itemdescription>

<br><label for='itemunit'>Item Unit Type</label>
<input type='text' id='itemunit' name='itemunit'>

<br><label for='iteminvoice'>Associated Invoice Number</label>
<input type='text' id='iteminvoice' name='iteminvoice'>

<br><label for='priceperunit'>Price Per Unit</label>
<input type='text' id='priceperunit' name='priceperunit'>

<br><label for='purchasequantity'>Purchase Quantity</label>
<input type='text' id='purchasequantity' name='purchasequantity'>

<br><button class='large' type='submit' name='ACselected' value='runcogqueries'>Add to COG</button>
<button class='large' type='submit' name='ACselected' value='addcogitem'>Cancel</button>
_END;
            } else {
        
                $formaction = <<<_END
action='AddCogItem.php' method='post'
_END;
                $formmessage = <<<_END
item $itemtocheck not found in master item list, please add to master item
_END;
        $formcontent = <<<_END
<label for='itemnumber'>Item Number to Add to COG</label>
<input type='text' id='itemnumber' name='itemnumber'>
<button class='large' type='submit' name='ACselected' value='addcogitem'>Add COG Item</button>
<button class='large' type='submit' name='ACselected' value='doneaddcogitem'>Done</button>
_END;
            }//end if itemdesc string
 
        } else {
        $formaction = <<<_END
action='AddCogItem.php' method='post'
_END;
        $formmessage = <<<_END
<h2>AddCog Item</h2>
_END;
        $formcontent = <<<_END
<label for='itemnumber'>Item Number to Add to COG</label>
<input type='text' id='itemnumber' name='itemnumber'>
<button class='large' type='submit' name='ACselected' value='addcogitem'>Add COG Item</button>
<button class='large' type='submit' name='ACselected' value='doneaddcogitem'>Done</button>
_END;
        } //end if variables set
        break;

    case "runcogqueries":
        /*
         * $_POST['cogitemnumber'] masteritem number
         * $_POST['cogitemdescription'] masteritem description
         * $_POST['itemunit'] unit of item
         * $_POST['iteminvoice'] length of item, always longest
         * $_POST['priceperunit'] width of item, always shortest, maybe 0
         * $_POST['purchasequantity'] location of item
         *
         */
        unset($logmsg);
        unset($invfound);

        if (isset($_POST['cogitemnumber'])) {
            $mitemnumber = $_POST['cogitemnumber'];
        } else {
            $logmsg = "missing cogitemnumber"; 
        }
        if (isset($_POST['itemunit'])) {
            $iteuni = $_POST['itemunit'];
        } else {
            $logmsg = "missing item unit"; 
        }
        if (isset($_POST['iteminvoice'])) {
            $iteinv = $_POST['iteminvoice'];
        } else {
            $logmsg = "missing iteminvoice"; 
        }
        if (isset($_POST['priceperunit'])) {
            $priperuni = $_POST['priceperunit'];
        } else {
            $logmsg = "missing priceperunit";
        } 
        if (isset($_POST['purchasequantity'])) {
            $purqty = $_POST['purchasequantity'];
        } else {
            $logmsg = "missing purchasequantity";
        }
        
        if (!isset($logmsg)) {
            $invfound = checkMasterInvoice($iteinv);
//print_r($invfound);
            if (isset($invfound)) {
                
                $outcome = addCogItem($mitemnumber, $iteuni, $iteinv, $priperuni, $purqty);
                if ($outcome == 'success') {
                    $formmessage = <<<_END
item $mitemnumber from $invnum added to COG <br>
_END;
                } else {
                $formmessage = <<<_END
ERROR adding item $mitemnumber from $invnum to COG <br>
_END;
                } //end if outcome assignitem
            } else {
                $formmessage = <<<_END
missing $iteinv add to master invoice before continuing <br>
_END;

            } //end check master invoice

        } else {
           print $logmsg;
        } // end if logmsg

        $formaction = <<<_END
action='AddCogItem.php' method='post'
_END;
        $formmessage .= <<<_END
Add another COG item?
_END;
        $formcontent = <<<_END
<label for='itemnumber'>Next Item Number</label>
<input type='text' id='itemnumber' name='itemnumber'>
<button class='large' type='submit' name='ACselected' value='addcogitem'>Add COG Item</button>
<button class='large' type='submit' name='ACselected' value='doneaddcogitem'>Done</button>
_END;

        break;

    case "doneaddcogitem":
        unset($doit);
        unset($itemtocheck);
        unset($itemdescription);
        unset($mitemnumber);
        unset($iteuni);
        unset($iteinv);
        unset($priperuni);
        unset($purqty);
        unset($logmsg);

        $formaction = <<<_END
action='ChangePage.php' method='post'
_END;

        $formmessage = <<<_END
<h2>Done adding COG items.</h2>
_END;

        $formcontent = <<<_END
<input hidden id='gotopage' name='gotopage' value='changepage'>
<button class='large' type='submit' name='CPselected'>Choose New Action</button>
_END;

        break;

    default:
        /**
         * 
         * print default add cog item form
         * $formaction, $formmessage, $formcontent must have values
         *
         *ok
         */

        if (!isset($formaction)) {
            $formaction = <<<_END
action='AddCogItem.php' method='post'
_END;
        }
        if (!isset($formmessage)) {
            $formmessage = <<<_END
<h2>Add COG Item Default</h2>
_END;
        }
        if (!isset($formcontent)) {
            $formcontent = <<<_END
<label for='itemnumber'>Item Number</label>
<input type='text' id='itemnumber' name='itemnumber'>
<button class='large' type='submit' name='ACselected' value='addcogitem'>Add COG Item</button>
<button class='large' type='submit' name='ACselected' value='doneaddcogitem'>Done</button>
_END;
        }
        break;
} //end switch on ACselected



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
