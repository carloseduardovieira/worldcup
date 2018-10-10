<?php

// Criar um novo jogo. Para criar um jogo, é necessário especificar quais são os dois times e a data e hora do jogo 

class Match {
 
 private $conn;

 public $id;
 public $idHomeTeam;
 public $idVisitingTeam;
 public $matchTime;
 
 public function __construct($db){
     $this->conn = $db;
 }
 
 public function Create( $oMatch ){
    if( !$oMatch->idHomeTeam || !$oMatch->idVisitingTeam || !$oMatch->matchTime ) die('some value not found');

    $dDateReceived = DateTime::createFromFormat('Y-m-d H:i', $oMatch->matchTime);
    if(!($dDateReceived && $dDateReceived->format('Y-m-d H:i') === $oMatch->matchTime)){
        die('Error! Invalid date format expected. yyyy-mm-dd H:i');
    }

    $this->matchTime = date('Y-m-d H:i', strtotime($oMatch->matchTime));
    $this->idHomeTeam = (int) htmlspecialchars(strip_tags($oMatch->idHomeTeam));
    $this->idVisitingTeam = (int) htmlspecialchars(strip_tags($oMatch->idVisitingTeam));
    $iIdMatch = '';

    $sQuery = "INSERT INTO matches (matchTime) VALUES(?)";
    $aParams = array($this->matchTime);
    $iIdMatch = $this->Insert($sQuery, $aParams);
    
    if(!is_numeric($iIdMatch)) die( $iIdMatch );

    $sQuery = "INSERT INTO teams_has_matches (teams_id, matches_id) VALUES(?,?)";
    $aParams = array($this->idHomeTeam, $iIdMatch);
    $this->Insert($sQuery, $aParams);
    
    $aParams = array($this->idVisitingTeam, $iIdMatch);
    $this->Insert($sQuery, $aParams);
}

 public function Insert($sQuery, $aParams = []){
    $stmt = $this->conn->prepare( $sQuery );
    try { 
        $this->conn->beginTransaction(); 
        $stmt->execute( $aParams );         
        $lastInsert = $this->conn->lastInsertId(); 
        $this->conn->commit(); 
        return $lastInsert;
    } catch(PDOExecption $e) { 
        $this->conn->rollback(); 
        return "Error!: " . $e->getMessage() . "</br>"; 
    }
 }

 public function FindMatchesByDay( $dMatch ) {
    
    $dDateReceived = DateTime::createFromFormat('Y-m-d', $dMatch);
    if(!($dDateReceived && $dDateReceived->format('Y-m-d') === $dMatch)){
        die('Error! Invalid date format expected. yyyy-mm-dd');
    }

    $this->matchTime = date('Y-m-d', strtotime($dMatch));
    
    $sQuery = "      
    ";
    
    try {                 
        $stmt = $this->conn->query( $sQuery );        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOExecption $e) { 
        return $this->conn->errorInfo();
    }  
 }

}