<?php // generated with ADL vs. 0.8.10-593
  
  /********* on line 97, file "apps/meterkast/meterkast.adl"
    SERVICE Gebruiker : I[Gebruiker]
   = [ sessies : user~
     ]
   *********/
  
  class Gebruiker {
    protected $id=false;
    protected $_new=true;
    private $_sessies;
    function Gebruiker($id=null, $_sessies=null){
      $this->id=$id;
      $this->_sessies=$_sessies;
      if(!isset($_sessies) && isset($id)){
        // get a Gebruiker based on its identifier
        // check if it exists:
        $ctx = DB_doquer('SELECT DISTINCT fst.`AttGebruiker` AS `i`
                           FROM 
                              ( SELECT DISTINCT `i` AS `AttGebruiker`, `i`
                                  FROM `gebruiker`
                              ) AS fst
                          WHERE fst.`AttGebruiker` = \''.addSlashes($id).'\'');
        if(count($ctx)==0) $this->_new=true; else
        {
          $this->_new=false;
          // fill the attributes
          $me=array();
          $me['sessies']=firstCol(DB_doquer("SELECT DISTINCT `sessietbl`.`id` AS `sessies`
                                               FROM `sessietbl`
                                              WHERE `sessietbl`.`gebruiker`='".addslashes($id)."'"));
          $this->set_sessies($me['sessies']);
        }
      }
      else if(isset($id)){ // just check if it exists
        $ctx = DB_doquer('SELECT DISTINCT fst.`AttGebruiker` AS `i`
                           FROM 
                              ( SELECT DISTINCT `i` AS `AttGebruiker`, `i`
                                  FROM `gebruiker`
                              ) AS fst
                          WHERE fst.`AttGebruiker` = \''.addSlashes($id).'\'');
        $this->_new=(count($ctx)==0);
      }
    }

    function save(){
      DB_doquer('START TRANSACTION');
      /**************************\
      * All attributes are saved *
      \**************************/
      $newID = ($this->getId()===false);
      $me=array("id"=>$this->getId(), "sessies" => $this->_sessies);
      // no code for sessies,id in sessietbl
      foreach  ($me['sessies'] as $sessies){
        if(isset($me['id']))
          DB_doquer("UPDATE `sessietbl` SET `gebruiker`='".addslashes($me['id'])."' WHERE `id`='".addslashes($sessies)."'", 5);
      }
      if(true){ // all rules are met
        DB_doquer('COMMIT');
        return $this->getId();
      }
      DB_doquer('ROLLBACK');
      return false;
    }
    function del(){
      DB_doquer('START TRANSACTION');
      $me=array("id"=>$this->getId(), "sessies" => $this->_sessies);
      if(true){ // all rules are met
        DB_doquer('COMMIT');
        return true;
      }
      DB_doquer('ROLLBACK');
      return false;
    }
    function set_sessies($val){
      $this->_sessies=$val;
    }
    function get_sessies(){
      if(!isset($this->_sessies)) return array();
      return $this->_sessies;
    }
    function setId($id){
      $this->id=$id;
      return $this->id;
    }
    function getId(){
      if($this->id===null) return false;
      return $this->id;
    }
    function isNew(){
      return $this->_new;
    }
  }

  function getEachGebruiker(){
    return firstCol(DB_doquer('SELECT DISTINCT `i`
                                 FROM `gebruiker`'));
  }

  function readGebruiker($id){
      // check existence of $id
      $obj = new Gebruiker($id);
      if($obj->isNew()) return false; else return $obj;
  }

  function delGebruiker($id){
    $tobeDeleted = new Gebruiker($id);
    if($tobeDeleted->isNew()) return true; // item never existed in the first place
    if($tobeDeleted->del()) return true; else return $tobeDeleted;
  }

?>