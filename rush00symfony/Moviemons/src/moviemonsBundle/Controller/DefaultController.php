<?php

namespace moviemonsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class DefaultController extends Controller
{
	public function createMap($position, $alive, $capture){
        $str = "<a href='/options'>Options</a><table>";

        for ($i=0; $i <= 4; $i++) {
            $str .= "<tr>";
            for ($u=0; $u <= 4; $u++) {
                if ($i == $position[0] && $u == $position[1]){
                    $str .= "<td style='background-color : green;'>O<br>/|\<br>^</td>";
                }
                else if ( $i == $position[0] - 1 && $u == $position[1]) {
                    $str .= "<td><a style='font-size: 24px;' href='/fight?position=".$i.",".$u."'>^</a></td>";
                }
                else if ( $i == $position[0] && $u == $position[1] - 1 ) {
                    $str .= "<td><a style='font-size: 24px;' href='/fight?position=".$i.",".$u."'><</a></td>";
                }
                else if ( $i == $position[0] && $u == $position[1] + 1 ) {
                    $str .= "<td><a style='font-size: 24px;' href='/fight?position=".$i.",".$u."'>></a></td>";
                }
                else if ( $i == $position[0] + 1 && $u == $position[1] ) {
                    $str .= "<td><a style='font-size: 19px;' href='/fight?position=".$i.",".$u."'>V</a></td>";
                }
                else {
                    $str .= "<td></td>";
                }
            }
            $str .= "</tr>";
        }
        $str .= "</table><br>";

        if ($alive){
            $str .= "<h3>wild ones</h3><br><div>";
            foreach ($alive as $key => $movie) {
                $str .= "
                    <img style='width : 100px; height : 150px;' src='".$movie[3]."'>";
            }
            $str .= "</div>";
        }
        if ($capture){
            $str .= "<h3>mine</h3><br><div>";
            foreach ($capture as $key => $movie) {
                $str .= "
                    <img style='width : 100px; height : 150px;' src='".$movie[3]."'>";
            }
            $str .= "</div>";
        }

        $str .= "<br><br><a href='/moviedex' >MOVIEDEX</a>";

        return $str;
    }

    /**
    * @Route("/", name="home")
    */
    public function home()
    {
    	return $this->redirect("/options", 308);
    }

    /**
    * @Route("/options", name="options")
    */
    public function options()
    {
        $menu_items = array('new', 'save', 'load');

        $str = "<ul>";
        foreach ($menu_items as $key => $item) {
            $str .= "<li><a href='/".$item."'>".$item."</a></li>";
        }
        if ($this->get('session')->get('name')){
            $str .= "<li><a href='/map'>cancel</a></li>";
        }
        $str .= "</ul>";

        return $this->render('moviemonsBundle::index.html.twig', array('content' => $str));
    }

    /**
    * @Route("/new", name="newGame")
    */
    public function newGame(Request $request)
    {
    	if ($username = $request->query->get('name')){
    		$trainer = array(100, 10);
	        $this->get('session')->clear();
	        $this->get('session')->set('name', $username);
	        $this->get('session')->set('trainer', $trainer);
	        $this->get('session')->set('position', array("2", "2"));

	        $movies = array("tt1517451", "tt0372784", "tt0068646", "tt0111161", "tt0071562", "tt0468569", "tt0050083", "tt0108052", "tt0167260", "tt0110912");

	        $moviemons = array();
	        foreach ($movies as $key => $value) {
	            $url = "http://www.omdbapi.com/?apikey=9f98a5ae&i=".$value;
	            //  Initiate curl
	            $ch = curl_init();
	            // Disable SSL verification
	            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	            // Will return the response, if false it print the response
	            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	            // Set the url
	            curl_setopt($ch, CURLOPT_URL,$url);
	            // Execute
	            $result=curl_exec($ch);
	            // Closing
	            curl_close($ch);

	            // Will dump a beauty json :3
	            $tmp = json_decode($result, true);
	            $moviemons[$value] = array($tmp["Title"], $tmp["Metascore"], $tmp["imdbRating"], $tmp["Poster"], $tmp['Director'], $tmp['Plot'], $value);
	        }
	        $this->get('session')->set('moviemonsalive', $moviemons);

	        $position = array("2", "2");
	        return $this->Redirect('/map', 308);
    	}
    	else {
    		$this->get('session')->clear();
    		$dir = str_replace("Controller", "", __DIR__);
            $str = file_get_contents($dir."/Resources/views/new.html.twig");
            return $this->render('moviemonsBundle::index.html.twig', array('content' => $str));
    	}
    }

    /**
    * @Route("/map", name="map")
    */
    public function map(Request $request)
    {
        if ( $request->query->get('position') ){
            $this->get('session')->set('position', explode(",", $request->query->get('position')));
            $position = $this->get('session')->get('position');
        }
        else {
            $position = $this->get('session')->get('position');
        }
        $trainer_data = $this->get('session')->get('trainer');
        $trainer_data[0] = 100;
        $this->get('session')->set('trainer', $trainer_data);
        $str = $this->createMap($position, $this->get('session')->get('moviemonsalive'), $this->get('session')->get('moviemonscapture'));

        return $this->render('moviemonsBundle::index.html.twig', array('content' => $str));
    }

    /**
    * @Route("/moviedex", name="moviedex")
    */
    public function moviedex()
    {
        if ( $this->get('session')->get('moviemonscapture') ){
            $str = "<h1>MOVIEDEX</h1><ul>";
            $moviemonscapture = $this->get('session')->get('moviemonscapture');
            foreach ($moviemonscapture as $key => $movie) {
                $str .= "<li><a href='/movie?movie_id=".$key."'><img src='".$movie[3]."'><h3>".$movie[0]."</h3></a></li>";
            }
            $str .= "</ul>";
            $str .= "<a href='/map'>back to the map</a>";
            return $this->render('moviemonsBundle::index.html.twig', array('content' => $str));
        }
        else {
            $str = "<h3>you havent capture any moviemon yet !</h3><a href='/map'>back to the map</a>";
            return $this->render('moviemonsBundle::index.html.twig', array('content' => $str));
        }
    }

    /**
    * @Route("/movie", name="movie")
    */
    public function movie(Request $request)
    {
        if ($request->query->get('movie_id') !== NULL){
        	$moviemonscapture = $this->get('session')->get('moviemonscapture');
            $movie_id = $request->query->get('movie_id');
            
            if ( array_key_exists($movie_id, $moviemonscapture)){
                $str = "<img src='".$moviemonscapture[$movie_id][3]."'><h3>".$moviemonscapture[$movie_id][0]."</h3>by<h5>".$moviemonscapture[$movie_id][4]."</h5><br>plot :<p>".$moviemonscapture[$movie_id][5]."</p>";
                $str .= "<a href='/map'>back to the map</a>";
                return $this->render('moviemonsBundle::index.html.twig', array('content' => $str));
            }
            else {
                return $this->Redirect('/map', 308);
            }
        }
    }

    /**
    * @Route("/fight", name="fight")
    */
    public function fight(Request $request)
    {
    	// echo $this->get('request')->getSchemeAndHttpHost();
    	// echo "<br>";
    	echo $this->getRequest()->headers->get('referer');
        if ( $request->query->get('position') && $this->getRequest()->headers->get('referer') == $this->get('request')->getSchemeAndHttpHost().'/map' && $this->get('session')->get('current_fight') == null){
            $this->get('session')->set('position', explode(",", $request->query->get('position')));
            $moviemonsleft = $this->get('session')->get('moviemonsalive');

            if (empty($moviemonsleft)){
            	return $this->Redirect('/map', 308);
            }
            else {
            	$moviemon_id = array_rand($moviemonsleft);

	            $moviemons_data = $moviemonsleft[$moviemon_id];
	            $trainer_data = $this->get('session')->get('trainer');


	            $fight = array('turn' => 0, 'id' => $moviemon_id, 'hp' => $moviemons_data[1], 'pw' => $moviemons_data[2]);
	            $this->get('session')->set('current_fight', $fight);

	            $str = "<img src='".$moviemons_data[3]."'><h1>".$moviemons_data[0]."</h1><br><h3>moviemon hp : ".$moviemons_data[1]."<br>moviemon pw : ".$moviemons_data[2]."</h3><br><h3>trainer hp : ".$trainer_data[0]."<br>trainer pw : ".$trainer_data[1]."</h3><a href='/fight'>FIGHT</a><br><a href='/map'>retour map</a>";
	            return $this->render('moviemonsBundle::index.html.twig', array('content' => $str));
	        }
        }
        else if ($request->query->get('position') == null) {
            $fight = $this->get('session')->get('current_fight');
            $trainer_data = $this->get('session')->get('trainer');
            if ( $fight['turn'] == 0 ){
                if ( rand( 0, 10 ) + $trainer_data[1] > 15 ){
                    $fight['hp'] = $fight['hp'] - $trainer_data[1];
                    $fight['turn'] = 0;
                    $this->get('session')->set('current_fight', $fight);
                }
                else {
                    $fight['turn'] = 1;
                    $this->get('session')->set('current_fight', $fight);
                }
            }
            else {
                $trainer_data[0] = $trainer_data[0] - $fight['pw'];
                $fight['turn'] = 0;
                $this->get('session')->set('current_fight', $fight);
                $this->get('session')->set('trainer', $trainer_data);
            }
            if ( $fight['hp'] <= 0 ){
                $this->get('session')->set('current_fight', NULL);
                $trainer = array(100, 60);
                $this->get('session')->set('trainer', $trainer);
                $trainer_data[0] = 100;
                $trainer_data[1] = $trainer_data[1]+1;
                $this->get('session')->set('trainer', $trainer_data);
                $moviemons_data = $this->get('session')->get('moviemonsalive');
                $tmp = $moviemons_data[$fight['id']];
                unset($moviemons_data[$fight['id']]);
                $this->get('session')->set('moviemonsalive', $moviemons_data);
                $moviemons_capture = $this->get('session')->get('moviemonscapture');
                if ( $moviemons_capture == NULL ){
                    $moviemons_capture = array();
                    $moviemons_capture[0] = $tmp;
                }
                else {
                    array_push($moviemons_capture, $tmp);
                }
                $this->get('session')->set('moviemonscapture', $moviemons_capture);
                $position = $this->get('session')->get('position');
                if ( empty($moviemons_data) ){
                    $this->get('session')->set('gg', true);
                    $url = "/gg";
                    return $this->Redirect($url, 308);
                }
                else {
                	return $this->Redirect('/map', 308);
                }
            }
            else if ($trainer_data[0] <= 0){
                $this->get('session')->clear();
                $url = "/gameover";
                $this->get('session')->set('gameover', true);
                return $this->Redirect($url, 308);
            }
            $moviemons_data = $this->get('session')->get('moviemonsalive'); 
            $moviemons_data = $moviemons_data[$fight['id']];

            $str = "<img src='".$moviemons_data[3]."'><h1>".$moviemons_data[0]."</h1><br><h3> moviemonhp : ".$fight['hp']."<br>moviemon pw : ".$moviemons_data[2]."</h3><br><h3>trainer hp : ".$trainer_data[0]."<br>trainer pw : ".$trainer_data[1]."</h3><a href='/fight'>FIGHT</a><br><a href='/map'>retour map</a>";
            return $this->render('moviemonsBundle::index.html.twig', array('content' => $str));
        }
        else {
            $this->get('session')->set('current_fight', null);
        	return $this->Redirect('/map', 308);
        }
    }

    /**
    * @Route("/save", name="save")
    */
    public function save()
    {
        if ($this->get('session')->get('name')){
            $name = $this->get('session')->get('name');
            $trainer = $this->get('session')->get('trainer');
            $position = $this->get('session')->get('position');
            $moviemons_alive = $this->get('session')->get('moviemonsalive');
            $moviemons_capture = $this->get('session')->get('moviemonscapture');

            $json = json_encode(array( 'trainer' => $trainer, 'position' => $position, 'moviemons_alive' => $moviemons_alive, 'moviemons_capture' => $moviemons_capture));
            file_put_contents($name.".json", $json);
            return $this->Redirect('/map', 308);
        }
        else {
        	return $this->Redirect('/options', 308);
        }
    }

    /**
    * @Route("/load", name="load")
    */
    public function load(Request $request)
    {
        if ( $request->query->get('name') ){

        	$saves = preg_grep('~\.(json)$~', scandir("."));
        	foreach ($saves as $key => $value) {
        		$saves[$key] = str_replace(".json", "", $value);
            }
            if (in_array($request->query->get('name'), $saves)){
            	$data = file_get_contents($request->query->get('name').".json");
	            $data = json_decode($data, true);
                if ($data != null){
                    $this->get('session')->set('name', $request->query->get('name'));
                    $this->get('session')->set('trainer', $data['trainer']);
                    $this->get('session')->set('position', $data['position']);
                    $this->get('session')->set('moviemonsalive', $data['moviemons_alive']);
                    $this->get('session')->set('moviemonscapture', $data['moviemons_capture']);
                    return $this->Redirect('/map', 308);
                }
	            else {
                    unlink($request->query->get('name').".json");
                    return $this->Redirect('/load', 308);
                }
            }
            else {
            	return $this->Redirect('/load', 308);
            }
        }
        else {
            $saves = preg_grep('~\.(json)$~', scandir("."));
            $str = "<ul>";
            foreach ($saves as $key => $value) {
                $str .= "<li><a href='/load?name=".str_replace(".json", "", $value)."'>".str_replace(".json", "", $value)."</a></li>";
            }
            $str .= "</ul>";
            return $this->render('moviemonsBundle::index.html.twig', array('content' => $str));
        }
    }

    /**
    * @Route("/gameover", name="gameover")
    */
    public function gameover()
    {
    	if ($this->get('session')->get('gameover') == true){
    		$menu_items = array('new', 'load');
	        $str = "<ul style'padding-top : 100px;' >";
	        foreach ($menu_items as $key => $item) {
	            $str .= "<li style='list-style-type: none;'><a href='/".$item."'>".$item."</a></li>";
	        }
	        $str .= "</ul>";
	        $str .= "<h2>GAME OVER</h2>";
	        return $this->render('moviemonsBundle::index.html.twig', array('content' => $str));
    	}
    	else {
    		return $this->Redirect('/options', 308);
    	}
    }

    /**
    * @Route("/gg", name="gg")
    */
    public function gg()
    {
    	if ($this->get('session')->get('gg') == true){
    		$menu_items = array('new', 'save', 'load');
	        $str = "<ul style'padding-top : 100px;' >";
	        foreach ($menu_items as $key => $item) {
	            $str .= "<li style='list-style-type: none;'><a href='/".$item."'>".$item."</a></li>";
	        }
	        $str .= "</ul>";
	        $str .= "<h2>WELL DONE</h2>";
	        return $this->render('moviemonsBundle::index.html.twig', array('content' => $str));
    	}
    	else {
    		return $this->Redirect('/options', 308);
    	}
    }

}

?>