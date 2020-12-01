<?php

/**
 *
 * stock item test page, no login
 *  adding items to ii_physical
 * TODO tie itemunit to dropdown list from database 
 **/


/**
 * formaction: 
 * formmessage: instructions for form
 * formcontent: 
 *
 **/
$currentworkingdirectory = getcwd();
$pagetitle = 'Stock Item';
$doit = $logmsg = '';
$itemtocheck = $itemdescription = '';
$mitemnumber = '';
$iteuni = $itelen = $itewid = $iteloc = '';

require_once('Functions.php');

/**
 * Stock Item
 * http://localhost:8888/MFSInventory/StockItem.php
 */
if (isset($_POST['SIselected'])) {
    $doit = $_POST['SIselected'];
} else {
    $doit = "stockitem";
}
switch ($doit) {
    case "stockitem":
        if (isset($_POST['itemnumber'])) {

            $itemtocheck = $_POST['itemnumber'];
            $itemdescription = '';
            $itemdescription = checkMasterItem($itemtocheck);

            if (isset($itemdescription)) {

                $formaction = <<<_END
action='StockItem.php' method='post'
_END;
                $formmessage = <<<_END
<h2>Stock item $itemtocheck $itemdescription</h2>
_END;
                $formcontent = <<<_END
<input hidden name='stockitemnumber' value=$itemtocheck>
<input hidden name='stockitemdescription' value=$itemdescription>
<br><label for='itemunit'>Item Unit Type</label>
<input type='text' id='itemunit' name='itemunit'>
<br><label for='itemlength'>Item Length (always longest)</label>
<input type='text' id='itemlength' name='itemlength'>
<br><label for='itemwidth'>Item Width (always shortest, may be 0)</label>
<input type='text' id='itemwidth' name='itemwidth'>
<br><label for='itemlocation'>Item Location</label>
<input type='text' id='itemlocation' name='itemlocation'>
<br><button class='large' type='submit' name='SIselected' value='runstockqueries'>Add to Stock</button>
<button class='large' type='submit' name='SIselected' value='stockitem'>Cancel</button>
_END;
            } else {
        
                $formaction = <<<_END
action='StockItem.php' method='post'
_END;
                $formmessage = <<<_END
item $itemtocheck not found in master item list, please update master item before stocking item
_END;
        $formcontent = <<<_END
<label for='itemnumber'>Item Number to Stock</label>
<input type='text' id='itemnumber' name='itemnumber'>
<button class='large' type='submit' name='SIselected' value='stockitem'>Stock Item</button>
<button class='large' type='submit' name='SIselected' value='donestockitem'>Done</button>
_END;
            }//end if itemdesc string
 
        } else {
        $formaction = <<<_END
action='StockItem.php' method='post'
_END;
        $formmessage = <<<_END
<h2>Stock Item</h2>
_END;
        $formcontent = <<<_END
<label for='itemnumber'>Item Number to Stock</label>
<input type='text' id='itemnumber' name='itemnumber'>
<button class='large' type='submit' name='SIselected' value='stockitem'>Stock Item</button>
<button class='large' type='submit' name='SIselected' value='donestockitem'>Done</button>
_END;
        } //end if variables set
        break;

    case "runstockqueries":
        /*
         * $_POST['stockitemnumber'] masteritem number
         * $_POST['stockitemdescription'] masteritem description
         * $_POST['itemunit'] unit of item
         * $_POST['itemlength'] length of item, always longest
         * $_POST['itemwidth'] width of item, always shortest, maybe 0
         * $_POST['itemlocation'] location of item
         *
         */
        unset($logmsg);

        if (isset($_POST['stockitemnumber'])) {
            $mitemnumber = $_POST['stockitemnumber'];
        } else {
            $logmsg = "missing stockitemnumber"; 
        }
        if (isset($_POST['itemunit'])) {
            $iteuni = $_POST['itemunit'];
        } else {
            $logmsg = "missing item unit"; 
        }
        if (isset($_POST['itemlength'])) {
            $itelen = $_POST['itemlength'];
        } else {
            $logmsg = "missing itemlength"; 
        }
        if (isset($_POST['itemwidth'])) {
            $itewid = $_POST['itemwidth'];
        } else {
            $itewid = 0;
        } 
        if (isset($_POST['itemlocation'])) {
            $iteloc = $_POST['itemlocation'];
        } else {
            $logmsg = "missing item location";
        }
        
        if (!isset($logmsg)) {
            $outcome = stockItem($mitemnumber, $iteuni, $itelen, $itewid, $iteloc);
            if ($outcome == 'success') {
                $formmessage = <<<_END
item $mitemnumber ( $itelen x $itewid ) in stock at $iteloc <br>
_END;
            } else {
                $formmessage = <<<_END
ERROR adding item $mitemnumber to stock <br>
_END;
            } //end if outcome assignitem
        } else {
           print $logmsg;
        } // end if logmsg

        $formaction = <<<_END
action='StockItem.php' method='post'
_END;
        $formmessage .= <<<_END
Stock another item?
_END;
        $formcontent = <<<_END
<label for='itemnumber'>Next Item Number</label>
<input type='text' id='itemnumber' name='itemnumber'>
<button class='large' type='submit' name='SIselected' value='stockitem'>Stock Item</button>
<button class='large' type='submit' name='SIselected' value='donestockitem'>Done</button>
_END;

        break;

    case "donestockitem":
        unset($doit);
        unset($itemtocheck);
        unset($itemdescription);
        unset($mitemnumber);
        unset($iteuni);
        unset($itelen);
        unset($itewid);
        unset($iteloc);
        unset($logmsg);

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
         * print default stock item form
         * $formaction, $formmessage, $formcontent must have values
         *
         *ok
         */

        if (!isset($formaction)) {
            $formaction = <<<_END
action='StockItem.php' method='post'
_END;
        }
        if (!isset($formmessage)) {
            $formmessage = <<<_END
<h2>Stock Item Default</h2>
_END;
        }
        if (!isset($formcontent)) {
            $formcontent = <<<_END
<label for='itemnumber'>Item Number</label>
<input type='text' id='itemnumber' name='itemnumber'>
<button class='large' type='submit' name='SIselected' value='stockitem'>Stock Item</button>
<button class='large' type='submit' name='SIselected' value='donestockitem'>Done</button>
_END;
        }
        break;
} //end switch on SIselected



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
