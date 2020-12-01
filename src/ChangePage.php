
<?php

/**
 * ok 20180316
 *
 * change page test page, no login
 *
 **/


/**
 * formaction: 
 * formmessage: instructions for form
 * formcontent: 
 *
 **/

$currentworkingdirectory = getcwd();
$pagetitle = 'Change Page';
$doit = $logmsg = '';

require_once('Functions.php');

/**
 * ChangePage.php
 * http://localhost:8888/MFSInventory/ChangePage.php
 */
//var_dump($_POST);
if (isset($_POST['gotopage'])) {
    $doit = $_POST['gotopage'];
} else {
    $doit = "changepage";
}
//print_r($doit);

switch ($doit) {
    case "changepage":
        $formaction = <<<_END
action='ChangePage.php' method='post'
_END;
        $formmessage = <<<_END
<h2>Change Page</h2>
_END;
        $formcontent = <<<_END
<label for='gotopage'>Go To Page</label>
<select id='gotopage' name='gotopage'>
<option value='lookupitem'>Lookup Item</option>
<option value='stockitem'>Stock Item</option>
<option value='lookupjob'>Lookup Job</option>
</select>
<button class='large' type='submit' name='CPselected'>Go</button>
_END;
        break;
    case "lookupitem":
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
        break;

    case "lookupjob":
        $formaction = <<<_END
action='LookupJob.php' method='post'
_END;
        $formmessage = <<<_END
<h2>Lookup Job</h2>
_END;
        $formcontent = <<<_END
<label for='jobnumber'>Job Number</label>
<input type='text' id='jobnumber' name='jobnumber'>
<button class='large' type='submit' name='LJselected' value='lookupjob'>Lookup Job</button>
<button class='large' type='submit' name='LJselected' value='donelookupjob'>Done</button>
_END;
        break;

    case "addinvoice":
        $formaction = <<<_END
action='AddInvoice.php' method='post'
_END;
        $formmessage = <<<_END
<h2>Add Invoice</h2>
_END;
        $formcontent = <<<_END
<label for='itemnumber'>Item Number</label>
<input type='text' id='itemnumber' name='itemnumber'>
<button class='large' type='submit' name='AIselected' value='addinvoice'>Add Invoice</button>
<button class='large' type='submit' name='AIselected' value='doneaddinvoice'>Done</button>
_END;
        break;

    case "stockitem":
        $formaction = <<<_END
action='StockItem.php' method='post'
_END;
        $formmessage = <<<_END
<h2>Stock Item Default</h2>
_END;
        $formcontent = <<<_END
<label for='itemnumber'>Item Number</label>
<input type='text' id='itemnumber' name='itemnumber'>
<button class='large' type='submit' name='SIselected' value='stockitem'>Stock Item</button>
<button class='large' type='submit' name='SIselected' value='donestockitem'>Done</button>
_END;
        break;

    case "addcogitem":
        $formaction = <<<_END
action='AddCogItem.php' method='post'
_END;
        $formmessage = <<<_END
<h2>Add COG Item</h2>
_END;
        $formcontent = <<<_END
<label for='itemnumber'>Item Number</label>
<input type='text' id='itemnumber' name='itemnumber'>
<button class='large' type='submit' name='ACselected' value='addcogitem'>Add COG Item</button>
<button class='large' type='submit' name='ACselected' value='doneaddcogitem'>Done</button>
_END;
        break;

    default:
        $formaction = <<<_END
action='ChangePage.php' method='post'
_END;

        $formmessage = <<<_END
<h2>Change Page: $doit not found</h2>
_END;

        $formcontent = <<<_END
<label for='gotopage'>Go To Page</label>
<select id='gotopage' name='gotopage'>
<option value='lookupitem'>Lookup Item</option>
<option value='stockitem'>Stock Item</option>
<option value='lookupjob'>Lookup Job</option>
</select>
<button class='large' type='submit' name='CPselected'>Go</button>
_END;

        break;
} //end switch

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