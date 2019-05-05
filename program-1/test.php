<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>

        <title>example</title>

        <style type="text/css">
            body {
                font-family:Verdana, Geneva, sans-serif;
                font-size:13px;
                color:#333;

            }
        </style>

        <script type="text/javascript" src="jquery.min.js"></script>


    </head>
    <body>
        <form method="post">
            <div class="input_fields_wrap">
                <div>First line: Two space separated integers N and M: <input type="text" name="field1" id="field1"></div>
                <div id="dicword"></div>
                <br/>
                <div>Q: <input type="text" name="field2" id="field2"></div>
                <div id="queryword"></div>

                <input type="submit" name="submit"/> 
            </div>
        </form>
    </body>
    <script>
      $(document).ready(function () {
          //var max_fields      = 10; //maximum input boxes allowed
          var wrapper = $("#dicword"); //Fields wrapper
          var addfield = $("#field1"); //Add button ID

          $(addfield).blur(function (e) { //on add input button click
              e.preventDefault();
              var addfieldVal = $(this).val();
              var addfieldValArr = addfieldVal.split('  ');
              if ($.isNumeric(addfieldValArr[0]) && $.isNumeric(addfieldValArr[1])) {
                  var max_fields = addfieldValArr[0];
                  var x = $("#dicword input").length;
                  if (x < max_fields) {
                      for (var i = 1; i <= max_fields; i++) {
                          $(wrapper).append('<div>Add word ' + i + ' : <input type="text" name="word[]" maxlength="' + addfieldValArr[1] + '" /></div>'); //add input box
                      }
                  }
              }
          });

          $("#field2").blur(function (e) { //on add input button click
              e.preventDefault();
              var addfieldVal = addfield.val();
              var addfieldValArr = addfieldVal.split('  ');
              var addqfieldVal = $(this).val();
              if ($.isNumeric(addqfieldVal)) {
                  var max_qfields = addqfieldVal;
                  var y = $("#queryword input").length;
                  if (y < max_qfields) {
                      for (var j = 1; j <= max_qfields; j++) {
                          $("#queryword").append('<div>Add Query word ' + j + ' : <input type="text" name="query[]" maxlength="' + addfieldValArr[1] + '" /></div>'); //add input box
                      }
                  }
              }
          });

      });
    </script>
</html>

<?php

class dictionary {

  Public $dicword;
  Public $queryword;

  function __construct($dword, $qword) {
    $this->dicword = $dword;
    $this->queryword = $qword;
  }

  function dicquery() {
    $dvalArr = array();
    foreach ($this->queryword as $k => $qval) {
      $filterword = '?';
      $filtered = $this->strpos_all($qval, $filterword);
      $filtered = array_reverse($filtered);
      foreach ($this->dicword as $dval) {
        $daval = $dval;
        foreach ($filtered as $fk => $val) {
          $daval = substr_replace($daval, '', $val, 1);
          if (count($filtered) == $fk + 1)
            $dvalArr[$k][] = $daval;
          if (count($filtered) > 1)
            $daval = $daval;
        }
      }
    }
    foreach ($dvalArr as $dk => $dival) {
      $filterword = '?';
      $qvalr = str_replace($filterword, '', $this->queryword[$dk]);
      $l = 0;
      foreach ($dival as $dlval) {
        if ($dlval == $qvalr)
          $l++;
      }
      echo $l . "<br/>";
    }
  }

  function strpos_all($haystack, $needle) {
    $offset = 0;
    $allpos = array();
    while (($pos = strpos($haystack, $needle, $offset)) !== FALSE) {
      $offset = $pos + 1;
      $allpos[] = $pos;
    }
    return $allpos;
  }

}

if (isset($_POST['word']) && is_array($_POST['word']) && isset($_POST['query']) && is_array($_POST['query'])) {
  $dword = $_POST['word'];
  $qword = $_POST['query'];

  echo "<br/><br/>Dictonary Word: <br/>" . implode('<br/>', $dword);
  echo "<br/><br/>Query Word: <br/>" . implode('<br/>', $qword) . "<br/>";

  $dicresult = new dictionary($dword, $qword);
  echo "<br/>Output: <br/>";
  $dicresult->dicquery();
}
?>