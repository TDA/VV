<?php
/*!
 * KeyLemon.js
 * https://developers.keylemon.com/
 *
 * Copyright (c) 2014, keylemon.com
 * All rights reserved
 *
 *
 Redistribution and use in source and binary forms, with or without
 modification, are permitted provided that the following conditions are met:
 * Redistributions of source code must retain the above copyright
 notice, this list of conditions and the following disclaimer.
 * Redistributions in binary form must reproduce the above copyright
 notice, this list of conditions and the following disclaimer in the
 documentation and/or other materials provided with the distribution.
 * Neither the name of the <organization> nor the
 names of its contributors may be used to endorse or promote products
 derived from this software without specific prior written permission.

 THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 DISCLAIMED. IN NO EVENT SHALL <COPYRIGHT HOLDER> BE LIABLE FOR ANY
 DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

 */

if (session_id() == '') { session_start(); }

require_once ( dirname(__FILE__).'/config.php');
require_once (WRAPPER_PATH);

class KeyLemon {

    public function get_server() {
        return SERVER;
    }

    public function set_identity($identityId) {
        $_SESSION['kl-identity'] = $identityId;
    }

    public function get_identity() {
        return isset($_SESSION['kl-identity']) ? $_SESSION['kl-identity'] : null;
    }

    public function is_authenticated() {
        return isset($_SESSION['kl-authenticated']) ? $_SESSION['kl-authenticated'] : FALSE;
    }
	
	public function clear_authentication(){
		unset($_SESSION['kl-authenticated']);
	}

}

list($path) = explode('?', $_SERVER['REQUEST_URI']);
//echo "\n<br>".$path[0];
//echo count($path);

// if script was called directly,dont ignore
if(strpos($path,'keylemon.php') !== false)  {

    $pathInfo = array();
   // $path = substr($path, strlen(dirname($_SERVER['SCRIPT_NAME']))+1);
   
    foreach (explode('/', $path) as $dir) {
        if (!empty($dir)) {
            $pathInfo[] = urldecode($dir);
	//echo "<br>".urldecode($dir);
        }
    }
    if (count($pathInfo) > 0) {
        $last = $pathInfo[count($pathInfo)-1];
        list($last) = explode('.', $last);
        $pathInfo[count($pathInfo)-1] = $last;
    }

    if ($pathInfo) {

        $api = null;

        $user_agent = isset($_SESSION['USER-AGENT']);
        if(isset($_SESSION['USER-AGENT'])){
            $api = new klAPI(USERNAME, KEY, SERVER, $_SESSION['USER-AGENT']);
        }else{
            $api = new klAPI(USERNAME, KEY, SERVER);
        }
        //echo $pathInfo[count($pathInfo)-2];
        $function = @$pathInfo[count($pathInfo)-1];
		if($pathInfo[count($pathInfo)-2]=="image")
		$function = @$pathInfo[count($pathInfo)-2];
		
		
		if($function=="\\")
		break;
		//echo "hiho<br>";
        //echo $function;
        switch ($function) {
            case 'initialize':
                $_SESSION['USER-AGENT'] = $_POST['user-agent'];
                print json_encode(array());
				break;
            case 'clear_identity':
                unset($_SESSION['kl-identity']);
            case 'clear_data':
                unset($_SESSION['kl-authenticated']);
            case 'clear_images':
                for ($i = 0; $i < 20; $i++){
                    if(array_key_exists('image-part-'.$i, $_SESSION)){
                        unset($_SESSION['image-part-'.$i]);
                    }
                }
            case 'clear_audio':
                for ($i = 0; $i < 20; $i++){
                    if(array_key_exists('sample-part-'.$i, $_SESSION)){
                        unset($_SESSION['sample-part-'.$i]);
                    }
                }
                print json_encode(array('nb_image' => getNbImage()));
                break;
            case 'push_image':
                $index = $_POST['index'];
                $_SESSION['image-part-'.$index] = $_POST['data'];
                $decoded = base64_decode($_POST['data']);
                $md5sum = md5($decoded);
                $response = array('nb_image' => getNbImage(), 'md5' => $md5sum);
                print json_encode($response);
                break;
            case 'debug_save_sample':
                $data = base64_decode($_POST['data']);
                file_put_contents("/tmp/backend.sample.wav",$data);
                print json_encode(array());
                break;
            case 'push_sample':
                $index = $_POST['index'];
                $_SESSION['sample-part-'.$index] = $_POST['data'];
                $response = array();
                print json_encode($response);
                break;
            case 'add_model':
                // Create an empty identity if it doesn't exist
                if( !isset($_SESSION['kl-identity'])){
                    $response = $api->create_identity(null, null, null, null, null);
                    $_SESSION['kl-identity'] = $response->identity_id;
                }

                if($_POST['modality'] === 'face'){
                    $images = "";
                    for ($i = 0; $i < 20; $i++){
                        if(array_key_exists('image-part-'.$i, $_SESSION)){
                            $images .= $_SESSION['image-part-'.$i].',';
                        }else{
                            break; 
                        }
                    }
                    $images = substr($images, 0, (strlen($images) - 1));
                    $datasArray = array();
                    if ($images !== '') {
                        $images = explode(',', $images);
                        foreach ($images as $key => $data)
                            $datasArray[] = base64_decode($data);
                    }
                }

          		if($_POST['modality'] === 'speaker'){
                    $samples = "";
                    for ($i = 0; $i < 20; $i++){
                        if(array_key_exists('sample-part-'.$i, $_SESSION)){
                            $samples .= $_SESSION['sample-part-'.$i].',';
                        }else{
                            break; 
                        }
                    }
                    $samples = substr($samples, 0, (strlen($samples) - 1));
                    $datasArray = array();
                    if ($samples !== '') {
                        $samples = explode(',', $samples);
                        foreach ($samples as $key => $data)
                            $datasArray[] = base64_decode($data);
                    }
                }

                if($_POST['modality'] === 'face'){
                    $response = $api->create_model(null, $datasArray, null);
                }
                if($_POST['modality'] === 'speaker'){
                    $response = $api->create_speaker_model(null, $datasArray, null);
                }
                if(array_key_exists('model_id', $response)){
                    $model_id = $response->model_id;
                    $add_model_response = $api->add_models_to_identity($_SESSION['kl-identity'], array($model_id));
                    $response->identity_id = $_SESSION['kl-identity'];
                }
                print json_encode($response);
                break;
            case 'remove_model':
                $response = $api->delete_model($_POST['model_id']);
                print json_encode($response);
                break;
            case 'get_identity':
                $response = $api->get_identity($_SESSION['kl-identity']);
                print json_encode($response);
                break;
            case 'image':
                if($pathInfo[count($pathInfo)-2] == "image"){
                   $imageuid = $pathInfo[count($pathInfo)-1];
				   //changed to accomodate the change in the pathinfo
				   //echo 'im in';
				   //echo $imageuid;
                }else{
                   $imageuid = $pathInfo[1];
				   //echo 'im in2';
                }
				echo $imageuid;
				$response = $api->get_image($imageuid);
                header('Content-type: image/jpeg');
                echo $response;
				
                break;
            case 'list_identities':
                $response = $api->list_identities($_POST['limit'], $_POST['offset']);
                print json_encode($response);
                break;
            case 'create_stream' :
                $response = $api->open_new_stream(null, $_SESSION['kl-identity']);
                print json_encode($response);
                break;
            case 'read_stream' :
                $response = $api->get_stream_state($_POST['stream_id']);
                $_SESSION['kl-authenticated'] = $response->authenticated;
                print json_encode($response);
                break;
            case 'recognize_speaker':
                if (!array_key_exists('kl-identity', $_SESSION)){
                    print json_encode(array('errors' => 'Identity is null'));
                    break;
                }
                $datasArray = array();
                $datasArray[0] = base64_decode($_POST['data']);
                $response = $api->recognize_speaker(null, array($_SESSION['kl-identity']), null, $datasArray, null, FALSE);

                if(property_exists($response, 'audios')){
                    $score = $response->audios[0]->results[0]->score;
                    if($score >= SPEAKER_AUTHENTICATION_THRESHOLD){
                        $_SESSION['kl-authenticated'] = true;
                    }else{
                        $_SESSION['kl-authenticated'] = false;
                    }
                }else{
                    $_SESSION['kl-authenticated'] = false;
                }
                $response->authenticated = $_SESSION['kl-authenticated'];
                print json_encode($response);
                break;
            case 'test':
                $configurationSuccess = true;
                $communicationSuccess = false;
                if (array_key_exists('format', $_GET)){
                    $format = $_GET['format'];
                }else{
                    $format = 'html';
                }
                $apiResponse = $api->get_usage();
                if($apiResponse == null){
                    $communicationSuccess = false;
                }else{
                    $communicationSuccess = true;
                }

                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $apiHost = $_POST['api_host'];
                    if($apiHost != SERVER){
                        $configurationSuccess = false;
                    }
                }

                printTestResponse($format, $communicationSuccess, $configurationSuccess);
                break;

            default :
                print json_encode(array('errors' => 'The function "'.$function.'" is not available'));
                break;
        }
    }
}

function printTestResponse($format, $comm, $conf){

    if ($format == 'html'){
        print "<h2>&#10004; Server configuration</h2>";
        if(!$comm){
            print "<h2>&#10008; KeyLemon API communication error.</h2>";
        }
        if(!$conf){
            print "<h2>&#10008; KeyLemon API configuration error.</h2>";
        }
        if($comm && $conf){
            print "<h2>&#10004; Test success !</h2>";
        }
    }

    if ($format == 'json'){
        $jsonResponse = array();
        $jsonResponse["success"] = false;
        $errorList= array();
        if(!$comm){
            array_push($errorList, array('message' => 'Communication error'));
        }
        if(!$conf){
            array_push($errorList, array('message' => 'Configuration error'));
        }
        if($comm && $conf){
            $jsonResponse["success"] = true;
        }
        $jsonResponse["errors"] = $errorList;
        print json_encode($jsonResponse);
    }
}


function getNbImage(){
    $i = 0;
    for ($i; $i < 20; $i++){
        if(array_key_exists('image-part-'.$i, $_SESSION)){
        }else{
            break; 
        }
    }   
    return $i;
}