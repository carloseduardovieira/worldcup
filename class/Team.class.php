<?php

class Team{
 
    private $conn;
 
    public $id;
    public $name;
    public $image;
    
    public function __construct($db){
        $this->conn = $db;
    }

    public function Create( $oTeam ) {
        if( !$oTeam->name || !$oTeam->image ) die('some value not found');              
    
        $this->name = htmlspecialchars(strip_tags($oTeam->name));
        $this->image = htmlspecialchars(strip_tags($oTeam->image));
        
        $sQuery = "INSERT INTO teams (name, image) VALUES(?,?)";
        $aParams = array($this->name,$this->image);

        $stmt = $this->conn->prepare( $sQuery );
        try { 
            $this->conn->beginTransaction(); 
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
            return "Error!: " . $e->getMessage() . "</br>"; 
        }
    }

    public function FindTeamMatches( $iIdTeam ) {
        $sQuery = "SELECT 
            m.id as idPartida, t.id as idHomeTeam, t.name as homeTeam,
            t.image as imgHomeTeam, t2.id as idVisitingTeam, t2.name as visitingTeam, 
            t2.image as imgVisitingTeam, m.matchTime 
            FROM matches m JOIN teams as t ON t.id = m.idHomeTeam
            JOIN teams t2 ON t2.id = m.idVisitingTeam
            WHERE idHomeTeam = ? OR idVisitingTeam = ? 
        ";
        $this->id = (int) $iIdTeam;
        $aParams = array($this->id, $this->id);
        try {                 
            $stmt = $this->conn->prepare( $sQuery );                    
            $stmt->execute($aParams);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOExecption $e) { 
            return $this->conn->errorInfo();
        }  
    }
    
}