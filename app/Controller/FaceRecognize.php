<?php 
session_start();

class FaceRecognize extends \Controller {
    public function index(): void {
        $this->loadView('face-recognize-ui');
    }
}

$faceRecognize = new FaceRecognize();  
if(isset($_SESSION['username'])){
    $faceRecognize->index();
}
