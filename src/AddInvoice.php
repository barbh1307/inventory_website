<?php

/**
 *
 * add invoice test page, no login
 *  adding invoices to m_invoice
 * TODO tie itemunit to dropdown list from database 
 **/


/**
 * formaction: 
 * formmessage: instructions for form
 * formcontent: 
 *
 **/
$currentworkingdirectory = getcwd();
$pagetitle = 'Add Invoice';
$doit = $logmsg = '';
$invoicetoadd = $itemdescription = '';
$iteuni = $itelen = $itewid = $iteloc = '';
$invfound = '';

require_once('Functions.php');

/**
 * Add Invoice
 * http://localhost:8888/MFSInventory/AddInvoice.php
 */
if (isset($_POST['AIselected'])) {
    $doit = $_POST['AIselected'];
} else {
    $doit = "addinvoice";
}
switch ($doit) {
    case "addinvoice":
        if (isset($_POST['invoicenumber'])) {

            $invoicetoadd = $_POST['invoicenumber'];

            $invfound = checkMasterInvoice($invoicetoadd);

            if (isset($invfound)) {
                $formaction = <<<_END
action='AddInvoice.php' method='post'
_END;
                $formmessage = <<<_END
$invoicetoadd already entered
_END;
        $formcontent = <<<_END
<label for='invoicenumber'>Enter Another Invoice Number</label>
<input type='text' id='invoicenumber' name='invoicenumber'>
<button class='large' type='submit' name='AIselected' value='addinvoice'>Add Invoice</button>
<button class='large' type='submit' name='AIselected' value='doneaddinvoice'>Done</button>
_END;
            } else {
            
                $formaction = <<<_END
action='AddInvoice.php' method='post'
_END;
                $formmessage = <<<_END
<h2>Add Invoice $invoicetoadd</h2>
_END;
                $formcontent = <<<_END
<input hidden name='addinvoicenumber' value=$invoicetoadd>
<br><label for='invoicedate'>Invoice date</label>
<input type='date' id='invoicedate' name='invoicedate'>
<br><label for='companycode'>Company Code</label>
<input type='text' id='companycode' name='companycode'>
<br><label for='enteredby'>Entered By</label>
<input type='text' id='enteredby' name='enteredby'>
<br><button class='large' type='submit' name='AIselected' value='runaddinvoicequeries'>Add Invoice Submit</button>
<button class='large' type='submit' name='AIselected' value='addinvoice'>Cancel</button>
_END;
            }//end if invoice found
 
        } else {
        $formaction = <<<_END
action='AddInvoice.php' method='post'
_END;
        $formmessage = <<<_END
<h2>Enter Invoice Number</h2>
_END;
        $formcontent = <<<_END
<label for='invoicenumber'>Invoice Number</label>
<input type='text' id='invoicenumber' name='invoicenumber'>
<button class='large' type='submit' name='AIselected' value='addinvoice'>Add Invoice</button>
<button class='large' type='submit' name='AIselected' value='doneaddinvoice'>Done</button>
_END;
        } //end if variables set
        break;

    case "runaddinvoicequeries":
        /*
         * $_POST['addinvoicenumber'] masterinvoice number
         * $_POST['invoicedate'] date on invoice
         * $_POST['companycode'] two digit company code, from m_company
         * $_POST['enteredby'] user, from m_user         
         *
         */
        unset($logmsg);

        if (isset($_POST['addinvoicenumber'])) {
            $minvnumber = $_POST['addinvoicenumber'];
        } else {
            $logmsg = "missing addinvoicenumber"; 
        }
        if (isset($_POST['invoicedate'])) {
            $invdat = $_POST['invoicedate'];
        } else {
            $logmsg = "missing invoice date"; 
        }
        if (isset($_POST['companycode'])) {
            $comcod = $_POST['companycode'];
        } else {
            $logmsg = "missing company code"; 
        }
        if (isset($_POST['enteredby'])) {
            $entby = $_POST['enteredby'];
        } else {
            $logmsg = "missing entered by"; 
        } 
        
        if (!isset($logmsg)) {
            $outcome = addInvoice($minvnumber, $invdat, $comcod, $entby);
            if ($outcome == 'success') {
                $formmessage = <<<_END
added $minvnumber <br>
_END;
            } else {
                $formmessage = <<<_END
ERROR adding invoice $minvnumber <br>
_END;
            } //end if outcome assignitem
        } else {
           print $logmsg;
        } // end if logmsg

        $formaction = <<<_END
action='AddInvoice.php' method='post'
_END;
        $formmessage .= <<<_END
Add another invoice?
_END;
        $formcontent = <<<_END
<label for='invoicenumber'>Invoice Number</label>
<input type='text' id='invoicenumber' name='invoicenumber'>
<button class='large' type='submit' name='AIselected' value='addinvoice'>Add Invoice</button>
<button class='large' type='submit' name='AIselected' value='doneaddinvoice'>Done</button>
_END;

        break;

    case "doneaddinvoice":
        unset($doit);
        unset($invoicetoadd);
        unset($minvnumber);
        unset($invdat);
        unset($comcod);
        unset($entby);
        unset($logmsg);

        $formaction = <<<_END
action='ChangePage.php' method='post'
_END;

        $formmessage = <<<_END
<h2>Done adding invoices.</h2>
_END;

        $formcontent = <<<_END
<input hidden id='gotopage' name='gotopage' value='changepage'>
<button class='large' type='submit' name='CPselected'>Choose New Action</button>
_END;

        break;

    default:
        /**
         * 
         * print default Add Invoice form
         * $formaction, $formmessage, $formcontent must have values
         *
         *ok
         */

        if (!isset($formaction)) {
            $formaction = <<<_END
action='AddInvoice.php' method='post'
_END;
        }
        if (!isset($formmessage)) {
            $formmessage = <<<_END
<h2>Add Invoice Default</h2>
_END;
        }
        if (!isset($formcontent)) {
            $formcontent = <<<_END
<label for='itemnumber'>Item Number</label>
<input type='text' id='itemnumber' name='itemnumber'>
<button class='large' type='submit' name='AIselected' value='addinvoice'>Add Invoice</button>
<button class='large' type='submit' name='AIselected' value='doneaddinvoice'>Done</button>
_END;
        }
        break;
} //end switch on AIselected



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
