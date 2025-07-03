<?php 

class FaceRecognize extends \Controller {
    public function index(): void {
        $this->loadView('face-recognize-ui');
    }
}

$faceRecognize = new FaceRecognize();  
$faceRecognize->index();