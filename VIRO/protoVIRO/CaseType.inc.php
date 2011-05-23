<?php // generated with ADL vs. 0.8.10-452
  
  /********* on line 776, file "VIRO453ENG.adl"
    SERVICE CaseType : I[CaseType]
   = [ Cases : caseType~
        = [ nr : [Case]
          , area of law : areaOfLaw
          , caretaker of case file : caretaker
          ]
     ]
   *********/
  
  class CaseType {
    protected $_id=false;
    protected $_new=true;
    private $_Cases;
    function CaseType($id=null, $Cases=null){
      $this->_id=$id;
      $this->_Cases=$Cases;
      if(!isset($Cases) && isset($id)){
        // get a CaseType based on its identifier
        // check if it exists:
        $ctx = DB_doquer('SELECT DISTINCT fst.`AttCaseType` AS `i`
                           FROM 
                              ( SELECT DISTINCT `i` AS `AttCaseType`, `i`
                                  FROM `casetype`
                              ) AS fst
                          WHERE fst.`AttCaseType` = \''.addSlashes($id).'\'');
        if(count($ctx)==0) $this->_new=true; else
        {
          $this->_new=false;
          // fill the attributes
          $me=array();
          $me['Cases']=(DB_doquer("SELECT DISTINCT `case`.`i` AS `id`
                                     FROM `case`
                                    WHERE `case`.`casetype`='".addslashes($id)."'"));
          foreach($me['Cases'] as $i0=>&$v0){
            $v0=firstRow(DB_doquer("SELECT DISTINCT '".addslashes($v0['id'])."' AS `id`
                                         , '".addslashes($v0['id'])."' AS `nr`
                                         , `f3`.`areaoflaw` AS `area of law`
                                         , `f4`.`caretaker` AS `caretaker of case file`
                                      FROM `case`
                                      LEFT JOIN `case` AS f3 ON `f3`.`i`='".addslashes($v0['id'])."'
                                      LEFT JOIN `case` AS f4 ON `f4`.`i`='".addslashes($v0['id'])."'
                                     WHERE `case`.`i`='".addslashes($v0['id'])."'"));
          }
          unset($v0);
          $this->set_Cases($me['Cases']);
        }
      }
      else if(isset($id)){ // just check if it exists
        $ctx = DB_doquer('SELECT DISTINCT fst.`AttCaseType` AS `i`
                           FROM 
                              ( SELECT DISTINCT `i` AS `AttCaseType`, `i`
                                  FROM `casetype`
                              ) AS fst
                          WHERE fst.`AttCaseType` = \''.addSlashes($id).'\'');
        $this->_new=(count($ctx)==0);
      }
    }

    function save(){
      DB_doquer('START TRANSACTION');
      /****************************************\
      * Attributes that will not be saved are: *
      * -------------------------------------- *
      \****************************************/
      $newID = ($this->getId()===false);
      $me=array("id"=>$this->getId(), "Cases" => $this->_Cases);
      foreach($me['Cases'] as $i0=>$v0){
        if(isset($v0['id']))
          DB_doquer("UPDATE `case` SET `i`='".addslashes($v0['id'])."', `areaoflaw`='".addslashes($v0['area of law'])."', `caretaker`='".addslashes($v0['caretaker of case file'])."' WHERE `i`='".addslashes($v0['nr'])."'", 5);
      }
      foreach  ($me['Cases'] as $Cases){
        if(isset($me['id']))
          DB_doquer("UPDATE `case` SET `casetype`='".addslashes($me['id'])."' WHERE `i`='".addslashes($Cases['id'])."'", 5);
      }
      // no code for nr,i in case
      foreach($me['Cases'] as $i0=>$v0){
        DB_doquer("DELETE FROM `organ` WHERE `i`='".addslashes($v0['caretaker of case file'])."'",5);
      }
      foreach($me['Cases'] as $i0=>$v0){
        $res=DB_doquer("INSERT IGNORE INTO `organ` (`i`) VALUES ('".addslashes($v0['caretaker of case file'])."')", 5);
      }
      foreach($me['Cases'] as $i0=>$v0){
        DB_doquer("DELETE FROM `areaoflaw` WHERE `i`='".addslashes($v0['area of law'])."'",5);
      }
      foreach($me['Cases'] as $i0=>$v0){
        $res=DB_doquer("INSERT IGNORE INTO `areaoflaw` (`i`) VALUES ('".addslashes($v0['area of law'])."')", 5);
      }
      // no code for Cases,case in plaintiff
      // no code for nr,case in plaintiff
      if (!checkRule1()){
        $DB_err='\"Voor elke procedure moet er tenminste een eisende partij zijn.\"';
      } else
      if (!checkRule9()){
        $DB_err='\"\"';
      } else
      if (!checkRule10()){
        $DB_err='\"\"';
      } else
      if (!checkRule11()){
        $DB_err='\"\"';
      } else
      if (!checkRule15()){
        $DB_err='\"\"';
      } else
      if (!checkRule16()){
        $DB_err='\"\"';
      } else
      if (!checkRule17()){
        $DB_err='\"\"';
      } else
      if (!checkRule18()){
        $DB_err='\"\"';
      } else
      if (!checkRule21()){
        $DB_err='\"\"';
      } else
      if(true){ // all rules are met
        DB_doquer('COMMIT');
        return $this->getId();
      }
      DB_doquer('ROLLBACK');
      return false;
    }
    function del(){
      DB_doquer('START TRANSACTION');
      $me=array("id"=>$this->getId(), "Cases" => $this->_Cases);
      foreach($me['Cases'] as $i0=>$v0){
        DB_doquer("DELETE FROM `organ` WHERE `i`='".addslashes($v0['caretaker of case file'])."'",5);
      }
      foreach($me['Cases'] as $i0=>$v0){
        DB_doquer("DELETE FROM `areaoflaw` WHERE `i`='".addslashes($v0['area of law'])."'",5);
      }
      if (!checkRule1()){
        $DB_err='\"Voor elke procedure moet er tenminste een eisende partij zijn.\"';
      } else
      if (!checkRule9()){
        $DB_err='\"\"';
      } else
      if (!checkRule10()){
        $DB_err='\"\"';
      } else
      if (!checkRule11()){
        $DB_err='\"\"';
      } else
      if (!checkRule15()){
        $DB_err='\"\"';
      } else
      if (!checkRule16()){
        $DB_err='\"\"';
      } else
      if (!checkRule17()){
        $DB_err='\"\"';
      } else
      if (!checkRule18()){
        $DB_err='\"\"';
      } else
      if (!checkRule21()){
        $DB_err='\"\"';
      } else
      if(true){ // all rules are met
        DB_doquer('COMMIT');
        return true;
      }
      DB_doquer('ROLLBACK');
      return false;
    }
    function set_Cases($val){
      $this->_Cases=$val;
    }
    function get_Cases(){
      if(!isset($this->_Cases)) return array();
      return $this->_Cases;
    }
    function setId($id){
      $this->_id=$id;
      return $this->_id;
    }
    function getId(){
      if($this->_id===null) return false;
      return $this->_id;
    }
    function isNew(){
      return $this->_new;
    }
  }

  function getEachCaseType(){
    return firstCol(DB_doquer('SELECT DISTINCT `i`
                                 FROM `casetype`'));
  }

  function readCaseType($id){
      // check existence of $id
      $obj = new CaseType($id);
      if($obj->isNew()) return false; else return $obj;
  }

  function delCaseType($id){
    $tobeDeleted = new CaseType($id);
    if($tobeDeleted->isNew()) return true; // item never existed in the first place
    if($tobeDeleted->del()) return true; else return $tobeDeleted;
  }

?>