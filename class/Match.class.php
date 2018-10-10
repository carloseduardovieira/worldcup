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
    if( !$oMatch || !$oMatch->idHomeTeam || !$oMatch->idVisitingTeam || !$oMatch->matchTime ) die('some value not found');

    $dDateReceived = DateTime::createFromFormat('Y-m-d H:i', $oMatch->matchTime);
    if(!($dDateReceived && $dDateReceived->format('Y-m-d H:i') === $oMatch->matchTime)){
        die('Error! Invalid date format expected. yyyy-mm-dd H:i');
    }

    $this->matchTime = date('Y-m-d H:i', strtotime($oMatch->matchTime));
    $this->idHomeTeam = (int) htmlspecialchars(strip_tags($oMatch->idHomeTeam));
    $this->idVisitingTeam = (int) htmlspecialchars(strip_tags($oMatch->idVisitingTeam));
    $iIdMatch = '';
    if($this->idHomeTeam === $this->idVisitingTeam) die('Error! Teams equal');
    
    $sQuery = "INSERT INTO matches (idHomeTeam, idVisitingTeam, matchTime) VALUES(?,?,?)";
    $aParams = array($this->idHomeTeam, $this->idVisitingTeam, $this->matchTime);
    
    try {
        $this->conn->beginTransaction(); 
        $stmt = $this->conn->prepare( $sQuery ); 
        $stmt->execute( $aParams );         
        $iIdLastInsert = $this->conn->lastInsertId(); 
        if($iIdLastInsert !== 0){
            $this->conn->commit(); 
            return $iIdLastInsert;    
        }
        $this->conn->rollback();
        return 0;
    } catch(PDOExecption $e) { 
        $this->conn->rollback();         
        return 0;
    }
}

 public function FindMatchesByDay( $dMatch ) {
    
    $dDateReceived = DateTime::createFromFormat('Y-m-d', $dMatch);
    if(!($dDateReceived && $dDateReceived->format('Y-m-d') === $dMatch)){
        die('Error! Invalid date format expected. yyyy-mm-dd');
    }

    $this->matchTime = date('Y-m-d', strtotime($dMatch));
    
    $sQuery = "SELECT 
        m.id as idPartida, t.id as idHomeTeam, t.name as homeTeam, 
        t.image as imgHomeTeam, t2.id as idVisitingTeam, t2.name as visitingTeam, 
        t2.image as imgVisitingTeam, m.matchTime 
        FROM matches m JOIN teams as t ON t.id = m.idHomeTeam
        JOIN teams t2 ON t2.id = m.idVisitingTeam
        WHERE matchTime LIKE '%".$this->matchTime."%'";    
    try {                 
        $stmt = $this->conn->query( $sQuery );        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOExecption $e) { 
        return $this->conn->errorInfo();
    }  
 }

}