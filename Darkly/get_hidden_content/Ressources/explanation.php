Run this php code in order to get the flag hidden in the site files.

<?php
// we stack every new readme content encounter in this array
$content = array();

function get_hidden_content($path) {

  global $content;

  //get page content
  $page_content = file_get_contents($path);
  //get all links to folder or readme file with this regex
  $regex = '/<a href="(.+)">/';
  preg_match_all($regex, $page_content, $data);
  //get only href data
  $urls = $data[1];

  foreach($urls as $url) {
    if ($url == "../")
      //ignore this link
      continue ;
    if ($url == "README") {
      //get readme content
      $str = trim(file_get_contents($path.$url));
      // we found new readme content ! we display it 
      if ( !in_array($str, $content) ) {
        echo "path :\n-> ".$path.$url."\ncontent :\n-> ".$str."\n\n";
        $content[$str] = $str;
      }
    } else {
      //call the function recursively with the sub folder path
      $sub_folder_path = $path.$url;
      get_hidden_content($sub_folder_path);
    }
  }
}

get_hidden_content("http://192.168.56.101/.hidden/");
?>