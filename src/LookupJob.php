<?php

/**
 *
 * lookup job test page, no login
 *  remove item from ii_physical for job in working
 *  remove all instances of job from ii_working
 * TODO tie itemunit to dropdown list from database 
 **/


/**
 * test begin
 *
 **/




/**
 * test end
 *
 **/



/**
 * formaction: 
 * formmessage: instructions for form
 * formcontent: 
 *
 **/
$currentworkingdirectory = getcwd();
$pagetitle = 'Lookup Job';
$doit = $logmsg = '';
$formmessage = $formaction = $formcontent = '';
//$itemtocheck = $itemdescription = '';
//$mitemnumber = '';

$jobtofind = '';
$foundjobs = array();
$numofjob = '';

require_once('Functions.php');

/**
 * Lookup Job
 * http://localhost:8888/MFSInventory/LookupJob.php
 */
if (isset($_POST['LJselected'])) {
    $doit = $_POST['LJselected'];
} else {
    $doit = "lookupjob";
}

switch ($doit) {
    case "lookupjob":
        if (isset($_POST['jobnumber'])) {
//look up one job
            $jobtofind = $_POST['jobnumber'];

            $foundjobs = lookupJob($jobtofind);

            if (count($foundjobs) > 0) {

                if ( $jobtofind == "all" ) {
//look up all jobs
                    $numofjob = count($foundjobs);
                    $formcontent = populateLookupJobResulttable($foundjobs);
                    $formaction = <<<_END
action='LookupJob.php' method='post'
_END;
                    $formmessage = <<<_END
<h2>Found $jobtofind open jobs.</h2>
_END;
                    $formcontent .= <<<_END
<input hidden name='previousjobnumber' value=$jobtofind>
<button class='large' type='submit' name='LJselected' value='deletejob'>Clear Job</button>
<br><label for='jobnumber'>Job Number</label>
<input type='text' id='jobnumber' name='jobnumber'>
<button class='large' type='submit' name='LJselected' value='lookupjob'>Lookup Job</button>
<button class='large' type='submit' name='LJselected' value='donelookupjob'>Done</button>
_END;
                } else {
//look up one job
                    $numofjob = count($foundjobs);
                    $formcontent = populateLookupJobResulttable($foundjobs);
                    $formaction = <<<_END
action='LookupJob.php' method='post'
_END;
                    $formmessage = <<<_END
<h2>Found $numofjob instances of job $jobtofind</h2>
_END;
                    $formcontent .= <<<_END
<input hidden name='previousjobnumber' value=$jobtofind>
<button class='large' type='submit' name='LJselected' value='deletejob'>Clear Job</button>
<br><label for='jobnumber'>Job Number</label>
<input type='text' id='jobnumber' name='jobnumber'>
<button class='large' type='submit' name='LJselected' value='lookupjob'>Lookup Job</button>
<button class='large' type='submit' name='LJselected' value='donelookupjob'>Done</button>
_END;
                } //end if all 

            } else {
                $formcontent = "job not found";
                $formaction = <<<_END
action='LookupJob.php' method='post'
_END;
                $formmessage = <<<_END
<h2>Found 0 instances of job $jobtofind</h2>
_END;
                $formcontent .= <<<_END
<input hidden name='previousjobnumber' value=$jobtofind>
<label for='jobnumber'>Job Number</label>
<input type='text' id='jobnumber' name='jobnumber'>
<button class='large' type='submit' name='LJselected' value='lookupjob'>Lookup Job</button>
<button class='large' type='submit' name='LJselected' value='donelookupjob'>Done</button>
_END;
            } // end if count items found

        } else {
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
        } //end if variables set
        break;

    case "deletejob":
        /*
         * $_POST['tofinishjoblist'] job number array
         *
         */
            $deletejoblist = $_POST['tofinishjoblist'];
            $formcontent = <<<_END
<table align='center'>
  <tr>
    <th align='right'>Job Number</th>
    <th align='left'>Materials</th>
  </tr>
_END;
            $outcome = $itemidlist = $itemid = '';
            foreach ($deletejoblist as $jobnum) {
               $formcontent .= <<<_END
<tr>
  <td>$jobnum  </td>
_END;
               $itemidlist = getitemidsforjob($jobnum);
               foreach ($itemidlist as $itemid) {
$justitemnumber = $itemid['fk_iiwork_itemnumber_mitem'];
$justitemid = $itemid['fk_iiwork_id_iiphys'];
                   $outcome = deleteJobItem($jobnum,$itemid['fk_iiwork_id_iiphys']);
                   if ($outcome == 'success') {
                           $formcontent .= <<<_END
  <td>$justitemnumber  
_END;
                       $outcome = '';
                       $outcome = deletePhysicalItem($itemid['fk_iiwork_id_iiphys']);
                       if ($outcome != 'success') {
                           $formcontent .= <<<_END
FAILED (phys $justitemid);
_END;
                       } else {
                           $formcontent .= <<<_END
deleted;
_END;
                       } // if outcome physical item
                   } else {
                       $formcontent .= <<<_END
FAILED (work $justitemid);
_END;
                   }// if outcome work item


               } //end foreach item

               $formcontent .= <<<_END
</td></tr>
_END;
            } //end foreach joblist

            $formaction = <<<_END
action='LookupJob.php' method='post'
_END;
            $formmessage = <<<_END
<h2>Clearing the following jobs and their materials: </h2>
_END;
            $formcontent .= <<<_END
</table>
<button class='large' type='submit' name='LJselected' value='lookupjob'>Lookup Job</button>
<button class='large' type='submit' name='LJselected' value='donelookupjob'>Done</button>
_END;
        break;

    case "donelookupjob":
        unset($jobnum);
        unset($foundjobs);
        unset($doit);
        unset($formaction);
        unset($formmessage); 
        unset($formcontent);

        $formaction = <<<_END
action='ChangePage.php' method='post'
_END;

        $formmessage = <<<_END
<h2>Done looking up jobs.</h2>
_END;

        $formcontent = <<<_END
<input hidden id='gotopage' name='gotopage' value='changepage'>
<button class='large' type='submit' name='CPselected'>Choose New Action</button>
_END;

        break;

    default:
        /**
         * 
         * print default lookup job form
         * $formaction, $formmessage, $formcontent must have values
         *
         *ok
         */
        unset($jobnum);
        unset($foundjobs);
        unset($doit);

        if (!isset($formaction)) {
$formaction = <<<_END
action='LookupJob.php' method='post'
_END;
}
        if (!isset($formmessage)) {
$formmessage = <<<_END
<h2>Lookup Job Fall Thru</h2>
_END;
}
        if (!isset($formcontent)) {
$formcontent = <<<_END
<label for='jobnumber'>Job Number</label>
<input type='text' id='jobnumber' name='jobnumber'>
<button class='large' type='submit' name='LJselected' value='lookupjob'>Lookup Job</button>
<button class='large' type='submit' name='LJselected' value='donelookupjob'>Done</button>
_END;
}
        break;
} //end switch on LJselected

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
