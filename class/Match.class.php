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

    $sQuery = "INSERT INTO matches SET idHomeTeam=:idHomeTeam, idVisitingTeam=:idVisitingTeam, matchTime=:matchTime";
    
    $stmt = $this->conn->prepare( $sQuery );
    $this->idHomeTeam = htmlspecialchars(strip_tags($oMatch->idHomeTeam));
    $this->idVisitingTeam = htmlspecialchars(strip_tags($oMatch->idVisitingTeam));
    $this->matchTime = date('Y-m-d H:i', strtotime($oMatch->matchTime));    
     
    $stmt->bindParam(":idHomeTeam", $this->idHomeTeam);
    $stmt->bindParam(":idVisitingTeam", $this->idVisitingTeam);
    $stmt->bindParam(":matchTime", $this->matchTime);
    
    if( $stmt->execute() ){
        return true;
    }
 
    return false;
 }

 public function FindMatchByDay( $dMatch ) {
    echo '<pre>';
    print_r($oMatch);
    echo '</pre>';
 }

}