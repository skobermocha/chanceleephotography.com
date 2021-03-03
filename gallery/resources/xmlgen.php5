<?php 
/** 
 * fromvega XML Generator DIR 
 * 
 * Generates an XML file from files in a directory based on an user XML model. 
 * 
 * Your XML model must be a well formed XML file with an example of the code 
 * that you want to be created for each file and each directory. The root tag 
 * will be kept and each children of the model will be duplicated for each file. 
 * 
 * You can also run this script through the command line to interact with the 
 * script as it generates the XML files. 
 * 
 *         Usage: php xmlgen2_dir.php dir1 dir2 dir3 dirn 
 * 
 * If you need to generate an XML from records in a database table please use 
 * the other flavor of this script (DB) found at fromvega.com. 
 * 
 * @author fromvega 
 * @version 2.1 
 * @link http://fromvega.com 
 */ 

/** 
 * Customize the behavior of the script 
 * 
 */ 
// list of directories to be read with no trailing slash 
$directoryList = array('images'); 

// name of the XML file 
$xmlFileName = '../photos.xml'; 

// list of researched image file types
$imageTypeList = array("jpg","jpeg");

// name of the XML model 
$xmlModelName = '../resources/viewermodel.xml'; 

// if the script should invert Windows slashes ('\' to '/') 
$invertSlash = true; 

/** 
 * Code - Do not modify below this point (unless you know what you're doing) 
 * 
 */ 

/** 
 * Preparing the XML data 
 */ 

// create DOMDocument from the XML model 
$dom = DOMDocument::load($xmlModelName); 

// get the root element 
$doc = $dom->documentElement; 

// get children 
$children  = $doc->childNodes; 

// start creating a new XML tree 
$xmlModel = '<xmlgen>'; 

// copy the model's children as string 
foreach ($children as $child) { 
    $xmlModel .= $dom->saveXML($child); 
} 

// closes the root tag 
$xmlModel .= '</xmlgen>'; 

// remove children 
$doc->nodeValue = ''; 

// get the remaining nodes as string 
$xmlRootModel = $dom->saveXML(); 


/** 
 * Check arguments and update the directory list 
 */ 

// if CLI and if have arguments 
if(PHP_SAPI == 'cli' && $argc > 1){ 

    // remove the first argument 
    array_shift($argv); 

    // add to the directory list 
    foreach ($argv as $dir){ 
        array_push($directoryList, $dir); 
    } 

} 
// or check for 'dirlist' GET variable 
else if(isset($_GET['dirlist'])) { 

    // add to the directory list 
    foreach (explode(',',$_GET['dirlist']) as $dir){ 
        array_push($directoryList, $dir); 
    } 
} 

/** 
 * Start the directory walk 
 */ 

// for HTML display purposes 
if(PHP_SAPI !== 'cli') echo "<pre>\n"; 

// informative display 
echo "--------------------------------------------\n\n"; 
echo "   fromvega XML Generator 2 - fromvega.com\n\n"; 


// loop through the directories list 
foreach ($directoryList as $directory) { 

    // informative display 
    echo "---------------------------------------------\n"; 
    echo "Creating XML file for '$directory'...\n"; 

    try { 
        // create a new DirectoryIterator for the current directory 
        $dir = new DirectoryIterator($directory); 
    } catch (Exception $e){ 
        echo "Couldn't open the directory '$directory'.\n"; 
        continue; 
    } 

    // format variable 
    $directory = $invertSlash ? str_replace('\\', '/', $directory) : $directory; 
    $pathname  = $invertSlash ? ($directory.'/'.$xmlFileName) : ($directory . DIRECTORY_SEPARATOR . $xmlFileName); 

    // replace placeholders of the root tag 
    $xmlRootModel = str_replace('{dirname}', $directory, $xmlRootModel); 
    $xmlRootModel = str_replace('{filename}', $xmlFileName, $xmlRootModel); 
    $xmlRootModel = str_replace('{pathname}', $pathname, $xmlRootModel); 

    $xmlRootModel = preg_replace('/{callback:(\w+?)}/e', 'call_user_func($1, $pathname)', $xmlRootModel); 
    $xmlRootModel = preg_replace('/{input:(\w+?)}/e', 'getUserInput($1, $filename)', $xmlRootModel); 

    // recreate the DOM with the new data 
    $dom->loadXML($xmlRootModel); 
    $doc = $dom->documentElement; 

    // flag to be used as the file id 
    $fileId = 1; 

    // loop through the entries of the directory 
    foreach($dir as $file){ 

        // if it's a file 
		if($file->isFile()){

            // make a copy of the model 
            $xmlFile = $xmlModel; 

            // format variables 
            $filename  = $file->getFilename(); 
            $pathname  = $invertSlash ? str_replace('\\', '/', $file->getPathname()): $file->getPathname(); 

            // replace placeholders 
            $xmlFile = str_replace('{filename}', $filename, $xmlFile); 
            $xmlFile = str_replace('{dirname}', $directory, $xmlFile); 
            $xmlFile = str_replace('{pathname}', $pathname, $xmlFile); 
            $xmlFile = str_replace('{id}', $fileId, $xmlFile); 

            $xmlFile = preg_replace('/{callback:(\w+?)}/e', 'call_user_func($1, $pathname)', $xmlFile); 
            $xmlFile = preg_replace('/{input:(\w+?)}/e', 'getUserInput($1, $filename)', $xmlFile); 

            // increments the file id number 
            $fileId++; 

            /** 
             * Finishes the XML file 
              */ 

            // load the new data 
            $newDom  = DOMDocument::loadXML($xmlFile); 

            // add each node to the DOMDocument 
            foreach ($newDom->documentElement->childNodes as $child) { 
                $newNode = $dom->importNode($child, true); 
                $doc->appendChild($newNode); 
            } 
        } 
    } 

    // creates the XML file 
    $dom->save($directory.'/'.$xmlFileName); 

} 

// informative display 
echo "Done!\n"; 
if(PHP_SAPI !== 'cli') echo '</pre>'; 

/** 
 * Callback and other functions 
 */ 

/** 
 * Get the user input from the CLI 
 * 
 * @param string $desc 
 * @return string 
 */ 
function getUserInput($desc, $filename){ 

    // if cliMode ask for input otherwise leave blank 
    if(PHP_SAPI == 'cli'){ 
        echo "Enter '$filename' $desc: "; 
        return trim(fgets(STDIN)); 
    } else { 
        return ' '; 
    } 

} 

/** 
 * Returns the date 
 * 
 * @param string $path 
 * @return string 
 */ 
function today($path){ 
    return date('d/m/y'); 
} 

/** 
 * Discover the orientation of an image 
 * 
 * @param string $path 
 * @param int $id 
 * @return string 
 */ 
function discoverwidth($path){ 

    try { 
        // get image information 
        if(!$info = getimagesize($path)){ 
            throw new Exception('Not valid!'); 
        } 
    } catch (Exception $e){ 
        return 'unknown'; 
    } 
 
        return $info[0]; 
    
} 

function discoverheight($path){ 

    try { 
        // get image information 
        if(!$info = getimagesize($path)){ 
            throw new Exception('Not valid!'); 
        } 
    } catch (Exception $e){ 
        return 'unknown'; 
    } 
 
        return $info[1]; 
    
}
?>