<?php
    $sRootPath = dirname(__FILE__);
    include_once $sRootPath.'\class\Match.class.php';
    include_once $sRootPath.'\class\Team.class.php';
    include_once $sRootPath.'\class\Database.class.php';

    /** The following function will strip the script name from URL
    *   i.e.  http://www.something.com/search/book/fitzgerald will become /search/book/fitzgerald
    */
    header('Content-Type: application/json; charset=utf-8');               

    function GetCurrentUri()
    {
        $sBasepath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
        $sURI = substr($_SERVER['REQUEST_URI'], strlen($sBasepath));        
        if (strstr($sURI, '?')) $sURI = substr($sURI, 0, strpos($sURI, '?'));
        return trim($sURI, '/');
        
    }

    $sBaseUrl = GetCurrentUri();
    RedirectRoutes($sBaseUrl);

    /*
    Now, $routes will contain all the routes. $routes[0] will correspond to first route.
    For e.g. in above example $routes[0] is search, $routes[1] is book and $routes[2] is fitzgerald
    */
    function RedirectRoutes($sBaseUrl){
        $Received = file_get_contents('php://input');
        $oJsonReceived = json_decode($Received);  
        $database = new Database();
        $oDB = $database->getConnection();

        $routes = array();
        $routes = explode( '/', $sBaseUrl );
        foreach( $routes as $route ) {
            if( trim( $route ) != '' ){
                $routes[] = $route;
            }
        }

        switch ( $routes[0] ) {
            case "add-team":
                echo "add-team ";
                $oMatch = new Team( $oDB );
                echo $oMatch->Create( $oJsonReceived );
                break;
            case "find-team":
                if($_SERVER['REQUEST_METHOD'] !== 'GET') die('Error method of sending invalid!');   
                $oMatch = new Team( $oDB );
                $oMatches = $oMatch->FindTeamMatches( $routes[1] );                
                echo json_encode( $oMatches );
                break;
            case "add-match":
                echo "add-match";
                $oMatch = new Match( $oDB );
                $idRecord = $oMatch->Create( $oJsonReceived );                
                if($idRecord == 0) die('Error! Check that both teams exist.');
                echo $idRecord;
                break;            
            case "find-match-day":                               
                if($_SERVER['REQUEST_METHOD'] !== 'GET') die('Error method of sending invalid!');                            
                $oMatch = new Match( $oDB );
                $oMatches = $oMatch->FindMatchesByDay( $routes[1] );
                echo json_encode( $oMatches );
                break;
        }

    }
