<?php
/**
 * 业务封装类
 * 
 *
 */
class YcfUpload {
    public static function actionUpload(){
        $log_ms="";
        $fdfs = new FDFS();
        if (XUtils::method() == 'POST' && isset($_FILES["file"]) && !empty($_FILES["file"])) {
            if ($_FILES["file"]["error"] > 0) {
                    $log_ms.= "Return Code: " . $_FILES["file"]["error"] . "<br />";
            }
            else {
                    if(!FileFilter::checkExtName($_FILES["file"])){
                        echo json_encode(array('code'=>501,'message'=>FileFilter::$error));
                        return;
                    }

                    $log_ms.= "Upload: " . $_FILES["file"]["name"] . "<br />";
                    $log_ms.= "Type: " . $_FILES["file"]["type"] . "<br />";
                    $log_ms.= "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
                    $log_ms.= "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";
                    $origin_file_name = $_FILES["file"]["tmp_name"];


                    $fileinfo = $fdfs->upload($origin_file_name,'');

                    if ($fileinfo) {
                        echo json_encode(array('code'=>100,'message'=>'success','content'=>$fileinfo));
                        //$result=$fdfs->download_to_buff($fileinfo['group_name'],$fileinfo['remote_filename']);
                        //var_dump($result);
                        //update file info in the database etc
                    }else{
                        $log_ms.="Get error: ".serialize($fdfs->getError()). "<br />";
                    }
                    $log_ms.="Result: ".serialize($fileinfo). "<br />";
                    XUtils::log($log_ms,'upload');
                    //一定要释放连接
                    $fdfs->closs();
                    return;
            }
        }else{
            echo json_encode(array('code'=>500,'message'=>'no file input','content'=>''));
            return;
        }

    }

    public static function actionHello(){
        echo json_encode(array('code'=>100,'message'=>'hello fastdfs','content'=>'hello ycf'));
        return;
    }

}