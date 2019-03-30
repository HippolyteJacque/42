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
                    $str .= "<td><a style='font-size: 24px;' href='/Fight?position=".$i.",".$u."'>^</a></td>";
                }
                else if ( $i == $position[0] && $u == $position[1] - 1 ) {
                    $str .= "<td><a style='font-size: 24px;' href='/Fight?position=".$i.",".$u."'><</a></td>";
                }
                else if ( $i == $position[0] && $u == $position[1] + 1 ) {
                    $str .= "<td><a style='font-size: 24px;' href='/Fight?position=".$i.",".$u."'>></a></td>";
                }
                else if ( $i == $position[0] + 1 && $u == $position[1] ) {
                    $str .= "<td><a style='font-size: 19px;' href='/Fight?position=".$i.",".$u."'>V</a></td>";
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

        $str .= "<br><br><a href='/Moviedex' >MOVIEDEX</a>";

        return $str;
    }

    /**
     * @Route("/options", name="home")
     */
    public function indexAction()
    {
        $menu_items = array('New', 'Save', 'Load');

        $str = "<ul>";
        foreach ($menu_items as $key => $item) {
            $str .= "<li><a href='/".$item."'>".$item."</a></li>";
        }
        if ($this->get('session')->get('name')){
            $str .= "<li><a href='/Map'>cancel</a></li>";
        }
        $str .= "</ul>";

        return $this->render('moviemonsBundle::index.html.twig', array('content' => $str));
    }

    /**
     * @Route("/{page}", name="pagehandle")
     */
    public function pageHandle($page, Request $request)
    {
        $menu_items = array('New', 'Save', 'Load');
        if ( $page == "New" && ($username = $request->query->get('name')) ){

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
            $str = $this->createMap($position, $this->get('session')->get('moviemonsalive'), $this->get('session')->get('moviemonscapture'));
            return $this->render('moviemonsBundle::index.html.twig', array('content' => $str));
        }
        else if ( $page == "Map"){
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
        else if ( $page == "Moviedex" ){
            if ( $this->get('session')->get('moviemonscapture') ){
                $str = "<h1>MOVIEDEX</h1><ul>";
                $moviemonscapture = $this->get('session')->get('moviemonscapture');
                foreach ($moviemonscapture as $key => $movie) {
                    $str .= "<li><a href='/Movie?movie_id=".$key."'><img src='".$movie[3]."'><h3>".$movie[0]."</h3></a></li>";
                }
                $str .= "</ul>";
                $str .= "<a href='/Map'>back to the map</a>";
                return $this->render('moviemonsBundle::index.html.twig', array('content' => $str));
            }
            else {
                $str = "<h3>you havent capture any moviemon yet !</h3><a href='/Map'>back to the map</a>";
                return $this->render('moviemonsBundle::index.html.twig', array('content' => $str));
            }
        }
        else if ( $page == "Movie" && $request->query->get('movie_id') !== NULL ){
            $moviemonscapture = $this->get('session')->get('moviemonscapture');
            $movie_id = $request->query->get('movie_id');
            
            if ( $moviemonscapture[$movie_id] ){
                $str = "<img src='".$moviemonscapture[$movie_id][3]."'><h3>".$moviemonscapture[$movie_id][0]."</h3>by<h5>".$moviemonscapture[$movie_id][4]."</h5><br>plot :<p>".$moviemonscapture[$movie_id][5]."</p>";
                $str .= "<a href='/Map'>back to the map</a>";
                return $this->render('moviemonsBundle::index.html.twig', array('content' => $str));
            }
            else {
                $str = "<h2>You dont own this moviemon !</h2>";
                $str .= "<a href='/Map'>back to the map</a>";
                return $this->render('moviemonsBundle::index.html.twig', array('content' => $str));
            }
        }
        else if ( $page == "Fight" ){
            if ( $request->query->get('position') ){


                $this->get('session')->set('position', explode(",", $request->query->get('position')));
                $moviemonsleft = $this->get('session')->get('moviemonsalive');

                $moviemon_id = array_rand($moviemonsleft);

                $moviemons_data = $moviemonsleft[$moviemon_id];
                $trainer_data = $this->get('session')->get('trainer');


                $fight = array('turn' => 0, 'id' => $moviemon_id, 'hp' => $moviemons_data[1], 'pw' => $moviemons_data[2]);
                $this->get('session')->set('current_fight', $fight);

                $str = "<img src='".$moviemons_data[3]."'><h1>".$moviemons_data[0]."</h1><br><h3>moviemon hp : ".$moviemons_data[1]."<br>moviemon pw : ".$moviemons_data[2]."</h3><br><h3>trainer hp : ".$trainer_data[0]."<br>trainer pw : ".$trainer_data[1]."</h3><a href='/Fight'>FIGHT</a><br><a href='/Map'>retour map</a>";
                return $this->render('moviemonsBundle::index.html.twig', array('content' => $str));
            }
            else {
                $fight = $this->get('session')->get('current_fight');
                $trainer_data = $this->get('session')->get('trainer');
                if ( $fight == NULL || empty($fight) ){

                    $str = $this->createMap($this->get('session')->get('position'), $this->get('session')->get('moviemons_alive'), $this->get('session')->get('moviemons_capture'));
                    return $this->render('moviemonsBundle::index.html.twig', array('content' => $str));
                }
                else if ( $fight['turn'] == 0 ){
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
                    $str = $this->createMap($position, $moviemons_data, $moviemons_capture);
                    if ( empty($moviemons_data) ){
                        $this->get('session')->clear();
                        $url = "/gg";
                        return $this->Redirect($url, 308);
                    }
                    return $this->render('moviemonsBundle::index.html.twig', array('content' => $str));
                }
                else if ($trainer_data[0] <= 0){
                    $this->get('session')->clear();
                    $url = "/gameover";
                    return $this->Redirect($url, 308);
                }
                $moviemons_data = $this->get('session')->get('moviemonsalive'); 
                $moviemons_data = $moviemons_data[$fight['id']];

                $str = "<img src='".$moviemons_data[3]."'><h1>".$moviemons_data[0]."</h1><br><h3> moviemonhp : ".$fight['hp']."<br>moviemon pw : ".$moviemons_data[2]."</h3><br><h3>trainer hp : ".$trainer_data[0]."<br>trainer pw : ".$trainer_data[1]."</h3><a href='/Fight'>FIGHT</a><br><a href='/Map'>retour map</a>";
                return $this->render('moviemonsBundle::index.html.twig', array('content' => $str));
            }
        }
        else if ( $page == 'Save' ){
            if ($this->get('session')->get('name')){
                $name = $this->get('session')->get('name');
                $trainer = $this->get('session')->get('trainer');
                $position = $this->get('session')->get('position');
                $moviemons_alive = $this->get('session')->get('moviemonsalive');
                $moviemons_capture = $this->get('session')->get('moviemonscapture');

                $json = json_encode(array( 'trainer' => $trainer, 'position' => $position, 'moviemons_alive' => $moviemons_alive, 'moviemons_capture' => $moviemons_capture));
                file_put_contents($name.".json", $json);
                $str = $this->createMap($position, $moviemons_alive, $moviemons_capture);
                return $this->render('moviemonsBundle::index.html.twig', array('content' => $str));
            }
            else {
                $str = "<ul>";
                foreach ($menu_items as $key => $item) {
                    $str .= "<li style='list-style-type: none;'><a href='/".$item."'>".$item."</a></li>";
                }
                $str .= "</ul>";
                if ( $page == 'gameover'){
                    $str .= "<h2>GAME OVER</h2>";  
                }
                if ( $page == "gg" ){
                    $str .= "<h2>WELL DONE</h2>";
                }
                return $this->render('moviemonsBundle::index.html.twig', array('content' => $str));
            }
        }
        else if ( $page == 'Load' ){
            if ( $request->query->get('name') ){
                $data = file_get_contents($request->query->get('name').".json");
                $data = json_decode($data, true);

                $this->get('session')->set('name', $request->query->get('name'));
                $this->get('session')->set('trainer', $data['trainer']);
                $this->get('session')->set('position', $data['position']);
                $this->get('session')->set('moviemonsalive', $data['moviemons_alive']);
                $this->get('session')->set('moviemonscapture', $data['moviemons_capture']);
                $str = $this->createMap($data['position'], $data['moviemons_alive'], $data['moviemons_capture']);
                return $this->render('moviemonsBundle::index.html.twig', array('content' => $str));
            }
            else {
                $saves = preg_grep('~\.(json)$~', scandir("."));
                $str = "<ul>";
                foreach ($saves as $key => $value) {
                    $str .= "<li><a href='/Load?name=".str_replace(".json", "", $value)."'>".str_replace(".json", "", $value)."</a></li>";
                }
                $str .= "</ul>";
                return $this->render('moviemonsBundle::index.html.twig', array('content' => $str));
            }

        }
        else if ( in_array($page, $menu_items) ){
            $dir = str_replace("Controller", "", __DIR__);
            $str = file_get_contents($dir."/Resources/views/".$page.".html.twig");
            return $this->render('moviemonsBundle::index.html.twig', array('content' => $str));
        }
        else {
            $str = "<ul style'padding-top : 100px;' >";
            foreach ($menu_items as $key => $item) {
                $str .= "<li style='list-style-type: none;'><a href='/".$item."'>".$item."</a></li>";
            }
            $str .= "</ul>";
            if ( $page == 'gameover'){
                $str .= "<h2>GAME OVER</h2>";  
            }
            if ( $page == "gg" ){
                $str .= "<h2>WELL DONE</h2>";
            }
            return $this->render('moviemonsBundle::index.html.twig', array('content' => $str));
        }
        return $this->render('moviemonsBundle:Default:index.html.twig');
    }

}

class RedirectingController extends Controller
{
    /**
     * @Route("/{url}", name="remove_trailing_slash",
     *     requirements={"url" = ".*\/$"})
     */
    public function removeTrailingSlashAction(Request $request)
    {
        // ...
    }

}